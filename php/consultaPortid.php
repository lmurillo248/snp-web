<?php

require_once '../php/config/conexion.php';
date_default_timezone_set('America/Mazatlan');
try {
    $idPort = $_POST['idPort'];
    $messageIdX = "";
    $tableConsultaPort = "";

    $stid = oci_parse($conn, "SELECT ID, FECHA, SENDER, PORTID, PORTID_AUX, NOTA, MESSAGEID, ACUSE, XMLMSG 
                                FROM SPN_MSJ WHERE PORTID = :idPort AND MESSAGEID = '1005'");
                                
    oci_bind_by_name($stid, ":idPort", $idPort);
    oci_execute($stid);
    
    
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $tableConsultaPort.= "<tr><td>
                <div class='d-flex px-2 py-1'>
                    <div class='d-flex flex-column justify-content-center'>PORTID: " . $row['PORTID'] . 
                    "</div>
                </div>
            </td>
            <td>
                <div class='d-flex px-2 py-1'>
                    <div class='d-flex flex-column justify-content-center'> IDO: " . $row['SENDER'] . 
                    "</div>
                </div>
            </td>
            <td>
                <div class='d-flex px-2 py-1'>
                    <div class='d-flex flex-column justify-content-center'> MENSAJE: " . $row['MESSAGEID'] . 
                    "</div>
                </div>
            </td>
            <td style='width:50%'>
                <div class='d-flex px-2 py-1'>
                    <div class='d-flex flex-column justify-content-center'> FECHA: " . $row['FECHA'] . 
                    "</div>
                </div>
            </td>
            <td style='width:50%'>
                <div class='d-flex px-2 py-1'>
                    <div class='d-flex flex-column justify-content-center'> ACUSE: " . $row['ACUSE'] . 
                    "</div>
                </div>
            </td>
            <td style='width:50%'>
                <div class='d-flex px-2 py-1' style='width:50%'>
                    <div class='d-flex flex-column justify-content-center'> RESPUESTA: " . $row['XMLMSG'] . 
                    "</div>
                </div>
            </td></tr>";
            $messageIdX = $row['MESSAGEID'];
    }

    $stidX = oci_parse($conn, "SELECT PORTID,PORTEXECDATE,REQPORTEXECDATE
                                FROM SPN_MSJ_1006  WHERE PORTID = :idPort");
    oci_bind_by_name($stidX, ":idPort", $idPort);
    oci_execute($stidX);

    $countX = oci_fetch_all($stidX, $resultSet, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

    oci_close($conn);

    $resp = array('tableConsultaPort'=>$tableConsultaPort,'messageIdX'=>$messageIdX,'countX'=>$countX,'msg'=>'Success');
    echo json_encode($resp);
} catch (\Throwable $th) {
    $resp = array('tableConsultaPort'=>$tableConsultaPort,'msg'=>'Error en la ejecución');
    echo json_encode($resp);
}


?>