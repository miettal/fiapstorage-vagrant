<?php 
// UUID Generator
function uuid(){
  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff), mt_rand( 0, 0xffff ),
    mt_rand( 0, 0x0fff ) | 0x4000,
    mt_rand( 0, 0x3fff ) | 0x8000,
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff), mt_rand( 0, 0xffff ));
} 
  
// Prepare Keys
$keys = array();
$keys[0] = array("id"=>"http://www.gutp.jp/dummy/integer", "attrName"=>"time", "select"=>"maximum");
$keys[1] = array("id"=>"http://www.gutp.jp/dummy/real1", "attrName"=>"time", "select"=>"maximum"); 
  
// Generate Query, Header, and Transport for query
$query=array("type"=>"storage", "id"=>uuid(), "key"=>$keys);
$header=array("query"=>$query);
$transport=array("header"=>$header); 
$queryRQ=array("transport"=>$transport); 
  
// Call an IEEE1888 Storage server
// Specify the IP address of the SDK.
$server = new SoapClient("http://localhost/axis2/services/FIAPStorage?wsdl");
$queryRS = $server->query($queryRQ); 
  
// Parse IEEE1888 FETCH-Response 1 (Error Handling)
if($queryRS == NULL){
   echo "Error occured -- the result is empty.";
   exit;
}
if(!array_key_exists("transport",$queryRS)){
   echo "Error occured -- the transport in the result is empty.";
   exit;
}
$transport=$queryRS->transport;

if(!array_key_exists("header",$transport)){
   echo "Error occured -- the header in the transport is empty.";
   exit;
}
$header=$transport->header;

if(!array_key_exists("OK",$header)){
   if(!array_key_exists("error",$header)){
      echo "Error occured -- neither OK nor error presented in the header.";
      exit;
   }
   echo "Error:".$header->error->_;
   exit;
} 
  
// Parse IEEE1888 FETCH-Response 2 (Data Parsing, and Print out)
if(array_key_exists("body",$transport)){
  $body=$transport->body;
  if(array_key_exists("point",$body)){
    $points = $body->point;
    for($i=0;$i<count($points);$i++){
      if(count($points)==1){
        $point=$points;
      }else{
        $point=$points[$i];
            } 
      if(array_key_exists("value",$point)){
        $id=$point->id;
        $value=$point->value;

        $time=$value->time;
        $val=$value->_;
        echo $id."  ".$time."  ".$val."\n";
            }
       }
   }
}
?>

