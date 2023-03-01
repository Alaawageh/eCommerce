<?php
ob_start();
session_start();
$pageTitle = 'Comments';
if($_SESSION['Username']){
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if($do == 'Manage'){
        $stmt = $con->prepare("SELECT comments.*,items.Name AS Item_Name ,users.UserName FROM comments
                              INNER JOIN items ON items.Item_ID = comments.item_id
                              INNER JOIN users ON users.UserID = comments.user_id
                              ORDER BY c_id DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();?>
        <h2 class="text-center">Manage Comment</h2>
            <div class="container">
            <div class="table-responsive">
                <table class="table text-center table-bordered">
                <tr>
                    <td>#ID</td>
                    <td>Comment</td>
                    <td>Item Name</td>
                    <td>User Name</td>
                    <td>comment Date</td>
                    <td>control</td>
                </tr>
                    <?php foreach($rows as $row){
                    echo '<tr>';
                    echo '<td>'.$row['c_id'].'</td>';
                    echo '<td>'.$row['comment'].'</td>';
                    echo '<td>'.$row['Item_Name'].'</td>';
                    echo '<td>'.$row['UserName'].'</td>';
                    echo '<td>'.$row['comment_date'].'</td>';
                    echo '<td>
                    <a href= "comments.php?do=Edit&comid='.$row['c_id'].'" class="btn btn-success">Edit</a>
                    <a href= "comments.php?do=Delete&comid='.$row['c_id'].'" class="btn btn-danger confirm">Delete </a>';
                        if($row["status"] == 0){
                        
                        echo '<a href= "comments.php?do=Approve&comid='.$row['c_id'].'" class="btn btn-info activate">Approve</a>';
                        
                    }
                    '</td>';

                    echo '</tr>';
                    }?>
                </table>
            </div>
            </div>
    
    <?php    
    }elseif($do == 'Edit'){
        $comid= isset($_GET['comid'])&&is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $stmt2 = $con->prepare("SELECT * FROM comments WHERE c_id = ? ");
        $stmt2->execute(array($comid));
        $row = $stmt2->fetch();
        $count = $stmt2->rowCount();
        if($count > 0){?>
             <h2 class="text-center">Edit Comment</h2>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid ?>">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10">
                           <textarea class="form-control" name="comment"><?php echo $row['comment'];?></textarea>
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

    }elseif($do == 'Update'){
        echo '<h2 class="text-center">Update Comment</h2>';
        echo '<div class="container">';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
         // Get variable from the form
         $comid   = $_POST['comid'];
         $comment = $_POST['comment'];

           //update database
         $stmt= $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ? ");
         $stmt->execute(array($comment,$comid));
         $theMsg = '<div class="alert alert-success">'. $stmt->rowCount().'Recorde updated</div>';
         redirctHome($theMsg,'back');

         }else{
           $theMsg = '<div class="alert alert-danger">you can not browse this page</div>';
           redirctHome($theMsg);
         }

    }elseif($do == 'Delete'){
        echo '<h2 class="text-center">Delete Member</h2>';
        echo '<div class="container">';
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $check = checkItem('c_id','comments',$comid);
        if($check > 0){
              $stmt = $con->prepare('DELETE FROM comments WHERE c_id = ?');
              $stmt->execute(array($comid));
             $theMsg= '<div class="alert alert-success">'. $stmt->rowCount().'Recorde Deleted</div>';
             redirctHome($theMsg);
        }

    }elseif($do == 'Approve'){
        echo '<h2 class="text-center">Approved Comment</h2>';
        echo '<div class="container">';
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $check = checkItem('c_id','comments',$comid);
        if($check > 0){
              $stmt = $con->prepare('UPDATE comments SET status = 1 WHERE c_id = ? ');
              $stmt->execute(array($comid));
              $theMsg= '<div class="alert alert-success">'. $stmt->rowCount().'Recorde Approved</div>';
              redirctHome($theMsg);
        }

    }else{
        $theMsg = "<div class = 'alert alert-danger'> ID is not found</div>";
        redirctHome($theMsg);
    }
}else{
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>