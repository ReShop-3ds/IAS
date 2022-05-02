<?php
require_once "inc/db.php";
$headers = apache_request_headers();
if(!isset($headers['SOAPAction'])){
    echo "So long, gay computer!";
    exit;
}
header("Content-Type: text/xml;charset=utf-8");
$xml = simplexml_load_string(file_get_contents("php://input"));
//holy sh-
$nintendo1 = $xml->children('SOAP-ENV', true)->Body->children('ias', true)->SetIVSData;
if(!empty($nintendo1)){
    $q = $db->prepare("SELECT * FROM `act` WHERE `device_token`=:device_token");
    $q->execute([
        "device_token" => $nintendo1->DeviceToken
    ]);
    $r = $q->fetch();
    echo '<?xml version="1.0" encoding="utf-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><soapenv:Body><SetIVSDataResponse xmlns="urn:ias.wsapi.broadon.com"><Version>2.0</Version><DeviceId>'.$r['device_id'].'</DeviceId><MessageId>'.$nintendo1->MessageId.'</MessageId><TimeStamp>'.time().'</TimeStamp><ErrorCode>0</ErrorCode><ServiceStandbyMode>false</ServiceStandbyMode><AccountId>'.$r['id'].'</AccountId></SetIVSDataResponse></soapenv:Body></soapenv:Envelope>';
}