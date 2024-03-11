<?php

require_once '../php/config/conexion.php';
date_default_timezone_set('America/Mazatlan');
$fromDelete = $_POST['fromDelete'];
$toDelete = $_POST['toDelete'];
$exampleFormControlTextarea1 = $_POST['exampleFormControlTextarea1'];
$fechaTimeStamp = $_POST['fechaTimeStamp'];

#bandera
$flagRadio = $_POST['flagRadio'];
#mensaje 5001
$MessageID = 5001;
#ido 
$ido = 102;
#portid
$portid = $ido.$fechaTimeStamp.date("is");
$folioID = $ido.$fechaTimeStamp.rand(0,9);
#timestamp
$TransTimestamp = $fechaTimeStamp;
#sender
$sender = 128;

$numberRange = "";

#este es para los números de tel del input text
if ($fromDelete != "" && $toDelete != "") {
	
	$numberRange ="<NumberRange>\n<NumberFrom>".$fromDelete."</NumberFrom>\n"."<NumberTo>".$toDelete."</NumberTo>\n</NumberRange>\n";
	$TotalPhoneNums = 1;
	
}else{
    foreach ($arrayListado as $fila) {
        #echo $fila[0];
        $TotalPhoneNums = $TotalPhoneNums + 1;
        $numberRange .="<NumberRange>\n<NumberFrom>".$fila[0]."</NumberFrom>\n"."<NumberTo>".$fila[0]."</NumberTo>\n</NumberRange>\n";
    }
}


try {
    $xml = "<NPCData>\n" . 
        "<MessageHeader>\n" .
            "<TransTimestamp>".$TransTimestamp."</TransTimestamp>\n" .
            "<Sender>".$sender."</Sender>\n" .
            "<NumOfMessages>1</NumOfMessages>\n" .
        "</MessageHeader>\n" .
        "<NPCMessage MessageID='".trim($MessageID)."'>\n" .
            "<NumberDelRequest>\n" .
                "<PortID>".$portid."</PortID>\n" .
                "<Timestamp>".$TransTimestamp."</Timestamp>\n" .
                "<IDA>".$ido."</IDA>\n" .
                "<TotalPhoneNums>".$TotalPhoneNums."</TotalPhoneNums>\n" .
                    "<Numbers>\n" .
                        $numberRange .
                    "</Numbers>\n" .
            "</NumberDelRequest>\n" .
        "</NPCMessage>\n" .
    "</NPCData>";

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

    #echo htmlentities($xml);
    $resp = array('xml'=>$request,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'response'=>$response,'TransTimestamp'=>$TransTimestamp,'msg'=>'Success');
    echo json_encode($resp);
    #echo $arrayListado[1][0];
    #echo $xml;
    } catch (\Throwable $th) {

    $resp = array('xml'=>$request,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'msg'=>'Execution Error');
    echo json_encode($resp);
}



?>