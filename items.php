<?php
ob_start();
session_start();
$pageTitle = 'Show Item';
include "init.php";
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
$stmt4 = $con->prepare("SELECT items.*, categories.Name AS category_name, users.UserName FROM items
                        INNER JOIN categories ON categories.ID = items.Cat_ID
                        INNER JOIN users ON users.UserID = items.Member_ID WHERE Item_ID = ? AND Approve = 1");
$stmt4->execute(array($itemid));
$count = $stmt4->rowCount();
if($count > 0 ){
$item = $stmt4->fetch();

   
?>
<h1 class="text-center"><?php echo $item['Name']?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img class="img-responsive img-thumbnail center-block" src="hand.jpg" alt="">
        </div>
        <div class="col-md-9 item-info">
           <h2><?php echo $item['Name']?></h2>
           <p><?php echo $item['Description']?></p>
           <ul class="list-unstyled">
            <li><i class="fa fa-calendar fa-fw"></i>
                <span>Added Date </span> : <?php echo $item['Add_Date']?></li>
            <li><i class="fa fa-money fa-fw"></i>
                <span>Price</span> :<?php echo $item['Price']?></li>
            <li><i class="fa fa-building fa-fw"></i>
                 <span>Made in </span> :<?php echo $item['Country_Made']?></li>
            <li><i class="fa fa-tags fa-fw"></i>
                <span>Category </span> <a href="categories.php?pageid=<?php echo $item['Cat_ID']?>"><?php echo $item['category_name']?></a></li>
            <li><i class="fa fa-user fa-fw"></i>
                <span>Addes By</span> :<a href="#"><?php echo $item['UserName']?></a></li>
            <li><i class="fa fa-tag fa-fw"></i>
                <span>Tags</span> :<?php
                $allTags = explode(",",$item['tags']);
                foreach($allTags as $tag){
                    $tag = str_replace(' ','',$tag);
                    $tag = strtolower($tag);
                    if(!empty($tag)){
                    echo '<a href="tags.php?name='.$tag.'">'. $tag.'</a> | ' ;
                    }
                }
                ?>
            </li>
           </ul>
        </div>
    </div>
<?php if(isset($_SESSION['user'])){ ?>
    <hr class="custom-hr">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="add-comment">
                <h3>Add Your Comment</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid='.$item['Item_ID'] ?>" method="POST">
                    <textarea name="comment" class="form-control" required></textarea>
                    <input class="btn btn-primary" type="submit" value="Add comment">
                </form>
                <?php 
                if($_SERVER['REQUEST_METHOD'] === 'POST'){
                    $comment = $_POST['comment'];
                    $itemid  = $item['Item_ID'];
                    $userid  = $_SESSION['uid'];
                    if(!empty($comment)){
                        $stmt = $con->prepare("INSERT INTO comments(comment,status,comment_date,item_id ,user_id ) VALUES(:zcomment,0,now(),:zitemid,:zuserid)");
                        $stmt->execute(array(
                            'zcomment'   => $comment,
                            'zitemid'    => $itemid,
                            'zuserid'    => $userid
                        ));
                        if($stmt){
                            
                            echo '<div class="container">';
                            echo '<br>';
                            echo '<div class="alert alert-success">Comment Added</div>';
                            echo '</div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php }else{
        echo ' <a href="login.php">login before Added Comment</a>';
    } ?>
    <hr class="custom-hr">
        <?php 
            $stmt1 = $con->prepare("SELECT comments.*,users.UserName AS Member FROM comments
            INNER JOIN users ON users.UserID = comments.user_id WHERE item_id = ? AND status = 1
            ORDER BY c_id DESC");

            $stmt1->execute(array($item['Item_ID']));

            $comments = $stmt1->fetchAll();

            foreach($comments as $comment){ ?>
          <div class="comment-box">
            <div class="row">
                 <div class="col-sm-2 text-center">
                     <img class="rounded-circle img-responsive img-thumbnail" src="hand.jpg" alt="" >   
                     <?php echo $comment['Member']?>
                </div>
                 <div class="col-sm-10">
                    <p class="lead" ><?php echo $comment['comment']?></p>
                </div>
            </div>
          </div>
          <hr class="custom-hr">
                
           <?php } ?>
       
    
     
    
</div>

<?php 
}else{
 echo '<div class="container">';
 echo '<div class="alert alert-danger">There\'s No Such Id Or This Item Is Waiting Approved</div>';
 echo '</div>';   
}
include $tpl.'/footer.php';
ob_end_flush();
?>
