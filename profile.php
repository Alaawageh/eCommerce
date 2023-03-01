<?php
session_start();
$pageTitle = 'Profile';
include "init.php";
if(isset($_SESSION['user'])){
    $getUser = $con->prepare("SELECT * FROM users WHERE UserName=?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
   
?>
<h1 class="text-center">My Profile</h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">My information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li><i class="fa fa-unlock-alt fa fw"></i>
                        <span>Name </span> :<?php  echo $info['UserName'];?></li>
                    <li><i class="fa fa-envelope fa fw"></i>
                    <span>Email </span> :<?php  echo $info['Email'];?></li>
                    <li><i class="fa fa-user fa fw"></i>
                    <span>FullName </span> :<?php  echo $info['FullName'];?></li>
                    <li><i class="fa fa-calendar fa fw"></i>
                    <span>Register Date </span> :<?php  echo $info['Date'];?></li>
                    <li><i class="fa fa-tag fa fw"></i>
                    <span>favorite category </span>:</li> 
                </ul>
                <a href="#" class="btn btn-default">Edit Information</a>
            </div>
        </div>
    </div>
</div>
<div id="My-item" class="My_ads block">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">My Item</div>
             <div class="panel-body">

                <?php 
                $myitems = getAll("*","items","WHERE Member_ID =".$info['UserID']."" ,"AND Approve = 1" ,"Item_ID");
                if(!empty($myitems)){
                    echo '<div class="row">';
                    foreach($myitems as $item){
                        echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
                            echo '<span class="price-tag"> '.$item['Price'].'</span>';
                            echo '<img class="img-responsive" src="hand.jpg" alt="">';
                            echo '<div class="caption">';
                                echo '<h3><a href="items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>';
                                echo '<p>'.$item['Description'].'</p>';
                                echo '<div class="date">'.$item['Add_Date'].'</div>';
                                if($item['Approve'] == 0) {
                                    echo '<span style="background-color:red">Waiting Approved</span>';
                                }
                            echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        }
                        echo '</div>';
                }else {
                    echo 'There\'s No Ads to show, Create <a href="newads.php">New Item</a>';
                }

                ?>

             </div>
        </div>
    </div>
</div>
<div class="My_comments block">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">My comments</div>
            <div class="panel-body">
                <?php
                $allcomment = getAll("comment","comments","WHERE user_id = ".$info['UserID']."","","c_id");
    
                if(! empty($allcomment)){
                    foreach($allcomment as $comment){
                        echo '<p>'.$comment['comment'].'</p>';
                    }
                }else{
                    echo 'There\'s No comment to show';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php 
}else{
    header('Location: login.php');
    exit();
}
