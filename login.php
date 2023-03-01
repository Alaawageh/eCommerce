<?php
session_start();
$pageTitle='login';
if(isset($_SESSION['user'])){
    header('Location: index.php');
}
include 'init.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $hashpass = sha1($pass);
    // check if user exist in database
    $stmt = $con->prepare("SELECT UserID,UserName ,Password FROM users WHERE UserName=? AND Password=?");
    $stmt->execute(array($user,$hashpass));
    $get = $stmt->fetch();
    $count = $stmt->rowCount();
    if($count > 0){
        $_SESSION['user']=$user;
        $_SESSION['uid']=$get['UserID'];
        header('Location: index.php');
        exit();
    }
}

 ?>

<div class="login-page">
    <h1 class="text-center">
        <span class="active" data-class="login">Login</span>  
    </h1>
    <form class="login" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
        <input type="text" name="username" autocomplete="off" class="form-control" placeholder="Enter your name" required onfocus="this.placeholder=''" 
           onblur="this.placeholder='Enter your name'">
        <input type="password" name="password" autocomplete="new-password" class="form-control" placeholder="Enter password" required onfocus="this.placeholder=''" onblur="this.placeholder='Enter password'">
        <input type="submit" name="login" value="login" class="btn btn-primary btn-block">
    </form>

</div>
