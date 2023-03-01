<?php
//Add | Edit | Delete members
ob_start();
session_start();
$pageTitle = 'Members';
if(isset($_SESSION['Username'])){
    include 'init.php';

   $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

   if($do == 'Manage'){ // Manage page 
      $query = '';
      if(isset($_GET['page']) && $_GET['page'] == 'pending'){
         $query = "AND RegStatus = 0";

      }

   $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
   $stmt->execute();
   $rows = $stmt->fetchAll();
   if(!empty($rows)){

   ?>
   <h2 class="text-center">Manage Member</h2>
      <div class="container">
       <div class="table-responsive">
        <table class="table text-center table-bordered">
          <tr class="head">
             <td>#ID</td>
             <td>username</td>
             <td>email</td>
             <td>fullname</td>
             <td>Registered Date</td>
             <td>control</td>
          </tr>
             <?php foreach($rows as $row){
              echo '<tr>';
               echo '<td>'.$row['UserID'].'</td>';
               echo '<td>'.$row['UserName'].'</td>';
               echo '<td>'.$row['Email'].'</td>';
               echo '<td>'.$row['FullName'].'</td>';
               echo '<td>'.$row['Date'].'</td>';
               echo '<td>
               <a href= "members.php?do=Edit&userid='.$row['UserID'].'" class="btn btn-success">Edit</a>
               <a href= "members.php?do=Delete&userid='.$row['UserID'].'" class="btn btn-danger confirm">Delete </a>';
                if($row["RegStatus"] == 0){
                 
                 echo '<a href= "members.php?do=Activate&userid='.$row['UserID'].'" class="btn btn-info activate"> Active</a>';
                 
               }
               '</td>';

              echo '</tr>';
             }?>
        </table>
       </div>
          <a href='members.php?do=Add' class="btn btn-info"><i class = "fa fa-plus"></i>Add New Member</a>
      </div>
    <?php }else{
      echo '<div class="container">';
      echo "<div class='nice-message'>'There\’s No Member To Show'</div>";
      echo '<a href="members.php?do=Add" class="btn btn-info">Add New Member</a>';
      echo '</div>';
    }
     ?>
   <?php }elseif ($do == 'Add'){ //Add page ?>
      <h2 class="text-center">Add New Member</h2>
      <div class="container">
        <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
         <div class="form-group">
              <label class="col-sm-2 control-label">name</label>
              <div class="col-sm-10">
                <input type="text" name="username" class="form-control" autocomplete="off" required>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
               <input type="password" name="Password" class="form-control" autocomplete="new-password" required>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                <input type="email" name="Email" class="form-control">
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Full Name</label>
              <div class="col-sm-10">
                <input type="text" name="FullName" class="form-control" required>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">User Image</label>
              <div class="col-sm-10">
                <input type="file" name="Image" class="form-control" >
              </div>
           </div>
           <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Add Member" class="btn btn-info">
              </div>
           </div>
        </form>
     </div>
  <?php 
   }elseif($do == 'Insert'){
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
     echo '<h2 class="text-center">Update Member</h2>';
     echo '<div class="container">';
     //image uoload
     $image     = $_FILES['Image'];
     $imageName = $_FILES['Image']['name'];
     $imageSize = $_FILES['Image']['size'];
     $imageType = $_FILES['Image']['type'];
     $imageTmp = $_FILES['Image']['tmp_name'];
     //Allow File Type to upload
     $imageAllowExtension = array("png","jpg","jpeg");
     //Get image extension
     $var =explode('.', $imageName);
     $imageExtension = strtolower(end($var));


     $user  = $_POST['username'];
     $pass  = $_POST['Password'];
     $email = $_POST['Email'];
     $name  = $_POST['FullName'];
     $hashpass = sha1($_POST['Password']);
     //validate the form 

     $formErrors = array();
     if(empty($user)){$formErrors[] = 'the user name can not be empty';}
     if(empty($pass)){$formErrors[] = 'the password can not be empty';}
     if(empty($name)){$formErrors[] = 'the full name can not be empty';}
     if(! empty($imageName) && ! in_array($imageExtension,$imageAllowExtension)){
      $formErrors[] = 'This Extension Is Not Allowed';
     }
     if(empty($imageName)){
      $formErrors[] = 'Image Is Required';
     }
     if($imageSize > 4194304){$formErrors[] = 'Image Can Not Be Larger Than 4 MB';}

     foreach($formErrors as $error){echo '<div class="alert alert-danger">'.$error.'</div>';}
     //check no error
     if(empty($formErrors)){
      $image = rand(0,1000000)."_".$imageName;
      move_uploaded_file($imageTmp,"upload\userImage\\".$image);
      //check if user exist in database
      $check = checkItem("UserName","users",$user);
      if($check == 1){
         $theMsg = '<div class = "alert alert-danger"> Sorry the user is found</div>';
         redirctHome($theMsg,'back');
      }else{
           // insert info to database
            $stmt = $con->prepare("INSERT INTO users(UserName,Password,Email,FullName,RegStatus,Date,Image) VALUES (:Zuser,:Zpass,:Zemail,:Zname,1,now(),:Zimage)");
            $stmt->execute(array(
               'Zuser'  => $user,
               'Zpass'  => $hashpass,
               'Zemail' => $email,
               'Zname'  => $name,
               'Zimage' => $image,
               
            ));
            $theMsg= '<div class="alert alert-success">'. $stmt->rowCount().'Recorde Inserted</div>';
            redirctHome($theMsg,'back');
           }

   }

     }else{
      echo '<div class = "container">';
      $theMsg =  '<div class="alert alert-danger">you can not browse this page</div>';
      redirctHome($theMsg);
      echo '</div>';
     }

   }elseif($do == "Edit"){// Edit Page 
   $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
   $stmt = $con->prepare('SELECT * FROM users WHERE UserID = ? LIMIT 1');
   $stmt->execute(array($userid));
   $row = $stmt->fetch();
   $count = $stmt->rowCount();
   if($count > 0){ ?>
     <h2 class="text-center">Edit Member</h2>
     <div class="container">
        <form class="form-horizontal" action="?do=Update" method="POST">
         <input type="hidden" name="userid" value="<?php echo $userid ?>">
           <div class="form-group">
              <label class="col-sm-2 control-label">name</label>
              <div class="col-sm-10">
                <input type="text" name="username" class="form-control" value="<?php echo $row['UserName'] ?>" autocomplete="off" required>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
              <input type="hidden" name="oldPassword" value="<?php echo $row['Password']?>">
                <input type="password" name="newPassword" class="form-control" autocomplete="new-password">
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                <input type="email" name="Email" class="form-control" value="<?php echo $row['Email'] ?>">
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Full Name</label>
              <div class="col-sm-10">
                <input type="text" name="FullName" class="form-control" value="<?php echo $row['FullName'] ?>" required>
              </div>
           </div>
           <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Save" class="btn btn-info">
              </div>
           </div>
        </form>
     </div>
  <?php }else{
        echo "<div class ='container'>";
        $theMsg =  " <div class='alert alert-danger'>ID is not found</div>";
        redirctHome($theMsg);
        echo '</div>';
  }
   }elseif($do == 'Update'){ //Update page
     echo '<h2 class="text-center">Update Member</h2>';
    
     echo '<div class="container">';
     if($_SERVER['REQUEST_METHOD'] == 'POST'){
      // Get variable from the form
      $id   = $_POST['userid'];
      $user = $_POST['username'];
      $email = $_POST['Email'];
      $name = $_POST['FullName'];
      //password trick
      $pass = " ";
      if(empty($_POST['newPassword'])){$pass = $_POST['oldPassword']; }
      else{
        $pass = sha1($_POST['newPassword']);
      }
      //validate the form
      $formErrors = array();
      if(empty($user)){$formErrors[] = '<div class="alert alert-danger">the user name can not empty';}
      if(empty($name)){$formErrors[] = '<div class="alert alert-danger">the full name can not empty';}
      foreach($formErrors as $error){echo $error;}
      //check no error
      if(empty($formErrors)){
        //update database
        $stmt = $con->prepare("SELECT * FROM users WHERE UserName =? AND UserID != ?");
        $stmt->execute(array($user,$id));
        $rows = $stmt->rowCount();
        if($rows == 1){
         $theMsg = '<div class="alert alert-danger">The name is exist</div>';
         redirctHome($theMsg,'back');
        }else{
      $stmt= $con->prepare("UPDATE users SET UserName = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ? ");
      $stmt->execute(array($user,$email,$name,$pass,$id));
      $theMsg = '<div class="alert alert-success">'. $stmt->rowCount().'Recorde updated</div>';
      redirctHome($theMsg,'back');
        }

      }
      }else{
        $theMsg = '<div class="alert alert-danger">you can not browse this page</div>';
        redirctHome($theMsg);
      }
      


   }elseif($do == 'Delete'){
    echo '<h2 class="text-center">Delete Member</h2>';
    echo '<div class="container">';
     //type1
  
    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
    $check = checkItem('userid','users',$userid);
    if($check > 0){
          $stmt = $con->prepare('DELETE FROM users WHERE UserID = ? LIMIT 1');
          $stmt->execute(array($userid));
         $theMsg= '<div class="alert alert-success">'. $stmt->rowCount().'Recorde Deleted</div>';
         redirctHome($theMsg,'back');
    }

   

   }elseif($do == 'Activate'){
      echo '<h2 class="text-center">Activate Member</h2>';
      echo '<div class="container">';
       //type1
    
      $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
      $check = checkItem('userid','users',$userid);
      if($check > 0){
            $stmt = $con->prepare('UPDATE users SET RegStatus = 1 WHERE UserID = ? ');
            $stmt->execute(array($userid));
            $theMsg= '<div class="alert alert-success">'. $stmt->rowCount().'Recorde Activated</div>';
            redirctHome($theMsg);
      }
  
     
  
     }else{
      $theMsg = "<div class = 'alert alert-danger'> ID is not found</div>";
      redirctHome($theMsg);
   }
    //type2
    // $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
    // $stmt = $con->prepare('DELETE FROM users WHERE UserID = :zid LIMIT 1');
    // $stmt->bindparam(":zid",$userid);
    // $stmt->execute();
    // $count = $stmt->rowCount();
    // echo '<div class="alert alert-success">'. $stmt->rowCount().'Recorde Deleted</div>';

   
   echo '</div>';
   include $tpl . "/footer.php";
   }else{
      header('Location: index.php');
      exit();
   }

ob_end_flush();
?>