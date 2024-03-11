<?php

include 'config/conexion.php';

try {

    $userPerfil = $_POST['desactivar'];
    $activo = $_POST['activo'];

    #actializa el estatus para desactivar
    $stid = oci_parse($conn, "UPDATE SPN_MSJ_USUARIOS SET STATUS = :activo 
                                WHERE USUARIO = :userPerfil");
                                    
    #oci_bind_by_name($stid, ":idUsuario", $idUsuario);
    oci_bind_by_name($stid, ":userPerfil", $userPerfil);
    oci_bind_by_name($stid, ":activo", $activo);
    oci_execute($stid);

    $resp = array('userPerfil'=>$userPerfil,'activo'=>$activo,'msg'=>'Success'); 
    echo json_encode($resp);
    #echo $arrayListado[1][0];
    #echo $xml;
} catch (\Throwable $th) {

    #$resp = array('xml'=>$request,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'msg'=>'Execution Error');
    $resp = array('msg'=>'Execution Error');
    echo json_encode($resp);
}

?>