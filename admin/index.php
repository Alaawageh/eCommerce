<?php 
session_start();
$noNavbar = " ";
$pageTitle = "Login";

if(isset($_SESSION['Username'])){
    header('Location: dashboard.php'); //Redirect to dashboard page
}
include "init.php";

// Check if user coming from http post request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashPass = sha1($password);

    //check if user exist in database
$stmt = $con->prepare('SELECT UserID, UserName, Password FROM users WHERE UserName = ? AND Password = ? AND GroupID = 1 LIMIT 1');
$stmt->execute(array($username,$hashPass));
$row = $stmt->fetch();
$count = $stmt->rowCount();
if($count > 0){
 $_SESSION['Username'] = $username;// Register session name
 $_SESSION['ID'] = $row['UserID'];// Register session Id
 header('Location: dashboard.php'); //Redirect to dashboard page
 exit();
}
}


?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
<h2 class="text-center"> Admin Login </h2>
    <input type="text" class="form-control input-lg" name="user" placeholder="name" autocomplete="off">
    <input type="password" class="form-control input-lg" name="pass" placeholder="password" autocomplete="new-password">
    <input type="submit" class="btn btn-lg btn-primary btn-block" value="Login">
</form>
<?php include $tpl."/footer.php"; ?>