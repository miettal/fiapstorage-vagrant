<?php

require_once("FIAPWS.wsdl.php");

function query($queryRQ){
   
    $headerRS=array();

    if(!array_key_exists("transport",$queryRQ)){
      $errorRS=array("type"=>"INVALID_REQUEST","_"=>"transport is not specified.");
      $headerRS=array("error"=>$errorRS);
      $transportRS=array("header"=>$headerRS); 
      $queryRS=array("transport"=>$transportRS);
      return $queryRS;
    }

    $transport=$queryRQ->transport;
    if(!array_key_exists("header",$transport)){
      $errorRS=array("type"=>"INVALID_REQUEST","_"=>"header is not specified.");
      $headerRS=array("error"=>$errorRS);
      $transportRS=array("header"=>$headerRS); 
      $queryRS=array("transport"=>$transportRS);
      return $queryRS;
    }

    $header=$transport->header;
    if(!array_key_exists("query",$header)){
      $errorRS=array("type"=>"INVALID_REQUEST","_"=>"query is not specified.");
      $headerRS=array("error"=>$errorRS);
      $transportRS=array("header"=>$headerRS); 
      $queryRS=array("transport"=>$transportRS);
      return $queryRS;
    }

    $query=$header->query;
    if(!array_key_exists("key",$query)){
      $errorRS=array("type"=>"QUERY_NOT_SUPPORTED","_"=>"no keys are not specified.");
      $headerRS=array("error"=>$errorRS);
      $transportRS=array("header"=>$headerRS); 
      $queryRS=array("transport"=>$transportRS);
      return $queryRS;
    }
    $headerRS["query"]=$query;
   
    $keys=$query->key;
    $points=array();
    $pointSets=array();
    $error=null;

    // check the keys
    if(count($keys)>4){
      $errorRS=array("type"=>"TOO_MANY_KEYS","_"=>"only 4 keys are allowed.");
      $headerRS=array("error"=>$errorRS);
      $transportRS=array("header"=>$headerRS); 
      $queryRS=array("transport"=>$transportRS);
      return $queryRS;
    }

    // check the attributes of each key
    for($k=0;$k<count($keys);$k++){
      $key=null;
      if(count($keys)==1){
        $key=$keys;
      }else{
        $key=$keys[$k];
      }
      if(array_key_exists("attrName",$key) && $key->attrName!="time"){
        $errorRS=array("type"=>"INVALID_REQUEST","_"=>" attrName='".$key->attrName."' is not supported.");
        $headerRS=array("error"=>$errorRS);
        $transportRS=array("header"=>$headerRS); 
        $queryRS=array("transport"=>$transportRS);
        return $queryRS; 
      }
      if(array_key_exists("eq",$key)){
        $errorRS=array("type"=>"QUERY_NOT_SUPPORTED","_"=>"'eq' is not supported.");
        $headerRS=array("error"=>$errorRS);
        $transportRS=array("header"=>$headerRS); 
        $queryRS=array("transport"=>$transportRS);
        return $queryRS;
      }
      if(array_key_exists("neq",$key)){
        $errorRS=array("type"=>"QUERY_NOT_SUPPORTED","_"=>"'neq' is not supported.");
        $headerRS=array("error"=>$errorRS);
        $transportRS=array("header"=>$headerRS); 
        $queryRS=array("transport"=>$transportRS);
        return $queryRS;
      }
      if(array_key_exists("lt",$key)){
        $errorRS=array("type"=>"QUERY_NOT_SUPPORTED","_"=>"'lt' is not supported.");
        $headerRS=array("error"=>$errorRS);
        $transportRS=array("header"=>$headerRS); 
        $queryRS=array("transport"=>$transportRS);
        return $queryRS;
      }
      if(array_key_exists("gt",$key)){
        $errorRS=array("type"=>"QUERY_NOT_SUPPORTED","_"=>"'gt' is not supported.");
        $headerRS=array("error"=>$errorRS);
        $transportRS=array("header"=>$headerRS); 
        $queryRS=array("transport"=>$transportRS);
        return $queryRS;
      }
      if(array_key_exists("lteq",$key)){
        $errorRS=array("type"=>"QUERY_NOT_SUPPORTED","_"=>"'lteq' is not supported.");
        $headerRS=array("error"=>$errorRS);
        $transportRS=array("header"=>$headerRS); 
        $queryRS=array("transport"=>$transportRS);
        return $queryRS;
      }
      if(array_key_exists("gteq",$key)){
        $errorRS=array("type"=>"QUERY_NOT_SUPPORTED","_"=>"'gteq' is not supported.");
        $headerRS=array("error"=>$errorRS);
        $transportRS=array("header"=>$headerRS); 
        $queryRS=array("transport"=>$transportRS);
        return $queryRS;
      }
      if(array_key_exists("trap",$key)){
        $errorRS=array("type"=>"INVALID_REQUEST","_"=>"'trap' is not supported.");
        $headerRS=array("error"=>$errorRS);
        $transportRS=array("header"=>$headerRS); 
        $queryRS=array("transport"=>$transportRS);
        return $queryRS;
      }
      if( $key->id=="http://gutp.jp/server_test/integer0"
       || $key->id=="http://gutp.jp/server_test/integer1"
       || $key->id=="http://gutp.jp/server_test/integer2"
       || $key->id=="http://gutp.jp/server_test/integer3"){
        $points[$k]=array("id"=>$key->id,"value"=>array("time"=>date("c"),"_"=>rand()));
      }else{
        $errorRS=array("type"=>"POINT_NOT_FOUND","_"=>$key->id." is not managed in this server.");
        $headerRS=array("error"=>$errorRS);
        $transportRS=array("header"=>$headerRS); 
        $queryRS=array("transport"=>$transportRS);
        return $queryRS;
      }
    }

    $headerRS["OK"]=array();
    $bodyRS=array();
    if(count($points)>0){
      $bodyRS["point"]=$points;
    }
    $transportRS=array("header"=>$headerRS,"body"=>$bodyRS);
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
