<?php 
session_start();

if(isset($_SESSION['Username'])){
    $pageTitle = "Dashboard";
    include 'init.php';
    $numUsers = 4;
    $latestUsers = getLatest("*","users","UserID",$numUsers);
    $numItems = 4;
    $latestItems = getLatest("*","items","Item_ID",$numItems);
    $numComments = 4;
    //Start dashboard?>
    <h2 class="text-center">Dashboard</h2>
    <div class="container home-stats text-center" >
        <!-- <h2></h2> -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                        Total Members
                        <span><a href="members.php"><?php echo countItems('UserID','users')?></a></span>    
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
                        <div class="info">
                          Pending Members
                            <span><a href="members.php?do=Manage&page=pending"><?php
                            echo checkItem("RegStatus","users",0);
                            ?></a></span>
                        </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                             <span><a href="items.php"><?php echo countItems('Item_ID','items')?></a></span>
                        </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class="fa fa-comments"></i>
                    <div class="info">
                      Total comments
                      <span><a href="comments.php"><?php echo countItems('c_id','comments')?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container latest">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-user"></i>latest <?php echo $numUsers;?> Registerd 
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-users">
                            <?php
                               if(!empty($latestUsers)){
                               foreach($latestUsers as $user){
                                
                                echo '<li>';
                                echo $user['UserName'];
                                echo '<a href="members.php?do=Edit&userid='.$user['UserID'].'">';
                                echo '<span class="btn btn-success pull-right">';
                                echo '<i class="fa fa-edit">Edit</i>';
                                echo '</span>';
                                echo '</a>'; 
                                if($user['RegStatus'] == 0){
                                    echo '<a href= "members.php?do=Activate&userid='.$user['UserID'].'" class="btn btn-info pull-right"><i class="fa fa-check">Active</i></a>';
                                }
                                echo '</li>';
                            
                            }
                        }else{
                            echo "There\’s No Member To Show";
                        }
                            ?>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tag"></i>latest <?php echo $numItems;?> Items
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                      
                    <ul class="list-unstyled latest-users">
                            <?php
                               if(!empty($latestItems)){
                               foreach($latestItems as $item){
                                
                                echo '<li>';
                                echo $item['Name'];
                                echo '<a href="items.php?do=Edit&itemid='.$item['Item_ID'].'">';
                                echo '<span class="btn btn-success pull-right">';
                                echo '<i class="fa fa-edit">Edit</i>';
                                echo '</span>';
                                echo '</a>'; 
                                if($item['Approve'] == 0){
                                    echo '<a href= "items.php?do=Approve&itemid='.$item['Item_ID'].'" class="btn btn-info pull-right"><i class="fa fa-check">Approve</i></a>';
                                }
                                echo '</li>';
                            
                            }
                        }else{
                            echo "There\’s No Item To Show";
                        }
                            ?>
                        </ul>  
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
        <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-comment"></i>latest <?php echo $numComments; ?> Comment 
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                            <?php
                                $stmt = $con->prepare("SELECT comments.* ,users.UserName FROM comments 
                                                       INNER JOIN users ON users.UserID = comments.user_id
                                                       ORDER BY c_id DESC LIMIT $numComments");
                                $stmt->execute();
                                $comments = $stmt->fetchAll();
                                if(!empty($comments)){
                                foreach($comments as $comment){
                                    echo '<div class="comment-box">';
                                     echo '<span class="username-n">'.$comment['UserName'].'</span>';
                                     echo '<p class="username-c">'.$comment['comment'].'</p>';

                                    echo '</div>';

                                }
                            }else{
                                echo "There\’s No Item To Show";
                            }
                            ?>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <?php //End dashboard
  include $tpl . "/footer.php";
}else{
    header('Location: index.php');
    exit();
}