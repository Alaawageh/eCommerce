<?php

function getAllFrom($field,$table,$where=NULL,$and=NULL,$orderField,$ordering = 'DESC'){
  global $con;
  $getcat = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");
  $getcat->execute();
  $getAllFrom = $getcat->fetchAll();
  return $getAllFrom;
}
//Function Title
 function getTitle(){
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    }else{
        echo 'Default';
    }
 }
 //redirct function
 function redirctHome($theMsg,$url=null,$second = 3){
    
    if($url === null){
        $url = 'index.php';
    }else{
       if(isset( $_SERVER['HTTP_REFERER']) &&  $_SERVER['HTTP_REFERER'] !== ''){
         $url =  $_SERVER['HTTP_REFERER'];
       }else{
        $url = 'index.php';
       }
    }
    echo $theMsg;
    echo '<div class="alert alert-info">will be redirect to homepage in '.$second.' secondes </div>';
    header("refresh:$second;url=$url");
    exit();
 }
 //check Item function
  function checkItem($select,$from,$value){
   global $con;
   $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
   $statement->execute(array($value));
   $count = $statement->rowCount();
   return $count; 
 }
 // function to count number of items
 //$item ==> The item to count
 //$table ==> the table to choose from
 function countItems($item,$table){
  global $con;
  $stmt2 = $con->prepare("SELECT count($item) FROM $table");
  $stmt2->execute();
  return $stmt2->fetchColumn();
 
 }
 //function get latest user
function getLatest($select,$table,$order,$limit=2){
  global $con;
  $stmt3 = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
  $stmt3->execute();
  return $stmt3->fetchAll();
}
?>