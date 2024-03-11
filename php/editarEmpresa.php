<?php

include 'config/conexion.php';
date_default_timezone_set('America/Mazatlan');
try {

    $addIdoAnterior = $_POST['addIdoAnterior'];
    $addEmpAnterior = $_POST['addEmpAnterior'];
    $addIdoActual = $_POST['addIdoActual'];
    $addEmpActual = $_POST['addEmpActual'];


    #actualiza
    $stid = oci_parse($conn, "UPDATE SPN_MSJ_RECEPTORA SET IDO = :addIdoActual, EMPRESA = :addEmpActual
                                WHERE IDO = :addIdoAnterior AND EMPRESA = :addEmpAnterior");
                                    
    #oci_bind_by_name($stid, ":idUsuario", $idUsuario);
    oci_bind_by_name($stid, ":addIdoAnterior", $addIdoAnterior);
    oci_bind_by_name($stid, ":addEmpAnterior", $addEmpAnterior);
    oci_bind_by_name($stid, ":addIdoActual", $addIdoActual);
    oci_bind_by_name($stid, ":addEmpActual", $addEmpActual);
    oci_execute($stid);
    

    $resp = array('addIdoAnterior'=>$addIdoAnterior,'addEmpAnterior'=>$addEmpAnterior,
                  'addIdoActual'=>$addIdoActual,'addEmpActual'=>$addEmpActual,'msg'=>'Success'); 
    echo json_encode($resp);
    #echo $arrayListado[1][0];
    #echo $xml;
} catch (\Throwable $th) {

    #$resp = array('xml'=>$request,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'msg'=>'Execution Error');
    $resp = array('msg'=>'Execution Error');
    echo json_encode($resp);
}

?>