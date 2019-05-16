<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 15.05.2019
 * Time: 21:55
 */
$headers=getallheaders (  );
$data = file_get_contents('php://input');

if($headers['Content-Type']=='application/json')
    jsonResp($data);
elseif ($headers['Content-Type']=='text/xml')
    xmlResp($data);
else
    die();

// я бы сделал какую-то логику, но ее нет в ТЗ
function jsonResp($data){
    $json=json_decode($data,true);

    // begin best parse ever
    // processing
    // end best parse ever

    $resps=[
        '{"SubmitDataResult":"success"}',
        '{"SubmitDataResult":"reject"}',
        '{"SubmitDataResult":"error", "SubmitDataErrorMessage":""}'
    ];

    echo $resps[rand(0,count($resps)-1)];
}
function xmlResp($data){
    $xml = simplexml_load_string ($data);

    // begin best parse ever
    // processing
    // end best parse ever

    $resps=[
        '<?xml version="1.0" encoding="UTF-8"?> <userInfo version="1.6">   <returnCode>1</returnCode>   <returnCodeDescription>SUCCESS</returnCodeDescription>   <transactionId>AC158457A86E711D0000016AB036886A03E7</transactionId> </userInfo> ',
        '<?xml version="1.0" encoding="UTF-8"?> <userInfo version="1.6">   <returnCode>0</returnCode>   <returnCodeDescription>REJECT</returnCodeDescription> </userInfo> ',
        '<?xml version="1.0" encoding="UTF-8"?> <userInfo version="1.6">   <returnCode>0</returnCode>   <returnCodeDescription>ERROR</returnCodeDescription>   <returnError>Lead not Found</returnError> </userInfo>'
        ];
    echo $resps[rand(0,count($resps)-1)];
}
die();