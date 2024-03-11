<?php

require_once '../php/config/conexion.php';
date_default_timezone_set('America/Mazatlan');
try {

    $txtdate = $_POST['txtdate'];
    $idPort = $_POST['idPort'];
    #mensaje 1006
    $MessageID = 1006;
    #ido 
    $ido = substr($idPort,0,3);
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
                        "<PortScheduled>\n" .
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
                            "<PortExecDate>".date("Ymd"."020000", strtotime($txtdate))."</PortExecDate>\n" .
                            #"<ReqPortExecDate>".date("YmdHis", strtotime($txtdate))."</ReqPortExecDate>\n" .
                        "</PortScheduled>\n" .
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
    $resp = array('xml'=>$xml,'portid'=>$idPort,'txtdate'=>$txtdate,'response'=>$response,'countX'=>$countX,'ido'=>$ido,'msg'=>'Success','xmlmsg'=>$xmlmsg); 
    echo json_encode($resp);
    #echo $arrayListado[1][0];
    #echo $xml;
    } catch (\Throwable $th) {

    #$resp = array('xml'=>$request,'portid'=>$portid,'folioID'=>$folioID, 'TotalPhoneNums'=>$TotalPhoneNums,'msg'=>'Execution Error');
    $resp = array('msg'=>'Execution Error');
    echo json_encode($resp);
}



?>