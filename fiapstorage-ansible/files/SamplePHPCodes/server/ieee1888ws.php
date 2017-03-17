<?php

require_once("FIAPWS.wsdl.php");

function query($queryRQ){
  // TODO: ここにサーバコード(query)を実装する
  $errorRS=array("type"=>"SERVER_ERROR","_"=>"Not implemented.");
  $headerRS=array("error"=>$errorRS);
  $transportRS=array("header"=>$headerRS); 
  $queryRS=array("transport"=>$transportRS);
  return $queryRS;
}

function data($dataRQ){
  // TODO: ここにサーバコード(data)を実装する
  $errorRS=array("type"=>"SERVER_ERROR","_"=>"Not implemented.");
  $headerRS=array("error"=>$errorRS);
  $transportRS=array("header"=>$headerRS);
  $dataRS=array("transport"=>$transportRS);  
  return $dataRS;
}

// FIAPWS.wsdl を読み込んでSOAPサーバスタブを生成
$ss=new SoapServer('FIAPWS.wsdl');
$ss->AddFunction("query"); // queryメソッドをアタッチ
$ss->AddFunction("data");  // dataメソッドをアタッチ

if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
  // POST要求であれば、SOAP通信として処理する
  $ss->handle();
}else{
  // それ以外の場合、queryString(URLの?の後)に"wsdl"とあれば、このサーバのWSDLを返す
  if(isset($_SERVER["QUERY_STRING"]) && strcasecmp($_SERVER["QUERY_STRING"],"wsdl") == 0){
    header("Content-type: text/xml");
    $location="http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]; 
    printIEEE1888WSDL($location);
  }
}
?>
