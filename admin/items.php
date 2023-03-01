<?php
ob_start();
session_start();
$pageTitle = 'Items';
if(isset($_SESSION['Username'])){
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if($do == 'Manage'){
      $stmt3 = $con->prepare("SELECT items.*, categories.Name AS category_name, users.UserName FROM items
                              INNER JOIN categories ON categories.ID = items.Cat_ID
                              INNER JOIN users ON users.UserID = items.Member_ID
                              ORDER BY Item_ID DESC");
      $stmt3->execute();
      $items = $stmt3->fetchAll();
      if(!empty($items)){
      ?>
        
      <h2 class="text-center">Manage Item</h2>
      <div class="container">
       <div class="table-responsive">
        <table class="table text-center table-bordered">
          <tr>
             <td>#ID</td>
             <td>Name</td>
             <td>Description</td>
             <td>Price</td>
             <td>Add_Date</td>
             <td>category</td>
             <td>username</td>
             <td>control</td>
          </tr>
             <?php foreach($items as $item){
              echo '<tr>';
               echo '<td>'.$item['Item_ID'].'</td>';
               echo '<td>'.$item['Name'].'</td>';
               echo '<td>'.$item['Description'].'</td>';
               echo '<td>'.$item['Price'].'</td>';
               echo '<td>'.$item['Add_Date'].'</td>';
               echo '<td>'.$item['category_name'].'</td>';
               echo '<td>'.$item['UserName'].'</td>';
               echo '<td>
               <a href= "items.php?do=Edit&itemid='.$item['Item_ID'].'" class="btn btn-success">Edit</a>
               <a href= "items.php?do=Delete&itemid='.$item['Item_ID'].'" class="btn btn-danger confirm">Delete </a>';
               if($item['Approve']==0){
                  echo '<a href= "items.php?do=Approve&itemid='.$item['Item_ID'].'" class="btn btn-info activate">Approve</a>';
               }
               '</td>';

              echo '</tr>';
             }?>
        </table>
       </div>
          <a href='items.php?do=Add' class="btn btn-info"><i class = "fa fa-plus"></i>Add New Item</a>
      </div>
    <?php 
      }else{
         echo '<div class="container">';
         echo "<div class='nice-message'>'There\’s No Item To Show'</div>";
         echo '<a href="items.php?do=Add" class="btn btn-info">Add New Item</a>';
         echo '</div>';    
      }
    ?>
      
     
    <?php
    }elseif($do == 'Add'){?>
        <h2 class="text-center">Add New Item</h2>
        <div class="container">
        <form class="form-horizontal" action="?do=Insert" method="POST">
           <div class="form-group">
              <label class="col-sm-2 control-label">Name</label>
              <div class="col-sm-10">
                 <input type="text" name="name" class="form-control" required>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10">
                 <input type="text" name="description" class="form-control">
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Price</label>
              <div class="col-sm-10">
                 <input type="text" name="price" class="form-control" required>
              </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Country_Made</label>
              <div class="col-sm-10">
                 <input type="text" name="country" class="form-control">
              </div>
           </div>
           <div class="form-group">
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-10">
                <select name="satuts" class="form-control">
                    <option value="0">Select..</option>
                    <option value="1">New</option>
                    <option value="2">Like New</option>
                    <option value="3">Used</option>
                    <option value="4">very Old</option>
                </select>
            </div>
           </div>
           <div class="form-group">
            <label class="col-sm-2 control-label">Member</label>
            <div class="col-sm-10">
                <select name="member" class="form-control">
                    <option value="0">Select..</option>
                    <?php
                    $users = getAllFrom("*","users","","","UserID");
                    foreach($users as $user){
                     echo '<option value="'.$user['UserID'].'">'.$user['UserName'].'</option>';
                    }
                    ?>
                  
                </select>
            </div>
           </div>
           <div class="form-group">
            <label class="col-sm-2 control-label">Category</label>
            <div class="col-sm-10">
                <select name="category" class="form-control">
                    <option value="0">Select..</option>
                    <?php
                    $cats = getAllFrom("*","categories","","","ID");
                    foreach($cats as $cat){
                        echo '<option value="'.$cat['ID'].'">'.$cat['Name'].'</option>';
                     $childcats = getAllFrom("*","categories","WHERE parent =".$cat['ID']."","","ID");
                     foreach($childcats as $child){
                        echo '<option value="'.$child['ID'].'">'.'*****'.$child['Name'].'</option>';
                     } 
                    }
                    ?>
                </select>
            </div>
           </div>
           <div class="form-group">
              <label class="col-sm-2 control-label">Tags</label>
              <div class="col-sm-10">
                 <input type="text" name="tags" class="form-control">
              </div>
           </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                 <input type="submit" value="Add Item" class="btn btn-info">
              </div>
           </div>
        </form>
     </div>
<?php
    }elseif($do == 'Insert'){
      echo '<h2 class="text-center">Insert Item</h2>';
      echo '<div class="container">';
      $name    = $_POST['name'];
      $desc    = $_POST['description'];
      $price   = $_POST['price'];
      $country = $_POST['country'];
      $satuts  = $_POST['satuts'];
      $member  = $_POST['member'];
      $cat     = $_POST['category'];
      $tags    = $_POST['tags'];
      
      $formErrors = array();
      if(empty($name)){
         $formErrors = 'Name can not be <strong>Empty</strong>';
      }
      if(empty($price)){
         $formErrors = 'Price can not be <strong>Empty</strong>';
      } 
      if($satuts == 0){
         $formErrors = 'You Must choose status';
      } 
      if($member == 0){
         $formErrors = 'You Must choose member';
      }
      if($cat == 0){
         $formErrors = 'You Must choose category';
      }  
      if(empty($formErrors)){
         $stmt = $con->prepare("INSERT INTO items(Name,Description,Price,Country_Made,Status,Add_Date,Cat_ID,Member_ID,tags) VALUES (:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember,:ztags)");
         $stmt->execute(array(
            'zname'    => $name,
            'zdesc'    => $desc,
            'zprice'   => $price,
            'zcountry' => $country,
            'zstatus'  => $satuts,
            'zcat'     => $cat,
            'zmember'  => $member,
            'ztags'    => $tags,
         ));
         $theMsg= '<div class="alert alert-success">'. $stmt->rowCount().'Recorde Inserted</div>';
            redirctHome($theMsg,'back');
      }else{
         echo '<div class = "container">';
         $theMsg =  '<div class="alert alert-danger">you can not browse this page</div>';
         redirctHome($theMsg);
         echo '</div>';
        }
    }elseif($do == 'Edit'){
      $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
      $stmt4 = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
      $stmt4->execute(array($itemid));
      $item = $stmt4->fetch();
      $count  = $stmt4->rowCount();
      if($count > 0){ ?>
         <h2 class="text-center">Edit Item</h2>
         <div class="container">
         <form class="form-horizontal" action="?do=Update" method="POST">
            <input type="hidden" name="itemid" value="<?php echo $itemid;?>">
            <div class="form-group">
               <label class="col-sm-2 control-label">Name</label>
               <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" required value="<?php echo $item['Name']; ?>">
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-2 control-label">Description</label>
               <div class="col-sm-10">
                  <input type="text" name="description" class="form-control" value="<?php echo $item['Description']; ?>">
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-2 control-label">Price</label>
               <div class="col-sm-10">
                  <input type="text" name="price" class="form-control" required value="<?php echo $item['Price'];?>">
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-2 control-label">Country_Made</label>
               <div class="col-sm-10">
                  <input type="text" name="country" class="form-control" value="<?php echo $item['Country_Made'];?>">
               </div>
            </div>
            <div class="form-group">
             <label class="col-sm-2 control-label">Status</label>
             <div class="col-sm-10">
                 <select name="satuts" class="form-control">
                    
                     <option value="1" <?php if($item['Status'] == 1){ echo 'selected';} ?>>New</option>
                     <option value="2" <?php if($item['Status'] == 2){ echo 'selected';} ?>>Like New</option>
                     <option value="3" <?php if($item['Status'] == 3){ echo 'selected';} ?>>Used</option>
                     <option value="4" <?php if($item['Status'] == 4){ echo 'selected';} ?>>very Old</option>
                 </select>
             </div>
            </div>
            <div class="form-group">
             <label class="col-sm-2 control-label">Member</label>
             <div class="col-sm-10">
                 <select name="member" class="form-control">
                     
                     <?php
                     $users = getAllFrom("*","users","","","UserID");
                     foreach($users as $user){
                      echo '<option value="'.$user['UserID'].'" ';
                      if($item['Member_ID'] == $user['UserID']){echo 'selected';}
                      echo '>'.$user['UserName'].'</option>';
                     }
                     ?>
                   
                 </select>
             </div>
            </div>
            <div class="form-group">
             <label class="col-sm-2 control-label">Category</label>
             <div class="col-sm-10">
                 <select name="category" class="form-control">
                     
                     <?php
                     $cats = getAllFrom("*","categories","","","ID");
                     foreach($cats as $cat){
                      echo '<option value="'.$cat['ID'].'" ';
                      if($item['Cat_ID'] == $cat['ID']){echo 'selected';}
                      echo '>'.$cat['Name'].'</option>';
                      foreach($childcats as $child){
                        echo '<option value="'.$child['ID'].'">-->'.$child['Name'].'--> Sub Category From'.$cat['Name'].'</option>';
                     } 
                     }
                     ?>
                 </select>
             </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Tags</label>
              <div class="col-sm-10">
                 <input type="text" name="tags" class="form-control" value="<?php echo $item['tags']?>">
              </div>
           </div>
             <div class="form-group">
               <div class="col-sm-offset-2 col-sm-10">
                  <input type="submit" value="Edit Item" class="btn btn-info">
               </div>
            </div>
         </form><?php
         $stmt = $con->prepare("SELECT comments.* ,users.UserName FROM comments 
                              INNER JOIN users ON users.UserID = comments.user_id
                              WHERE item_ID =?");
        $stmt->execute(array($itemid));
        $rows = $stmt->fetchAll();
        if(!empty($rows)){ ?>
        <h2 class="text-center">Manage <?php echo $item['Name'] ?> Comment</h2>
            <div class="table-responsive">
                <table class="table text-center table-bordered">
                <tr>
                    <td>Comment</td>
                    <td>User Name</td>
                    <td>comment Date</td>
                    <td>control</td>
                </tr>
                    <?php foreach($rows as $row){
                    echo '<tr>';

                    echo '<td>'.$row['comment'].'</td>';

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

            <?php } ?>
      </div>
   <?php
      }else{
         echo "<div class ='container'>";
        $theMsg =  " <div class='alert alert-danger'>ID is not found</div>";
        redirctHome($theMsg);
        echo '</div>';

      }

    }elseif($do == 'Update'){
      echo '<h2 class="text-center">Update Item</h2>';
      echo '<div class="container">';
     if($_SERVER['REQUEST_METHOD']=='POST'){
      $id      = $_POST['itemid'];
      $name    = $_POST['name'];
      $desc    = $_POST['description'];
      $price   = $_POST['price'];
      $country = $_POST['country'];
      $satuts  = $_POST['satuts'];
      $member  = $_POST['member'];
      $cat     = $_POST['category'];
      $tags    = $_POST['tags'];
      $formErrors = array();
      if(empty($name)){
         $formErrors = 'Name can not be <strong>Empty</strong>';
      }
      if(empty($price)){
         $formErrors = 'Price can not be <strong>Empty</strong>';
      } 
      if($satuts == 0){
         $formErrors = 'You Must choose status';
      } 
      if($member == 0){
         $formErrors = 'You Must choose member';
      }
      if($cat == 0){
         $formErrors = 'You Must choose category';
      }  
      if(empty($formErrors)){
         $stmt= $con->prepare("UPDATE items SET Name=?,Description=?,Price=?,Country_Made=?,Status=?,Member_ID=?,Cat_ID=?,tags=?
                              WHERE Item_ID=?");
         $stmt->execute(array($name,$desc,$price,$country,$satuts,$member,$cat,$tags,$id));
         $theMsg = '<div class="alert alert-success">'. $stmt->rowCount().'Recorde updated</div>';
         redirctHome($theMsg,'back');
      }else{
         $theMsg = '<div class="alert alert-danger">you can not browse this page</div>';
         redirctHome($theMsg);
      }

     }
    }elseif($do == 'Delete'){
      echo '<h2 class="text-center">Delete Item</h2>';
      echo '<div class="container">';
      $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
      $check = checkItem('Item_ID','items',$itemid);
      if($check > 0){
            $stmt = $con->prepare('DELETE FROM items WHERE Item_ID = ?');
            $stmt->execute(array($itemid));
             $theMsg= '<div class="alert alert-success">'. $stmt->rowCount().'Recorde Deleted</div>';
           redirctHome($theMsg);
      }
    }elseif($do == 'Approve'){
      echo '<h2 class="text-center">Activate Member</h2>';
      echo '<div class="container">';
      $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
      $check = checkItem("Item_ID","items",$itemid);
      if($check > 0){
         $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
         $stmt->execute(array($itemid));
         $theMsg= '<div class="alert alert-success">'. $stmt->rowCount().'Recorde Approved</div>';
         redirctHome($theMsg);
      }
    }

}else{
      header('Location: index.php');
      exit();
}
include $tpl . "/footer.php";

ob_end_flush();
?>