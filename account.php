<?php
require_once "inc/db.php";
$headers = apache_request_headers();
if(!isset($headers['SOAPAction'])){
    echo "So long, gay computer!";
    exit;
}
header("Content-Type: text/xml;charset=utf-8");
$xml = simplexml_load_string(file_get_contents("php://input"));
$nintendo1 = $xml->children('SOAP-ENV', true)->Body->children('ias', true)->GetChallenge;
$nintendo2 = $xml->children('SOAP-ENV', true)->Body->children('ias', true)->GetRegistrationInfo;
//holy sh-
$nintendo3 = $xml->children('SOAP-ENV', true)->Body->children('ias', true)->SetIVSData;
if(!empty($nintendo1)){
    $q = $db->prepare("SELECT * FROM `act` WHERE `device_token`=:device_token");
    $q->execute([
        "device_token" => $nintendo1->DeviceToken
    ]);
    $r = $q->fetch();
    echo '<?xml version="1.0" encoding="utf-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><soapenv:Body><GetChallengeResponse xmlns="urn:ias.wsapi.broadon.com"><Version>2.0</Version><DeviceId>'.$nintendo1->DeviceId.'</DeviceId><MessageId>'.$nintendo1->MessageId.'</MessageId><TimeStamp>'.time().'</TimeStamp><ErrorCode>0</ErrorCode><ServiceStandbyMode>false</ServiceStandbyMode><Challenge>1922587247</Challenge></GetChallengeResponse></soapenv:Body></soapenv:Envelope>';
}else if(!empty($nintendo2)){
    $q = $db->prepare("SELECT * FROM `act` WHERE `id`=:id");
    $q->execute([
        "id" => $nintendo2->AccountId
    ]);
    $r = $q->fetch();
    echo '<?xml version="1.0" encoding="utf-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><soapenv:Body><GetRegistrationInfoResponse xmlns="urn:ias.wsapi.broadon.com"><Version>2.0</Version><DeviceId>'.$r['device_id'].'</DeviceId><MessageId>'.$nintendo2->MessageId.'</MessageId><TimeStamp>'.time().'</TimeStamp><ErrorCode>0</ErrorCode><ServiceStandbyMode>false</ServiceStandbyMode><AccountId>'.$r['id'].'</AccountId><DeviceToken>'.$r['device_token'].'</DeviceToken><DeviceTokenExpired>false</DeviceTokenExpired><Country>FR</Country><ExtAccountId></ExtAccountId><DeviceStatus>R</DeviceStatus><Currency>EUR</Currency></GetRegistrationInfoResponse></soapenv:Body></soapenv:Envelope>';
}else if(!empty($nintendo3)){
    $q = $db->prepare("SELECT * FROM `act` WHERE `id`=:id");
    $q->execute([
        "id" => $nintendo3->AccountId
    ]);
    $r = $q->fetch();
    echo '<?xml version="1.0" encoding="utf-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><soapenv:Body><SetIVSDataResponse xmlns="urn:ias.wsapi.broadon.com"><Version>2.0</Version><DeviceId>'.$r['device_id'].'</DeviceId><MessageId>'.$nintendo3->MessageId.'</MessageId><TimeStamp>'.time().'</TimeStamp><ErrorCode>0</ErrorCode><ServiceStandbyMode>false</ServiceStandbyMode><AccountId>'.$r['id'].'</AccountId></SetIVSDataResponse></soapenv:Body></soapenv:Envelope>';
}