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


function saveData($pointSets, $points, $fp){
  if($pointSets!=null && count($pointSets)==1){
    saveData($pointSets->pointSet,$pointSets->point);
  }
  if($pointSets!=null && count($pointSets)>1){
    for($i=0;$i<count($pointSets);$i++){
      saveData($pointSets[$i]->pointSet,$pointSets[$i]->point);
    } 
  }
  
  for($i=0;$points!=null && $i<count($points);$i++){
  
    if(count($points)==1){
      $p=$points;
    }else{
      $p=$points[$i];
    }
    $values=$p->value;
    for($t=0;$values!=null && $t<count($values);$t++){
      if(count($values)==1){
        $line=array($p->id,$values->time,$values->_);
      }else{
        $line=array($p->id,$values[$t]->time,$values[$t]->_);
      }
      fputcsv($fp,$line);
    }
  }

}

function data($dataRQ){

  if(!array_key_exists("transport",$dataRQ)){
    $errorRS=array("type"=>"INVALID_REQUEST", "_"=>"transport is not specifed");
    $headerRS=array("error"=>$errorRS);
    $transportRS=array("header"=>$headerRS);
    $dataRS=array("transport"=>$transportRS);
    return $dataRS;
  }
  $transportRQ=$dataRQ->transport;
  if(!array_key_exists("body",$transportRQ)){
    $errorRS=array("type"=>"INVALID_REQUEST", "_"=>"body is not specifed");
    $headerRS=array("error"=>$errorRS);
    $transportRS=array("header"=>$headerRS);
    $dataRS=array("transport"=>$transportRS);
    return $dataRS;
  }
  $bodyRQ=$transportRQ->body;

  try{
    $fp=fopen("/home/gutp/test.csv","a");
    if($fp==null){
      $errorRS=array("type"=>"SERVER_ERROR", "_"=>"test.csv: File open error.");
      $headerRS=array("error"=>$errorRS);
      $transportRS=array("header"=>$headerRS);
      $dataRS=array("transport"=>$transportRS);
      return $dataRS;
    }

    saveData($bodyRQ->pointSet,$bodyRQ->point,$fp);
    fclose($fp);
  
    $headerRS=array("OK"=>array());
    $transportRS=array("header"=>$headerRS);
    $dataRS=array("transport"=>$transportRS);  
    return $dataRS;

  }catch(Exception $e){
    $errorRS=array("type"=>"SERVER_ERROR", "_"=>$e->getMessage());
    $headerRS=array("error"=>$errorRS);
    $transportRS=array("header"=>$headerRS);
    $dataRS=array("transport"=>$transportRS);
    return $dataRS;
  }
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
