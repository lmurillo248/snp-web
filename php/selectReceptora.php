<?php

require_once '../php/config/conexion.php';
date_default_timezone_set('America/Mazatlan');
try {
    
    $selectEmpReceptora = "";
    $stid = oci_parse($conn, "SELECT IDO, EMPRESA FROM SPN_MSJ_RECEPTORA ORDER BY IDO ASC");
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
        $selectEmpReceptora.= "<option selected value=".$row['IDO'].">".$row['EMPRESA']."</option>\n";
    }

    $resp = array('tabla'=>$selectEmpReceptora,'msg'=>'Success');
    echo json_encode($resp);
} catch (\Throwable $th) {
    $resp = array('tabla'=>$selectEmpReceptora,'msg'=>'Error en la ejecución');
    echo json_encode($resp);
}


?>