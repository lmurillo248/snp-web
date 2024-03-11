<?php 

include 'php/config/conexion.php';
date_default_timezone_set('America/Mazatlan');
$menscript = "";
#Se valida que el botón de inicio de sesión esté posteado.
session_start();
if(isset($_POST['signinBtn'])){

  if (!empty($usuario) && !empty($passw)) {

    $usuario = $_POST['usuario'];
    $passw = $_POST['pass'];

    //config.php
    $config['version'] = '1.0';
    $config['urlLdap'] = 'ldap://izzitelecom.net:3268';
    $config['baseLdap'] = 'DC=izzitelecom,DC=net';
    $config['usuario'] = $usuario;
    $config['pass'] = $passw;
    $config['baseSearch'] = 'ou=users,ou=guests,dc=izzitelecom,dc=net';
    $config['columnaLdap'] = 'mail';


    // conexión al servidor LDAP
    $ldapconn = ldap_connect($config['urlLdap']) or die("Could not connect to LDAP server.");

    if ($ldapconn){
        // realizando la autenticación
      $ldapbind = ldap_bind($ldapconn, $config['usuario'], $config['pass']) or die("Error trying to bind: " . ldap_error($ldapconn));

      // verificación del enlace
      if($ldapbind){
         #Se ejecuta la consulta a la base de datos para validar al usuario y el password DEL ADMIN
         $stid = oci_parse($conn, "SELECT *
         FROM SPN_MSJ_USUARIOS WHERE USUARIO = :usuario AND STATUS = 1 AND PERFIL = 0");


        oci_bind_by_name($stid, ":usuario", $usuario);
        oci_execute($stid);

        $admin = "";

        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
          $admin =  $row['USUARIO'];
        }

        #-------------------------------------AQUÍ ES PARA EL USER GENERAL-------------------------
        #Se ejecuta la consulta a la base de datos para validar al usuario y el password DEL ADMIN
        $stidU = oci_parse($conn, "SELECT *
                FROM SPN_MSJ_USUARIOS WHERE USUARIO = :usuario AND STATUS = 1 AND PERFIL = 1");

        oci_bind_by_name($stidU, ":usuario", $usuario);
        oci_execute($stidU);

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
          $_SESSION['usuario'] = $usuario;
          if ($usuario == $admin) {
            $_SESSION['isadmin'] = true;
            header("Location:pages/admin.php");
          }else{
            $_SESSION['isadmin'] = false;
            header("Location:pages/index.php");
          }
        }    
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
        ldap_close($ldapconn);
    }else{
      $menscript = "<script>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text:'Error de conexión al LDAP',
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

<?php

require_once '../php/config/conexion.php';

try {

    $idPort = $_POST['idPortDelete'];
    #mensaje 3001
    $MessageID = 3001;
    #ido 102
    $ido = 126;
    #folio
    $folioID = $ido.date("ymdHis").date("i").rand(0,9);
    #timestamp
    $TransTimestamp = date("YmdHis");
    #number range
    $numberRange = "";
    #variable vacía
    $xmlmsg = "";

    $stidX = oci_parse($conn, "SELECT PORTID,PORTEXECDATE,REQPORTEXECDATE
                                FROM SPN_MSJ_1006  WHERE PORTID = :idPort");
    oci_bind_by_name($stidX, ":idPort", $idPort);
    oci_execute($stidX);

    $countX = oci_fetch_all($stidX, $resultSet, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

    if ($countX === 0) {
        
        $stid = oci_parse($conn, "SELECT ID, FECHA, SENDER, PORTID, PORTID_AUX, NOTA, MESSAGEID, ACUSE, XMLMSG 
                                        FROM SPN_MSJ WHERE PORTID = :idPort AND MESSAGEID = '1005'");
                                        
        oci_bind_by_name($stid, ":idPort", $idPort);
        oci_execute($stid);

        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $xmlmsg = $row['XMLMSG'];
        }

        if ($xmlmsg !== "") {
            
            $xmlmsgAux = simplexml_load_string($xmlmsg); #new SimpleXMLElement($xmlmsg);
        
            /* $objJsonDocument = json_encode($xmlmsgAux);
            $arrOutput = json_decode($objJsonDocument, TRUE);

            $portType = $arrOutput['MessageHeader']['Sender']; */

            $portType = $xmlmsgAux->NPCMessage->PortToBeScheduled[0]->PortType ;
            $SubscriberType = $xmlmsgAux->NPCMessage->PortToBeScheduled[0]->SubscriberType ;
            $RecoveryFlagType = $xmlmsgAux->NPCMessage->PortToBeScheduled[0]->RecoveryFlagType ;
            $DIDA = $xmlmsgAux->NPCMessage->PortToBeScheduled[0]->DIDA ;
            $DCR = $xmlmsgAux->NPCMessage->PortToBeScheduled[0]->DCR ;
            $TotalPhoneNums = $xmlmsgAux->NPCMessage->PortToBeScheduled[0]->TotalPhoneNums ;

            foreach ($xmlmsgAux->NPCMessage->PortToBeScheduled[0]->Numbers[0]->NumberRange as $item) {
                $numberRange .= "<NumberRange>\n<NumberFrom>".$item->NumberFrom."</NumberFrom>\n"."<NumberTo>".$item->NumberTo."</NumberTo>\n</NumberRange>\n";
                
            }


            #close connection
            oci_close($conn);
    
            $xml = "<NPCData>\n" .
                    "<MessageHeader>\n" .
                        "<TransTimestamp>".$TransTimestamp."</TransTimestamp>\n" .
                        "<Sender>".$ido."</Sender>\n" .
                        "<NumOfMessages>1</NumOfMessages>\n" .
                    "</MessageHeader>\n" .
                    "<NPCMessage MessageID='".trim($MessageID)."'>\n" .
                        "<PortCancellationRequest>\n" .
                            "<PortType>".$portType."</PortType>\n" .
                            "<SubscriberType>".$SubscriberType."</SubscriberType>\n" .
                            "<RecoveryFlagType>".$RecoveryFlagType."</RecoveryFlagType>\n" .
                            "<PortID>".$idPort."</PortID>\n" .
                            "<Timestamp>".$TransTimestamp."</Timestamp>\n" .
                            "<DIDA>".$DIDA."</DIDA>\n" .
                            "<DCR>".$DCR."</DCR>\n" .
                            "<RIDA>".$ido."</RIDA>\n" .
                            "<RCR>".$ido."</RCR>\n" .
                            "<TotalPhoneNums>".$TotalPhoneNums."</TotalPhoneNums>\n" .
                                "<Numbers>\n" .
                                    $numberRange .
                                "</Numbers>\n" .
                            #"<PortExecDate>".date("Ymd"."020000", strtotime($txtdate))."</PortExecDate>\n" .
                            #"<ReqPortExecDate>".date("YmdHis", strtotime($txtdate))."</ReqPortExecDate>\n" .
                        "</PortCancellationRequest>\n" .
                    "</NPCMessage>\n" .
                "</NPCData>
            ";
        
            $location = "http://172.21.141.123:7001/spn/spnService?WSDL";
                
            $request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:spn="https://bmartinezc:7001/spnService">
                            <soapenv:Header/>
                                <soapenv:Body>
                                    <spn:Mensaje>' . 
                                        htmlentities($xml) . 
                                    '</spn:Mensaje>
                                </soapenv:Body>
                            </soapenv:Envelope>';

            #$action = "guardarOrdenDeCompra";
            $headers = [
                'Method: POST',
                'Connection: Keep-Alive',
                'User-Agent: PHP-SOAP-CURL',
                'Content-Type: text/xml; charset=utf-8',
                #'SOAPAction: "guardarOrdenDeCompra"',
            ];

            $ch = curl_init($location);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            //tipo de autorización
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);//NTLM para webservices con dominios de windows
            //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);//AUTH BASIC para este caso
            //curl_setopt($ch, CURLOPT_USERPWD, 'lucio:lucio'); //usuario:contraseña

            $response = curl_exec($ch); 
        }else{
            $xmlmsg = "Error PortID";
        }
    }

    #echo htmlentities($xml);
    $resp = array('xml'=>$xml,'portid'=>$idPort,'response'=>$response,'countX'=>$countX,'msg'=>'Success','xmlmsg'=>$xmlmsg); 
    echo json_encode($resp);
    #echo $arrayListado[1][0];
    #echo $xml;
    } catch (\Throwable $th) {

    #$resp = array('xml'=>$request,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'msg'=>'Execution Error');
    $resp = array('msg'=>'Execution Error');
    echo json_encode($resp);
}



?>