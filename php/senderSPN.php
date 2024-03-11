<?php
date_default_timezone_set('America/Mazatlan');
#$cliente = $_POST['cliente'];
$donador = $_POST['donador'];
$PortType = $_POST['PortType'];
$ido = $_POST['ido'];
$TransTimestamp = $_POST['TransTimestamp'];
$activoDesconectado = $_POST['activoDesconectado'];
$numTel = $_POST['numTel'];
$numTo = $_POST['numTo'];
$numNip = $_POST['numNip'];
$comentarios = $_POST['comentarios'];
$fechaTimeStamp = $_POST['fechaTimeStamp'];


#-------------------------------------------------------------

#bandera
$flagRadio = $_POST['flagRadio'];

if ($flagRadio == 1) {
	$suscriberType = 0;
}else{
	$suscriberType = 1;
}

$MessageID = 1001;
$numFiles = 0;
$attachFiles ="";
$nombreAttach = "";


#$portid = $ido.date("YmdHis").date("is");
$portid = $ido.$fechaTimeStamp.date("is");
$folioID = $ido.$fechaTimeStamp.rand(0,9);

$numberRange = "";
#este es para los números de tel
if ($numTel != "" && $numTo != "" && $numNip != "Attachments") {
	
	$numberRange ="<NumberRange>\n<NumberFrom>".$numTel."</NumberFrom>\n"."<NumberTo>".$numTo."</NumberTo>\n</NumberRange>\n";
	$TotalPhoneNums = 1;
	
}else{ 
	#agregar los attachments
	$documentServer = $_SERVER['DOCUMENT_ROOT']."/portabilidad/files/";
	$directorio = "E:\appspn\documentos/";
	
	#variables para los adjuntos
	$formFileSmIdentifica = $_FILES['formFileSmIdentifica']['tmp_name'];
	$formFileSmSolOriginal = $_FILES['formFileSmSolOriginal']['tmp_name'];
	$formFileSmJurada = $_FILES['formFileSmJurada']['tmp_name'];

	#variables de los nombres de los adjuntos--------------------------
	$selectDocumentOne = $_POST['selectDocumentOne'];
	$selectDocumentTwo = $_POST['selectDocumentTwo'];
	$selectDocumentThree = $_POST['selectDocumentThree'];
	$numFiles = 3;

	#move_uploaded_file($_FILES["formFileSmIdentifica"]["tmp_name"], $_FILES["formFileSmIdentifica"]["name"]);
	#$bin_identifica = file_get_contents($_FILES["formFileSmIdentifica"]["name"]);
	/* $fileName1 = $_FILES["formFileSmIdentifica"]["name"];
	$fileTarget = $directorio.$fileName1;
	$file1 = $_FILES["formFileSmIdentifica"]["tmp_name"];
	$documento = move_uploaded_file($file1,$fileTarget); */

	function fileSaver($fileIdenty, $fileNameX){
		$target_dir = "E:\appspn\documentos/";
		$file = $_FILES[$fileIdenty]['name'];
		$path = pathinfo($file);
		$filename = $fileNameX; #$path['filename'];
		$ext = $path['extension'];
		$temp_name = $_FILES[$fileIdenty]['tmp_name'];
		$path_filename_ext = $target_dir.$filename.".".$ext;
		move_uploaded_file($temp_name,$path_filename_ext);

		return $ext;
	}

	$formFileSmIdentificaExt = fileSaver("formFileSmIdentifica",$portid.$MessageID."-".$selectDocumentOne); 
	
/* 	move_uploaded_file($_FILES["formFileSmSolOriginal"]["tmp_name"], $_FILES["formFileSmSolOriginal"]["name"]);
	#$bin_original = file_get_contents($_FILES["formFileSmSolOriginal"]["name"]);*/

	$formFileSmSolOriginalExt = fileSaver("formFileSmSolOriginal",$portid.$MessageID."-".$selectDocumentTwo); 

/*	move_uploaded_file($_FILES["formFileSmJurada"]["tmp_name"], $_FILES["formFileSmJurada"]["name"]);
	#$bin_juarada = file_get_contents($_FILES["formFileSmJurada"]["name"]);*/

	$formFileSmJuradaExt = fileSaver("formFileSmJurada",$portid.$MessageID."-".$selectDocumentThree);
		
	#estos son los nombres de los archivos.
	$nombreAttach = "<NumOfFiles>$numFiles</NumOfFiles>\n" .
					"<AttachedFiles>\n" .            
						"<FileName>".$portid.$MessageID."-".$selectDocumentOne.".".$formFileSmIdentificaExt."</FileName>\n" . 
						"<FileName>".$portid.$MessageID."-".$selectDocumentTwo.".".$formFileSmSolOriginalExt."</FileName>\n" . 
						"<FileName>".$portid.$MessageID."-".$selectDocumentThree.".".$formFileSmJuradaExt."</FileName>\n" .
					"</AttachedFiles>\n";
			
	if($numTel != "" && $numTo != "" && $numNip == "Attachments"){
		
		$numberRange ="<NumberRange>\n<NumberFrom>".$numTel."</NumberFrom>\n"."<NumberTo>".$numTo."</NumberTo>\n</NumberRange>\n";

		$TotalPhoneNums = 1;

			
	}else if($numTel == "" && $numTo == "" && $numNip == "Attachments"){
		#aquí va el listado de excel moral gobierno
		$TotalPhoneNums = 0;
		move_uploaded_file($_FILES["listadoNum"]["tmp_name"], $_FILES["listadoNum"]["name"]);
		$listadoNumeros = file_get_contents($_FILES["listadoNum"]["name"]);
		$arrayListado = json_decode($listadoNumeros);

		foreach ($arrayListado as $fila) {
			#echo $fila[0];
			$TotalPhoneNums = $TotalPhoneNums + 1;
			$numberRange .="<NumberRange>\n<NumberFrom>".$fila[0]."</NumberFrom>\n"."<NumberTo>".$fila[0]."</NumberTo>\n</NumberRange>\n";
		}
	}
}


try {

	/*$xmlHeader = str_replace(">","&gt;",str_replace("'","&#039;",str_replace("<","&lt;","<?xml version='1.0' encoding='UTF-8'?>\n")));*/
	$xml = "<?xml version='1.0' encoding='UTF-8'?>\n" .
		"<NPCData>\n" .
			"<MessageHeader>\n" .
				"<TransTimestamp>$fechaTimeStamp</TransTimestamp>\n" . #fecha y hora del servidor
				"<Sender>$ido</Sender>\n" . #sender quien lo envía
				"<NumOfMessages>1</NumOfMessages>\n" .
			"</MessageHeader>\n" .
			"<NPCMessage MessageID='".trim($MessageID)."'>\n" .
				"<PortRequest>\n" .
					"<PortType>$PortType</PortType>\n" .
					"<SubscriberType>$suscriberType</SubscriberType>\n" .
					"<RecoveryFlagType>$activoDesconectado</RecoveryFlagType>\n" .
					"<PortID>$portid</PortID>\n" .
					"<FolioID>$folioID</FolioID>\n" .
					"<Timestamp>$fechaTimeStamp</Timestamp>\n" .
					"<SubsReqTime>$fechaTimeStamp</SubsReqTime>\n" .
					"<RIDA>$ido</RIDA>\n" .
					"<RCR>$ido</RCR>\n" .
					"<TotalPhoneNums>$TotalPhoneNums</TotalPhoneNums>\n" .
						"<Numbers>\n" .
							$numberRange .
						"</Numbers>\n" .
						($numNip == "Attachments" ? "": "<Pin>".$numNip."</Pin>\n") . #qué se pone aquí cuando no tiene nip
					($comentarios == "" ? "": "<Comments>".$comentarios."</Comments>\n") .
							$nombreAttach .
				"</PortRequest>\n" .
			"</NPCMessage>\n" .
		"</NPCData>\n";
		#$xmlEntitize = $xmlHeader.str_replace("'","&quot;",str_replace(">","&gt;",str_replace("<","&lt;",$xml)));
		
		$location = "http://172.28.108.181:7001/spn/spnService?WSDL";
		
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

		$response = curl_exec($ch);//aquí se guarda la respuesta cuando es exitoso, cuando no se procesa correctamente devuelve false.

	#echo htmlentities($xml);
	$resp = array('xml'=>$response,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'fechaTimeStamp'=> $fechaTimeStamp,'msg'=>'Success');
	echo json_encode($resp);
	#echo $arrayListado[1][0];
	#echo $xml;
} catch (\Throwable $th) {
	
	$resp = array('xml'=>$response,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'msg'=>'Execution Error');
	echo json_encode($resp);
}



			

