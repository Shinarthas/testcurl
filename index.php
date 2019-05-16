<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 15.05.2019
 * Time: 21:20
 */
// input data
$data = [ "firstName" => "Vasya", "lastName" => "Pupkin", "dateOfBirth" => "1984-07-31", "Salary" => "500", "creditScore" => "good" ];

function xmlRequest($data){
    $xml = new SimpleXMLElement('<xml/>');
    $userInfo=$xml->addChild('userInfo');
    $userInfo->addAttribute('userInfo','1.6');
    foreach ($data as $key => $value){
        $userInfo->addChild($key, $value);
    }
    $userInfo->creditScore[0]=getCreditScore($data["Salary"]);

    $dom = dom_import_simplexml($xml)->ownerDocument;
    $dom->formatOutput = true;
    makeRequest($dom->saveXML(),false);

}
function jsonRequest($data){
    $json = $data;
    $json["creditScore"]=getCreditScore($data["Salary"]);
    makeRequest(json_encode($json), true);
}
function getCreditScore($salary){
    if(floatval($salary)>700) return 'good';
    if(floatval($salary)<300) return 'bad';
    return '';
}
function makeRequest($body, $type=false){
    $ch = curl_init();
    $headers = array(
        'Accept: '.($type?'application/json':'text/xml'),
        'Content-Type:'.($type?'application/json':'text/xml')

    );
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/request.php');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);


    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $result = curl_exec($ch);
    parseResponse($result);
}
// тут следовалобы сделать парс в зависимости от хедера или от того кто вызвал функцию, но этот вариант в некоторых ситуациях бдует даже лучше
function parseResponse($resp){


    if(json_decode($resp,true))
        $data=json_decode($resp,true);
    else{
        $data=[];
        $xml = simplexml_load_string ($resp);
        $data['SubmitDataResult']=strval($xml->returnCodeDescription[0]);
//        foreach ($xml->children() as $children) {
//            $data[$children->getName()]=strval($children);
//        }
    }
    getStatus($data);
}
function getStatus($data){
    if(strtolower($data['SubmitDataResult'])=='success') echo 'Sold';
    if(strtolower($data['SubmitDataResult'])=='reject') echo 'Reject';
    if(strtolower($data['SubmitDataResult'])=='error') echo 'Error';
}



xmlRequest($data);
jsonRequest($data);
