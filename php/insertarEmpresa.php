<?php

include 'config/conexion.php';
date_default_timezone_set('America/Mazatlan');
try {

    $addIdo = $_POST['addIdo'];
    $addEmp = $_POST['addEmp'];

    #inserta
    $stid = oci_parse($conn, "INSERT INTO SPN_MSJ_RECEPTORA(IDO, EMPRESA)
                                VALUES (:addIdo,:addEmp)");
                                    
    #oci_bind_by_name($stid, ":idUsuario", $idUsuario);
    oci_bind_by_name($stid, ":addIdo", $addIdo);
    oci_bind_by_name($stid, ":addEmp", $addEmp);
    oci_execute($stid);
    

    $resp = array('addIdo'=>$addIdo,'addEmp'=>$addEmp,'msg'=>'Success'); 
    echo json_encode($resp);
    #echo $arrayListado[1][0];
    #echo $xml;
} catch (\Throwable $th) {

    #$resp = array('xml'=>$request,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'msg'=>'Execution Error');
    $resp = array('msg'=>'Execution Error');
    echo json_encode($resp);
}

?>