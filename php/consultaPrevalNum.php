<?php

require_once '../php/config/conexion.php';
date_default_timezone_set('America/Mazatlan');

$buscaCompany = json_decode($_POST["buscaCompany"], true);

try {
    $tableConsultaCompany = "";
    // Construir la lista de valores para la cláusula IN
    $phoneNumbers = array_map(function($phone) {
        return "'" . $phone . "'";
    }, $buscaCompany['dataComp']);

    // Dividir los números en grupos de 800
    $chunks = array_chunk($phoneNumbers, 800);

    foreach ($chunks as $chunk) {
        $queryCompany = "
            WITH NUMS AS (
                SELECT COLUMN_VALUE AS PHONE
                FROM TABLE(sys.odcivarchar2list(" . implode(", ", $chunk) . "))
            ),
            PRIORIDAD1 AS (
                SELECT 
                    CAST(PHONE_NUMBER AS VARCHAR2(20)) AS PHONE, 
                    CAST(RCR AS VARCHAR2(50)) AS IDO, 
                    CAST(COMPANY_NAME AS VARCHAR2(200)) AS COMPANY
                FROM INFO_PHONE_COMPANY
                WHERE PHONE_NUMBER IN (SELECT PHONE FROM NUMS)
            ),

            PRIORIDAD2 AS (
                SELECT 
                    CAST(N.PHONE AS VARCHAR2(20)) AS PHONE,
                    CAST(Y.IDO_IDD AS VARCHAR2(50)) AS IDO,
                    CAST(X.PST AS VARCHAR2(200)) AS COMPANY
                FROM PNN_NG X
                INNER JOIN REPORTE_NNG Y 
                    ON X.PST = Y.PST
                AND X.SERIE = Y.SERIE
                JOIN NUMS N
                    ON CONCAT(X.CVE_SERVICIO, LPAD(X.SERIE, 3, '0')) = SUBSTR(N.PHONE, 1, 6)
                AND CAST(SUBSTR(N.PHONE, 7) AS INT) 
                    BETWEEN X.NUMERACION_INICIAL AND X.NUMERACION_FINAL
            ),

            PRIORIDAD3 AS (
                SELECT 
                    CAST(N.PHONE AS VARCHAR2(20)) AS PHONE,
                    CAST(C.IDO AS VARCHAR2(50)) AS IDO,
                    CAST(C.NOMBRE_CORTO AS VARCHAR2(200)) AS COMPANY
                FROM PNN_CO C
                JOIN NUMS N
                    ON CONCAT(C.NIR, C.SERIE) = SUBSTR(N.PHONE, 1, 6)
                AND CAST(SUBSTR(N.PHONE, 7) AS INT) 
                    BETWEEN C.NUMERACION_INICIAL AND C.NUMERACION_FINAL
            ),

            UNIONED AS (
                SELECT PHONE, IDO, COMPANY, 1 AS PRIORIDAD FROM PRIORIDAD1
                UNION ALL
                SELECT PHONE, IDO, COMPANY, 2 AS PRIORIDAD FROM PRIORIDAD2
                UNION ALL
                SELECT PHONE, IDO, COMPANY, 3 AS PRIORIDAD FROM PRIORIDAD3
            )

            SELECT PHONE, IDO, COMPANY
            FROM (
                SELECT 
                    U.PHONE,
                    U.IDO,
                    U.COMPANY,
                    ROW_NUMBER() OVER (PARTITION BY U.PHONE ORDER BY U.PRIORIDAD) AS RN
                FROM UNIONED U
            )

            WHERE RN = 1
        ";
        //implode toma un array y lo convierte en una cadena de texto, separando cada elemento del array con la cadena especificada

        $stid = oci_parse($conn, $queryCompany);
        oci_execute($stid);

        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $tableConsultaCompany.= "
                <tr>
                    <td>
                        ".$row['PHONE']."
                    </td>
                    <td>
                        ".$row['IDO']."
                    </td>
                    <td>
                        ".$row['COMPANY']."
                    </td>
                </tr>";
        }
    }
    
    $resp = array('tabla'=>$tableConsultaCompany,'msg'=>'Success','query'=>$queryCompany,'phone'=>$buscaCompany);
    // $resp = array('msg'=>'Success', 'phone'=>$buscaCompany);
    echo json_encode($resp);
} catch (\Throwable $th) {
    // $resp = array('tabla'=>$tableConsultaCompany,'msg'=>'Error en la ejecución');
    $resp = array('tabla'=>$tableConsultaCompany,'msg'=>'Error en la ejecución','error'=>$th,'query'=>$queryCompany,'phone'=>$buscaCompany);
    echo json_encode($resp);
}


?>