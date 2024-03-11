<?php 

include 'php/config/conexion.php';

$menscript = "";
#Se valida que el botón de inicio de sesión esté posteado.
session_start();
if(isset($_POST['signinBtn'])){

  $usuario = $_POST['usuario'];
  $passw = $_POST['pass'];

     //config.php
  $config['version'] = '1.0';
  $config['urlLdap'] = 'ldap://izzitelecom.net:3268';
  $config['baseLdap'] = 'DC=izzitelecom,DC=net';
  $config['usuario'] = $usuario;
  $config['pass'] = $passw;
  $config['baseSearch'] = 'dc=izzitelecom,dc=net';
  $config['columnaLdap'] = 'cn,mail';

 

  // conexión al servidor LDAP
  $ldapconn = ldap_connect($config['urlLdap']) or die("Could not connect to LDAP server.");

 

	if ($ldapconn) {
		// realizando la autenticación
		$ldapbind = ldap_bind($ldapconn, $config['usuario'], $config['pass']) or die("Error trying to bind: " . ldap_error($ldapconn));

 

		// verificación del enlace
		if ($ldapbind) {

			$ldaptree = 'dc=izzitelecom,dc=net';
			$search = "(mail=". $usuario . ")";

			

 
		  $result = ldap_search($ldapconn, $ldaptree, $search) or die ("Error in search query: ".ldap_error($ldapconn));

		  $data = ldap_get_entries($ldapconn, $result);
		  


		
		} else{

		  $menscript = "<script>
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text:'Usuario o contraseña invalidos',
                  footer: '<p>Something went wrong!</p>',
                  showCloseButton: true,
                  showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                  }
                })
              </script>";

	  }
		ldap_close($ldapconn);
	} 
    
   
    #Se valida que los campos de texto user & password contengan información.
    if (!empty($usuario) && !empty($passw)) {
      
        $parts = explode('@', $usuario);
        $user = $parts[0];
        var_dump($user);
        #Se ejecuta la consulta a la base de datos para validar al usuario y el password DEL ADMIN
        $stid = oci_parse($conn, "SELECT *
                                  FROM SPN_MSJ_USUARIOS WHERE USUARIO = :usuario AND STATUS = 1 AND PERFIL = 0");

        #$user = '.p-falonso.';
        oci_bind_by_name($stid, ":usuario", $user);
        #oci_bind_by_name($stid, ":passw", $passw);
        oci_execute($stid);

        #$results=array();
        #$numrows = oci_fetch_all($stid, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $admin = "";

        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
          $admin =  $row['USUARIO'];
        }

        #-------------------------------------AQUÍ ES PARA EL USER GENERAL-------------------------
        #Se ejecuta la consulta a la base de datos para validar al usuario y el password DEL ADMIN
        $stidU = oci_parse($conn, "SELECT *
                                  FROM SPN_MSJ_USUARIOS WHERE USUARIO = :usuario AND STATUS = 1 AND PERFIL = 1");

        #$user = '.p-falonso.';
        oci_bind_by_name($stidU, ":usuario", $user);
        #oci_bind_by_name($stid, ":passw", $passw);
        oci_execute($stidU);

        #$results=array();
        #$numrows = oci_fetch_all($stid, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $usuarioGral = "";
        
        while ($row = oci_fetch_array($stidU, OCI_ASSOC+OCI_RETURN_NULLS)) {
          $usuarioGral =  $row['USUARIO'];
        }

        if (($admin == "") && ($usuarioGral == "")) {
          $menscript = "<script>
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text:'Usuario o contraseña invalidos',
                  footer: '<p>Something went wrong!</p>',
                  showCloseButton: true,
                  showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                  }
                })
              </script>";
        }else{
          $_SESSION['usuario'] = $user;
          if ($user == $admin) {
            $_SESSION['isadmin'] = true;
            header("Location:pages/admin.php");
          }else{
            $_SESSION['isadmin'] = false;
            header("Location:pages/index.php");
          }
        }
       /*  echo '-->'.$n; */
  }else{
      $menscript = "<script>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text:'Usuario o contraseña invalidos',
        footer: '<p>Something went wrong!</p>',
        showCloseButton: true,
        showClass: {
          popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp'
        }
      })
    </script>";
  }
} 

?>