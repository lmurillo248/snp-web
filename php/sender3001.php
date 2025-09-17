<?php

require_once '../php/config/conexion.php';
date_default_timezone_set('America/Mazatlan');
try {
    $getDida = $_POST['getDida'];
    $getDcr = $_POST['getDcr'];
    $idPort = $_POST['idPortDelete'];
    $fechaTimeStamp = $_POST['fechaTimeStamp'];
    #mensaje 3001
    $MessageID = 3001;
    #ido 102
    $ido = substr($idPort,0,3);
    $year = substr($idPort,3,3);
    #folio
    $folioID = $ido.$fechaTimeStamp.rand(0,9);
    #timestamp
    $TransTimestamp = $fechaTimeStamp;
    #number range
    $numberRange = "";
    #variable vacía
    $xmlmsg = "";
    $xml = "";
    $statusMsg = "";
    $response = false;
    $xmlmsgAux = "";

    $currentDate = date("Y-m-d");
    $portedDate = "";
    $daysBetweenDates = "";
    $portDateymd = "";
    $portDateFormat = "";
    $diffDate = "";

    // $stidX = oci_parse($conn, "SELECT PORTID,PORTEXECDATE,REQPORTEXECDATE FROM SPN_MSJ_1006  WHERE PORTID = :idPort");
    $stidX = oci_parse($conn, "SELECT ID, FECHA, SENDER, PORTID, PORTID_AUX, NOTA, MESSAGEID, ACUSE, XMLMSG
                                FROM SPN_MSJ 
                                WHERE PORTID = :idPort
                                AND MESSAGEID = (SELECT MAX(MESSAGEID) FROM SPN_MSJ WHERE PORTID = :idPort)
                                AND ACUSE LIKE 'Recibido%'");
    oci_bind_by_name($stidX, ":idPort", $idPort);
    oci_execute($stidX);
    while ($row = oci_fetch_array($stidX, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $statusMsg = $row['MESSAGEID'];
    }
    
    $countX = oci_fetch_all($stidX, $resultSet, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
    
    
    

    if (intval($statusMsg) < 1008 && intval($statusMsg) > 0) {
        if(intval($statusMsg) == 1007 || intval($statusMsg) == 1006) {
            $stidProg = oci_parse($conn, "SELECT PORTID,PORTEXECDATE,REQPORTEXECDATE FROM SPN_MSJ_1006  WHERE PORTID = :idPort");
    
            oci_bind_by_name($stidProg, ":idPort", $idPort);
            oci_execute($stidProg);
            while ($row = oci_fetch_array($stidProg, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $portedDate = $row['PORTEXECDATE'];
            }
            $portDateymd = substr($portedDate,0,8);
            // Crear un objeto DateTime a partir del formato específico
            $portDateObject = DateTime::createFromFormat('Ymd', $portDateymd);
            // Formatear la fecha al formato deseado YYYY-MM-DD
            $portDateFormat = $portDateObject->format('Y-m-d');
            $diffDate = abs(strtotime($currentDate) - strtotime($portDateFormat));
            $daysBetweenDates = floor($diffDate / (60 * 60 * 24));
            if($daysBetweenDates < 1){
                $resp = array('xmlmsg'=>'Error: porting schedule completed');
                echo json_encode($resp);
                exit();
            }
        }
        // && (intval($statusMsg) == 1007 && $daysBetweenDates < 1)
        
        // $stid = oci_parse($conn, "SELECT ID, FECHA, SENDER, PORTID, PORTID_AUX, NOTA, MESSAGEID, ACUSE, XMLMSG 
        //                                 FROM SPN_MSJ WHERE PORTID = :idPort AND MESSAGEID = '1005'");
        $stid = oci_parse($conn, "SELECT ID, FECHA, SENDER, PORTID, PORTID_AUX, NOTA, MESSAGEID, ACUSE, XMLMSG 
                                FROM SPN_MSJ
                                WHERE PORTID = :idPort
                                AND MESSAGEID = '1002' AND ACUSE LIKE 'Recibido%' AND PORTID IN (
                                    SELECT PORTID
                                    FROM SPN_MSJ
                                    WHERE PORTID = :idPort AND MESSAGEID NOT IN ('3002', '1091', '1092', '9999')
                                    AND ROWNUM < 2
                                )");
        oci_bind_by_name($stid, ":idPort", $idPort);
        oci_execute($stid);

        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $xmlmsg = $row['XMLMSG'];
        }

        if($xmlmsg == ""){
            $stid = oci_parse($conn, "SELECT ID, FECHA, SENDER, PORTID, PORTID_AUX, NOTA, MESSAGEID, ACUSE, XMLMSG 
                                FROM SPN_MSJ
                                WHERE PORTID = :idPort
                                AND MESSAGEID = '1001' AND ACUSE LIKE 'Recibido%' AND PORTID IN (
                                    SELECT PORTID
                                    FROM SPN_MSJ
                                    WHERE PORTID = :idPort AND MESSAGEID NOT IN ('3002', '1091', '1092', '9999')
                                    AND ROWNUM < 2
                                )");
            oci_bind_by_name($stid, ":idPort", $idPort);
            oci_execute($stid);

            while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $xmlmsg = $row['XMLMSG'];
            }

            $xmlmsgAux = simplexml_load_string($xmlmsg); #new SimpleXMLElement($xmlmsg);
            
            // 1001 -> PortRequest
            // 1002 -> PortRequestAck
            // 1005 -> PortToBeScheduled
            $portType = $xmlmsgAux->NPCMessage->PortRequest->PortType ;
            $SubscriberType = $xmlmsgAux->NPCMessage->PortRequest->SubscriberType ;
            $RecoveryFlagType = $xmlmsgAux->NPCMessage->PortRequest->RecoveryFlagType ;
            $DIDA = $getDida;
            $DCR = $getDcr;
            $TotalPhoneNums = $xmlmsgAux->NPCMessage->PortRequest->TotalPhoneNums ;

            foreach ($xmlmsgAux->NPCMessage->PortRequest->Numbers[0]->NumberRange as $item) {
                $numberRange .= "<NumberRange>\n<NumberFrom>".$item->NumberFrom."</NumberFrom>\n"."<NumberTo>".$item->NumberTo."</NumberTo>\n</NumberRange>\n";
            }

        } else {
            $xmlmsgAux = simplexml_load_string($xmlmsg); #new SimpleXMLElement($xmlmsg);
            
            // 1001 -> PortRequest
            // 1002 -> PortRequestAck
            // 1005 -> PortToBeScheduled
            $portType = $xmlmsgAux->NPCMessage->PortRequestAck->PortType ;
            $SubscriberType = $xmlmsgAux->NPCMessage->PortRequestAck->SubscriberType ;
            $RecoveryFlagType = $xmlmsgAux->NPCMessage->PortRequestAck->RecoveryFlagType ;
            $DIDA = $xmlmsgAux->NPCMessage->PortRequestAck->DIDA ;
            $DCR = $xmlmsgAux->NPCMessage->PortRequestAck->DCR ;
            $TotalPhoneNums = $xmlmsgAux->NPCMessage->PortRequestAck->TotalPhoneNums ;

            foreach ($xmlmsgAux->NPCMessage->PortRequestAck->Numbers[0]->NumberRange as $item) {
                $numberRange .= "<NumberRange>\n<NumberFrom>".$item->NumberFrom."</NumberFrom>\n"."<NumberTo>".$item->NumberTo."</NumberTo>\n</NumberRange>\n";
            }

        }

        #close connection
        oci_close($conn);

        if($DIDA == "" || $DCR == ""){
            $resp = array('xmlmsg'=>'Error: DIDA or DCR is empty');
            echo json_encode($resp);
            exit();
        } else {
            // $xml = $xmlmsgAux->asXML();
            // $xml = $xmlmsgAux;

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
        
            // $location = "http://172.21.141.123:7001/spn/spnService?WSDL";
            // $location = "http://172.28.109.42:7001/spn/spnService?WSDL";
            // $location = "http://172.28.109.43:7001/spn/spnService?WSDL";
            // $location = "http://172.28.108.181:7001/spn/spnService?WSDL";
            // $location = "http://172.28.108.182:7001/spn/spnService?WSDL";
            // $location = "http://172.21.151.77:7001/spn/spnService?WSDL";
            $location = "http://172.21.132.108:7001/spn/spnService?wsdl";
            // $location = "http://172.21.132.109:7001/spn/spnService?wsdl";
                
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
            // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);//NTLM para webservices con dominios de windows
            // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);//AUTH BASIC para este caso
            // curl_setopt($ch, CURLOPT_USERPWD, 'lucio:lucio'); //usuario:contraseña

            $response = curl_exec($ch);
        }
    }

    #echo htmlentities($xml);
    $resp = array('xml'=>$xml,'portid'=>$idPort,'response'=>$xmlmsgAux,'msg'=>'Success','xmlmsg'=>$xmlmsg, 'statusMsg'=>intval($statusMsg)); 
    echo json_encode($resp);
    #echo $arrayListado[1][0];
    #echo $xml;
    #$resp = array('countX'=>$countX);
    #echo json_encode($resp);
} catch (\Throwable $th) {

    #$resp = array('xml'=>$request,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'msg'=>'Execution Error');
    $resp = array('msg'=>'Execution Error');
    echo json_encode($resp);
}



?>