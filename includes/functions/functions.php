<?php
function getAll($field,$table,$where=NULL,$and=NULL,$orderField = NUll,$ordering = 'DESC'){
  global $con;
  $getall = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");
  $getall->execute();
  $getallfrom = $getall->fetchAll();
  return $getallfrom;
}

/*get all category*/
// function getCats(){
//   global $con;
//   $getCats = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
//   $getCats->execute();
//   $cats = $getCats->fetchAll();
//   return $cats;
// }

// /*get all category*/
// function getItems($where,$value,$approve=NULL){
//   global $con;
//   $sql = $approve == NULL ? 'AND approve = 1' : '';
//   $getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC");
//   $getItems->execute(array($value));
//   $itmes = $getItems->fetchAll();
//   return $itmes;
// }


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
function CheckUserStatus($user){
  global $con;
  $stmtx = $con->prepare("SELECT UserName , RegStatus FROM users WHERE UserName=? AND RegStatus=0");
  $stmtx->execute(array($user));
  $rowCount = $stmtx->rowCount();
  return $rowCount;
}
 
?>