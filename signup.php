<?php
session_start();
$pageTitle='signup';
if(isset($_SESSION['user'])){
    header('Location: index.php');
}
include 'init.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $formErrors = array();
  $username  = $_POST['username'];
  $password  = $_POST['password'];
  $password2 = $_POST['password2'];
  $email     = $_POST['email']; 
  if(isset($username)){
    $filterUser = filter_var($username,FILTER_SANITIZE_STRING);
    if(strlen($filterUser) < 4){
      $formErrors[] = 'UserName Must Be Larger Than 4 character';
    }
  }
  if(isset($password) && ($password2)){
    if(empty($password)){
      $formErrors[] = 'Password Cant Be Empty';
    }

    if(sha1($password) !== sha1($password2)){
      $formErrors[] = 'Sorry The Password Is Not Match';
    }
  }
  if(isset($email)){
   $filterEmail = filter_var($email,FILTER_SANITIZE_EMAIL);
   if(filter_var($filterEmail,FILTER_VALIDATE_EMAIL) != true){
    $formErrors[] = 'This Email Is Not Valid';
   }
  }
  // if check user in database
  $check = checkItem('UserName','users',$username);
  if($check == 1){
    $formErrors[] = 'Sorry The User is Existes';
  }else {
    // insert user info to database
    $stmt = $con->prepare("INSERT INTO users(UserName,Password,Email,RegStatus,Date) VALUES (:zuser,:zpass,:zemail,0,now())");
    $stmt->execute(array(
      'zuser'  => $username,
      'zpass'  => sha1($password),
      'zemail' => $email
    ));
      $successMsg ="Welcome You Are Register Now";
      header('Location: login.php');
  }
 
}

?>
<div class="signup-page">
    <h1 class="text-center">
    <span class="active" data-class="signup">Signup</span>
    </h1>
    <form class="signup" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">    
        <input pattern=".{4,}" title="UserName Must Be Larger Than 4 Chars" type="text" name="username"  class="form-control" placeholder="Enter your name" required onfocus="this.placeholder=''" onblur="this.placeholder='Enter your name'">
        <input type="email" name="email" class="form-control" placeholder="Enter a Valid email" required onfocus="this.placeholder=''" onblur="this.placeholder='Enter a valid email'">
        <input minlength="5" type="password" name="password" autocomplete="new-password" class="form-control" required placeholder="Enter a strong password" onfocus="this.placeholder=''" onblur="this.placeholder='Enter a strong password'">
        <input minlength="5" type="password" name="password2" autocomplete="new-password" class="form-control" required placeholder="Enter a confirm password" onfocus="this.placeholder=''" onblur="this.placeholder='Enter a confirm password'">
        <input type="submit" name="signup" value="Signup" class="btn btn-success btn-block">
    </form>
<div class="error text-center">
  <?php
  if(!empty($formErrors)){
    foreach($formErrors as $error){
      echo '<div class="alert alert-danger">'.$error.'</div>';
  }
 
}
if(isset($successMsg)){
  echo '<div class="alert alert-success">'.$successMsg.'</div>';
}?>
</div>
</div>
