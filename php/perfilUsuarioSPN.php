<?php

include 'config/conexion.php';
date_default_timezone_set('America/Mazatlan');
try {

    $userPerfil = $_POST['userPerfil'];
    $userEmail = $_POST['userEmail'];
    $firstName = $_POST['firstName'];
    $FirstlastName = $_POST['FirstlastName'];
    $SecondlastName = $_POST['SecondlastName'];
    $selectPerfil = $_POST['selectPerfil'];
    $activo = $_POST['activo'];

    #Se ejecuta la consulta a la base de datos para validar al usuario y el password DEL ADMIN
    $stid = oci_parse($conn, "SELECT COUNT(*) AS C
                                        FROM SPN_MSJ_USUARIOS WHERE USUARIO = :usuario");

    #$user = '.p-falonso.';
    oci_bind_by_name($stid, ":usuario", $userPerfil);
    #oci_bind_by_name($stid, ":passw", $passw);
    oci_execute($stid);


    oci_fetch_all($stid, $result);
    $row = $result['C'][0];

    if ($row == 0) {
        #inserta
        $stid = oci_parse($conn, "INSERT INTO SPN_MSJ_USUARIOS(IDUSUARIO, USUARIO, NOMBRE, APELLIDOP, APELLIDOM, EMAIL, PERFIL, STATUS)
                                    VALUES (SPN_MSJ_USUARIOS_ID_SEQ.NEXTVAL,:userPerfil,:userEmail,:firstName,:FirstlastName,
                                            :SecondlastName,:selectPerfil,:activo)");
                                        
        #oci_bind_by_name($stid, ":idUsuario", $idUsuario);
        oci_bind_by_name($stid, ":userPerfil", $userPerfil);
        oci_bind_by_name($stid, ":firstName", $firstName);
        oci_bind_by_name($stid, ":FirstlastName", $FirstlastName);
        oci_bind_by_name($stid, ":SecondlastName", $SecondlastName);
        oci_bind_by_name($stid, ":userEmail", $userEmail);
        oci_bind_by_name($stid, ":selectPerfil", $selectPerfil);
        oci_bind_by_name($stid, ":activo", $activo);
        oci_execute($stid);
    }else{
        #actualiza
        $stid = oci_parse($conn, "UPDATE SPN_MSJ_USUARIOS SET USUARIO = :userPerfil, NOMBRE = :firstName, APELLIDOP = :FirstlastName, 
                                    APELLIDOM = :SecondlastName, EMAIL = :userEmail, PERFIL = :selectPerfil, STATUS = :activo 
                                    WHERE USUARIO = :userPerfil");
                                        
        #oci_bind_by_name($stid, ":idUsuario", $idUsuario);
        oci_bind_by_name($stid, ":userPerfil", $userPerfil);
        oci_bind_by_name($stid, ":firstName", $firstName);
        oci_bind_by_name($stid, ":FirstlastName", $FirstlastName);
        oci_bind_by_name($stid, ":SecondlastName", $SecondlastName);
        oci_bind_by_name($stid, ":userEmail", $userEmail);
        oci_bind_by_name($stid, ":selectPerfil", $selectPerfil);
        oci_bind_by_name($stid, ":activo", $activo);
        oci_execute($stid);
    }

    $resp = array('userPerfil'=>$userPerfil,'userEmail'=>$userEmail,'firstName'=>$firstName,
                    'FirstlastName'=>$FirstlastName,'SecondlastName'=>$SecondlastName,'selectPerfil'=>$selectPerfil,
                    'activo'=>$activo,'msg'=>'Success'); 
    echo json_encode($resp);
    #echo $arrayListado[1][0];
    #echo $xml;
} catch (\Throwable $th) {

    #$resp = array('xml'=>$request,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'msg'=>'Execution Error');
    $resp = array('msg'=>'Execution Error');
    echo json_encode($resp);
}

?>