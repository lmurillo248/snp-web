<?xml version="1.0" encoding="UTF-8"?>
<NPCData>
    <MessageHeader>
        <TransTimestamp>20230417141725</TransTimestamp>//fecha del servidor
        <Sender>126</Sender>//IDO va a venir de la interfaz
        <NumOfMessages>1</NumOfMessages>//siempre es 1
    </MessageHeader>
    <NPCMessage MessageID="1001">//Fijo a la salida por primera vez
        <PortRequest>
            <PortType>8</PortType>// del xsd
            <SubscriberType>0</SubscriberType>
            <RecoveryFlagType>N</RecoveryFlagType>//es para recuperación de número, puede ser Y o N esto aplica Y cuando es número desconectado
            <PortID>126 2023 04 17 13 17 17 9149</PortID>//se genera en el sistema, los últimos 4 dígitos se generan de manera consecutiva
            <FolioID>126 23 04 17 13 09 9 8961</FolioID>//generado con el sender, fecha, hora y un consecutivo
            <Timestamp>20230417141725</Timestamp>//fecha hora del servidor
            <SubsReqTime>20230417130344</SubsReqTime>//fecha de timestamp
            <RIDA>126</RIDA>//mismo que el sender, es el receptor
            <RCR>126</RCR>//propietario del número(enviarlos igual para no detenerse)
            <TotalPhoneNums>1</TotalPhoneNums>//total de números a portar
            <Numbers>//números portados
                <NumberRange>//rango de from a to de teléfonos
                    <NumberFrom>4421000057</NumberFrom>//el from de la secuencia de teléfonos
                    <NumberTo>4421000057</NumberTo>//el to del fin de la secuencia de teléfonos
                </NumberRange>
            </Numbers>
            <Pin>8409</Pin>//el número solicitado al *52
            <Comments>|Cambio de Numero Telefonico|</Comments>
            <NumOfFiles>0</NumOfFiles>//el número de archivos adjuntados
            <AttachedFiles/>
        </PortRequest>
    </NPCMessage>
</NPCData>
