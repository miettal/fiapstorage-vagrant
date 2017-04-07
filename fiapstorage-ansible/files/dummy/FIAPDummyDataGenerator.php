<?php

define(IEEE1888WSDL,"http://localhost/axis2/services/FIAPStorage?wsdl");
define(POINTSET,"http://www.gutp.jp/dummy/");

$cur_time=0;
$pre_time=0;

while(true){
  $cur_time=intval(time()/60)*60;
  if($cur_time==$pre_time){
    sleep(1);
    continue;
  }
  $pre_time=$cur_time;

  $server = new SoapClient(IEEE1888WSDL);

  $points=array();

  // Boolean Type
  if(rand(0,1)==0){
    $v="false";
  }else{
    $v="true";
  }
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[0]=array("id"=>POINTSET."boolean","value"=>$value);
 
  // Integer Type
  $v=rand(-100000000,100000000);
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[1]=array("id"=>POINTSET."integer","value"=>$value);
  
  // Real Type 1
  $v=rand(-1000,1000)/10.0;
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[2]=array("id"=>POINTSET."real1","value"=>$value);
  
  // Real Type 2
  $v=rand(-1000,1000)*1e+8;
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[3]=array("id"=>POINTSET."real2","value"=>$value);
  
  // Real Type 3
  $v=rand(-1000,1000)*1e-9;
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[4]=array("id"=>POINTSET."real3","value"=>$value);
  
  // Enum Type 1
  if(rand(0,1)==0){
    $v="OFF";
  }else{
    $v="ON";
  }
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[5]=array("id"=>POINTSET."user_defined_enum1","value"=>$value);
  
  // Enum Type 2
  switch (rand(0,4)){
  case 0: $v="AUTO"; break;
  case 1: $v="COOL"; break;
  case 2: $v="PRE_COOL"; break;
  case 3: $v="FAN_ONLY"; break;
  case 4: $v="HEAT"; break;
  default: $v="NONE"; break;
  }
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[6]=array("id"=>POINTSET."user_defined_enum2","value"=>$value);
  
  // Enum Type 3
  switch (rand(0,2)){
  case 0: $v="AUTO"; break;
  case 1: $v="BYPASS"; break;
  case 2: $v="HEX"; break;
  default: $v="NONE"; break;
  }
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[7]=array("id"=>POINTSET."user_defined_enum3","value"=>$value);
  
  // Enum Type 4
  switch (rand(0,1)){
  case 0: $v="NORMAL"; break;
  case 1: $v="ALARM"; break;
  default: $v="NONE"; break;
  }
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[8]=array("id"=>POINTSET."user_defined_enum4","value"=>$value);
  
  // String Type
  $v=substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789<>=- ',5)),0,5);
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[9]=array("id"=>POINTSET."string","value"=>$value);
  
  // DateTime Type 1
  $v=strftime('%Y-%m-%dT%H:%M:%S',time());
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[10]=array("id"=>POINTSET."datetime1","value"=>$value);
  
  // DateTime Type 2
  $v=strftime('%Y-%m-%dT%H:%M:%S%z',time());
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[11]=array("id"=>POINTSET."datetime2","value"=>$value);
  
  // Date Type
  $v=strftime('%Y-%m-%d',time());
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[12]=array("id"=>POINTSET."date","value"=>$value);
  
  // Time Type
  $v=strftime('%H:%M:%S',time());
  $value=array("time"=>$cur_time,"_"=> $v );
  $points[13]=array("id"=>POINTSET."time","value"=>$value);
  

  // Compose the request
  $pointSet=array("id"=>POINTSET,"point"=>$points);
  $body=array("pointSet"=>$pointSet);
  $transport=array("body"=>$body);
  $dataRQ=array("transport"=>$transport);
  
  // Communicate with the server
  $dataRS=$server->data($dataRQ);
  
  // Check the result
  $transport=$dataRS->transport;
  if($transport->header->OK!=NULL){
    echo "Success: \n";
  }else if($transport->header->error!=NULL){
    echo "Error: ".$transport->header->error->_."\n";
  }
  
}
?>
