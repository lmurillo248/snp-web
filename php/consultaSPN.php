<?php

require_once '../php/config/conexion.php';
date_default_timezone_set('America/Mazatlan');
try {
    $tableConsulta = "";
    $stid = oci_parse($conn, "SELECT ID, FECHA, SENDER, PORTID, PORTID_AUX, NOTA, MESSAGEID, ACUSE, XMLMSG 
                                FROM SPN_MSJ WHERE SENDER IS NOT NULL  ORDER BY FECHA DESC");
    oci_execute($stid);

    $tableConsulta.= "
    
        <table class='table align-items-center mb-0' style='font-size: 11px;'>
            <thead style='position: sticky'>
                <tr>
                    <th scope=col'>Secuencial</th>
                    <th scope=col'>Recibida</th>
                    <th scope=col'>Receptor</th>
                    <th scope=col'>Mensaje ID</th>
                    <th scope=col'>Acuse</th>
                    <th scope=col'>Nota</th>
                    <th scope=col'>Mensaje</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $tableConsulta.= "
            <tr>
                <td>
                    <div class='d-flex px-2 py-1'>
                        <div class='d-flex flex-column justify-content-center'>" . $row['PORTID'] . 
                        "</div>
                    </div>
                </td>
                <td>
                    <div class='d-flex px-2 py-1'>
                        <div class='d-flex flex-column justify-content-center' >" . $row['FECHA'] . 
                        "</div>
                    </div>
                </td>
                <td>
                    <div class='d-flex px-2 py-1'>
                        <div class='d-flex flex-column justify-content-center'>" . $row['SENDER'] . 
                        "</div>
                    </div>
                </td>
                <td>
                    <div class='d-flex px-2 py-1'>
                        <div class='d-flex flex-column justify-content-center' id='MilCinco'>" . $row['MESSAGEID'] . 
                        "</div>
                    </div>
                </td>
                <td>
                    <div class='d-flex px-2 py-1'>
                        <div class='d-flex flex-column justify-content-center' style='text-align: justify; text-wrap: wrap; width: 60%'>" . $row['ACUSE'] . 
                        "</div>
                    </div>
                </td>
                <td>
                    <div class='d-flex px-2 py-1'>
                        <div class='d-flex flex-column justify-content-center' style='text-align: justify; text-wrap: wrap;'>" . $row['NOTA'] . 
                        "</div>
                    </div>
                </td>
                <td>
                    <div class='d-flex px-2 py-1' style='width:150px'>
                        <div class='d-flex flex-column justify-content-center' style='text-align: justify; text-wrap: wrap; width: 100%'>" . $row['XMLMSG'] . 
                        "</div>
                    </div>
                </td>
            </tr>";
    }
    $tableConsulta.= "</tbody>
                </table>
            ";

    $resp = array('tabla'=>$tableConsulta,'msg'=>'Success');
    echo json_encode($resp);
} catch (\Throwable $th) {
    $resp = array('tabla'=>$tableConsulta,'msg'=>'Error en la ejecución');
    echo json_encode($resp);
}


?>