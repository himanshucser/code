<?php 
$env=file_get_contents(__DIR__."/.env");
foreach(@explode("\n",$env) as $line){
  preg_match("/([^#]+)\=(.*)/",$line,$matches);
  if(isset($matches[2])){
    define(trim($matches[1]), trim($matches[2])); 
  }
}