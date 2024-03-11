<?php

require_once '../php/config/conexion.php';
date_default_timezone_set('America/Mazatlan');
try {

    $numRevFrom = $_POST['numRevFrom'];
    $numRevTo = $_POST['numRevTo'];
    $selectRevertType = $_POST['selectRevertType'];
    $txtdateRevert = $_POST['txtdateRevert'];
    $selectDocumentRevert = $_POST['selectDocumentRevert'];
    $formFileSmRevert = $_POST['formFileSmRevert'];
    #$formFileSmRevert = $_FILES['formFileSmRevert']['tmp_name'];
    #mensaje 4001
    $MessageID = 4001;
    #ido 102
    #$ido = substr($idPort,0,3);
    #folio
    #$folioID = $ido.date("ymdHis").date("i").rand(0,9);
    #timestamp
    $TransTimestamp = date("YmdHis");
    #number range
    $numberRange = "";
    #variable vacía
    $xmlmsg = "";
    $xml = "";
    $response = false;
    $NumOfFiles = 0;

    $PortID = "";
    $portType = "";
    $SubscriberType = "";
    $RecoveryFlagType = "";
    $DIDA = "";
    $DCR = "";
    $RIDA = "";
    $RCR = "";
    $TotalPhoneNums = "";

    $stidX = oci_parse($conn, "SELECT ID, FECHA, SENDER, PORTID, PORTID_AUX, NOTA, MESSAGEID, ACUSE, XMLMSG 
                                FROM SPN_MSJ WHERE XMLMSG LIKE '%' || :RevFrom || '%' AND MESSAGEID = '1006'");
    oci_bind_by_name($stidX, ":RevFrom", $numRevFrom);
    oci_execute($stidX);

    #$countX = oci_fetch_all($stidX, $resultSet, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

    while ($rowX = oci_fetch_array($stidX, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $xmlmsg = $rowX['XMLMSG'];
    }

    #close connection
    oci_close($conn);

    if ($formFileSmRevert !== "") {
        $NumOfFiles = 1;
    }
    #move_uploaded_file($_FILES["formFileSmRevert"]["tmp_name"], $_FILES["formFileSmRevert"]["name"]);
	#$bin_identifica = file_get_contents($_FILES["formFileSmRevert"]["name"]);

    

    if ($xmlmsg !== "") {
        
        $xmlmsgAux = simplexml_load_string($xmlmsg); #new SimpleXMLElement($xmlmsg);
        
        $PortID = $xmlmsgAux->NPCMessage->PortScheduled[0]->PortID ;
        $portType = $xmlmsgAux->NPCMessage->PortScheduled[0]->PortType ;
        $SubscriberType = $xmlmsgAux->NPCMessage->PortScheduled[0]->SubscriberType ;
        $RecoveryFlagType = $xmlmsgAux->NPCMessage->PortScheduled[0]->RecoveryFlagType ;
        $DIDA = $xmlmsgAux->NPCMessage->PortScheduled[0]->DIDA ;
        $DCR = $xmlmsgAux->NPCMessage->PortScheduled[0]->DCR ;
        $RIDA = $xmlmsgAux->NPCMessage->PortScheduled[0]->RIDA ;
        $RCR = $xmlmsgAux->NPCMessage->PortScheduled[0]->RCR ;
        $TotalPhoneNums = $xmlmsgAux->NPCMessage->PortScheduled[0]->TotalPhoneNums ;

        foreach ($xmlmsgAux->NPCMessage->PortScheduled[0]->Numbers[0]->NumberRange as $item) {
            $numberRange .= "<NumberRange><NumberFrom>".$item->NumberFrom."</NumberFrom>"."<NumberTo>".$item->NumberTo."</NumberTo></NumberRange>";
            
        }

        $xml = "<NPCData>" .
                "<MessageHeader>" .
                    "<TransTimestamp>".$TransTimestamp."</TransTimestamp>" .
                    "<Sender>".$RIDA."</Sender>" .
                    "<NumOfMessages>1</NumOfMessages>" .
                "</MessageHeader>" .
                "<NPCMessage MessageID='".trim($MessageID)."'>" .
                    "<PortRevRequest>" .
                        "<PortType>".$portType."</PortType>" .
                        "<SubscriberType>".$SubscriberType."</SubscriberType>" .
                        "<RecoveryFlagType>".$RecoveryFlagType."</RecoveryFlagType>" .
                        "<PortID>".$portIdRev."</PortID>" .
                        "<Timestamp>20210619110000</Timestamp>" .
                        "<DIDA>".$RIDA."</DIDA>" .
                        "<DCR>".$RCR."</DCR>" .
                        "<RIDA>".$DIDA."</RIDA>" .
                        "<RCR>".$DCR."</RCR>" .
                        "<TotalPhoneNums>".$TotalPhoneNums."</TotalPhoneNums>" .
                        "<Numbers>" .
                            $numberRange .
                        "</Numbers>" .
                        "<PortExecDate>".date("Ymd"."020000", strtotime($txtdateRevert))."</PortExecDate>" .
                        "<OriginOfReq>".$selectRevertType."</OriginOfReq>" .
                        "<Comments>|Reversión|</Comments>" .
                        #"<NumOfFiles>".$NumOfFiles."</NumOfFiles>" .
                        #($formFileSmRevert == "" ? "": "<AttachedFiles>".$formFileSmRevert."</AttachedFiles>") .
                    "</PortRevRequest>" .
                "</NPCMessage>" .
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
    
    #echo htmlentities($xml);
    $resp = array('xml'=>$xml,'response'=>$response,'PortID'=>$PortID,'portType'=>$portType,'SubscriberType'=>$SubscriberType,'msg'=>'Success',
                    'RIDA'=>$RIDA,'xmlmsg'=>$xmlmsg,'numberRange'=>$numberRange,'formFileSmRevert'=>$formFileSmRevert); 
    echo json_encode($resp);
    #echo $arrayListado[1][0];
    #echo $xmlmsg;
    #$resp = array('countX'=>$countX);
    #echo json_encode($resp);
    } catch (\Throwable $th) {

    #$resp = array('xml'=>$request,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'msg'=>'Execution Error');
    $resp = array('msg'=>'Execution Error');
    echo json_encode($resp);
}



?>