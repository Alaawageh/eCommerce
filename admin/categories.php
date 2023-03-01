<?php
ob_start();
 session_start();
 $pageTitle = "Category";
 if(isset($_SESSION['Username'])){
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if($do == 'Manage'){
      $sort = 'ASC';
      $sort_array=(array('ASC','DESC'));
      if(isset($_GET['sort']) && in_array($_GET['sort'] , $sort_array)){
         $sort =$_GET['sort']; 
      }
      $stmt2 = $con->prepare("SELECT * FROM Categories WHERE parent=0 ORDER BY Ordering $sort");
      $stmt2->execute();
      $cats = $stmt2->fetchAll(); ?>
          <h2 class="text-center">Manage Categories</h2>
            <div class="container categories">
               <div class="panel panel-default">
                  <div class="panel-heading">Manage Categories
                     <div class="option pull-right">
                        <i class="fa fa-sort"></i> Ordering: [
                        <a class="<?php if($sort == 'ASC'){echo 'active';} ?>" href="?sort=ASC">Asc</a> |
                        <a class="<?php if($sort == 'DESC'){echo 'active';} ?>" href="?sort=DESC">Desc</a>]
                        <i class="fa fa-eye"></i> View: [
                        <span class="active" data-view="Full">Full</span> |
                        <span data-view="Classic">Classic</span>]
                     </div>
                  </div>
                  <div class="panel-body">
                     <?php
                     foreach($cats as $cat){
                        echo '<div class="cat">';
                        echo '<div class="hidden-buttons">';
                         echo '<a href="categories.php?do=Edit&catid='.$cat['ID'].'" class="btn btn-sm btn-info"><i class="fa fa-edit">Edit</i></a>';
                         echo '<a href="categories.php?do=Delete&catid='.$cat['ID'].'" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash">Delete</i></a>';
                        echo '</div>';
                        echo '<h3>'.$cat['Name'].'</h3>';
                        echo '<div class="full-view">';
                           echo "<p>";if($cat['Description'] == ''){echo 'The category has no discription';}else{echo $cat['Description'];}'</p><br>';
                           if($cat['Visibility'] == 1){echo '<span class="Visibility"> Hidden </span>';}
                           if($cat['Allow_Comment'] == 1){echo '<span class="commenting"> Comment Disabled </span>';}
                           if($cat['Allow_Ads'] == 1){echo '<span class="advertise"> Ads Disabled </span>';}
                        echo '</div>';
                        $subCat = getAllFrom("*","categories","where parent = ".$cat['ID']."","","ID","ASC");
                        if(!empty($subCat)){
                          echo '<h4 class="child-head">Sub Category</h4>';
                          echo '<ul class="list-unstyled child-cats">';
                          foreach($subCat as $c){
                           echo "<li class='cat-link'>
                              <a href='categories.php?do=Edit&catid=".$c['ID']."'>".$c['Name']."</a>
                              <a href='categories.php?do=Delete&catid=".$c['ID']."' class='confirm show-delete'>Delete</a>
                           </li>";
                           
                          }
                          echo '</ul>';
                        }
                        echo '</div>';
                        echo '<hr>';
                     }
                     
                     ?>

                  </div>
               </div>
               <a href="categories.php?do=Add" class="btn btn-info"><i class="fa fa-plus">Add New Category</i></a>
            </div>

    <?php }elseif($do == 'Add'){?>
          <h2 class="text-center">Add New Category</h2>
            <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
               <div class="form-group">
                  <label class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-10">
                     <input type="text" name="name" class="form-control" autocomplete="off" required>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-10">
                     <input type="text" name="description" class="form-control">
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Ordering</label>
                  <div class="col-sm-10">
                     <input type="text" name="ordering" class="form-control">
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Parent</label>
                  <div class="col-sm-10">
                     <select name="parent" class="form-control">
                        <option value="0">None</option>
                        <?php
                        $allCats = getAllFrom("*","categories","WHERE parent=0","","ID","ASC");
                        foreach($allCats as $cat){
                           echo '<option value="'.$cat['ID'].'">'.$cat["Name"].'</option>';
                        }
                        ?>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Visibility</label>
                  <div class="col-sm-10">
                     <div>
                        <input type="radio" id="vis-yes" name="visibility" value="0" checked>
                        <label for="vis-yes">Yes</label>
                     </div>
                     <div>
                        <input type="radio" id="vis-no" name="visibility" value="1" >
                        <label for="vis-no">No</label>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Commenting</label>
                  <div class="col-sm-10">
                     <div>
                        <input type="radio" id="com-yes" name="commenting" value="0" checked>
                        <label for="com-yes">Yes</label>
                     </div>
                     <div>
                        <input type="radio" id="com-no" name="commenting" value="1" >
                        <label for="com-no">No</label>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Ads</label>
                  <div class="col-sm-10">
                     <div>
                        <input type="radio" id="ads-yes" name="ads" value="0" checked>
                        <label for="ads-yes">Yes</label>
                     </div>
                     <div>
                        <input type="radio" id="ads-no" name="ads" value="1" >
                        <label for="ads-no">No</label>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                     <input type="submit" value="Add Category" class="btn btn-info">
                  </div>
               </div>
            </form>
         </div>
    
    <?php }elseif($do == 'Insert'){
      echo '<h2 class="text-center">Add New Category</h2>';
      echo '<div class="container">';
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $name        = $_POST['name'];
         $desc        = $_POST['description'];
         $order       = $_POST['ordering'];
         $parent      = $_POST['parent'];
         $visible     = $_POST['visibility'];
         $comment     = $_POST['commenting'];
         $ads         = $_POST['ads'];
         //validate
         $formError = '';
         if(empty($name)){
            $formError = 'the user name can not be empty';
         }
         if(empty($formError)){
             //check if category exite 
            $check = checkItem('Name','Categories',$name);
            if($check == 1){
               $theMsg = '<div class = "alert alert-danger"> Sorry the category is found</div>';
               redirctHome($theMsg,'back');
            }else{
               $stmt = $con->prepare("INSERT INTO Categories(Name,Description,Ordering,parent,Visibility,Allow_Comment,Allow_Ads)
               VALUES (:zname,:zdesc,:order,:zparent,:visible,:zcomment,:zads)");
               $stmt->execute(array(
                  'zname'    => $name,
                  'zdesc'    => $desc,
                  'order'    => $order,
                  'zparent'  => $parent,
                  'visible'  => $visible,
                  'zcomment' =>$comment,
                  'zads'     => $ads
               )
   
               );
               $theMsg = '<div class = "alert alert-success">'.$stmt->rowCount().'Recorde Inserted</div>';
               redirctHome($theMsg,'back');
            }

         }

      }
      
    }elseif($do == 'Edit'){
      $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
      $stmt3 = $con->prepare("SELECT * FROM Categories WHERE ID = ?");
      $stmt3->execute(array($catid));
      $cat = $stmt3->fetch();
      $count = $stmt3->rowCount();
      if($count > 0){?>
       <h2 class="text-center">Edit Category</h2>
            <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
               <input type="hidden" name="ID" value="<?php echo $catid; ?>">
               <div class="form-group">
                  <label class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-10">
                     <input type="text" name="name" class="form-control" required value="<?php echo $cat['Name'];?>">
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-10">
                     <input type="text" name="description" class="form-control" value="<?php echo $cat['Description'];?>">
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Ordering</label>
                  <div class="col-sm-10">
                     <input type="text" name="ordering" class="form-control" value="<?php echo $cat['Ordering'];?>">
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Parent</label>
                  <div class="col-sm-10">
                     <select name="parent" class="form-control">
                        <option value="0">None</option>
                        <?php
                        $allCats = getAllFrom("*","categories","WHERE parent=0","","ID","ASC");
                        foreach($allCats as $allcat){
                           echo '<option value="'.$allcat['ID'].'"';
                           if($cat['parent']==$allcat['ID']){echo 'selected';}
                           echo '>'.$allcat["Name"].'</option>';
                        }
                        ?>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Visibility</label>
                  <div class="col-sm-10">
                     <div>
                        <input type="radio" id="vis-yes" name="visibility" value="0"<?php if($cat['Visibility'] == 0){echo 'checked';}?>>
                        <label for="vis-yes">Yes</label>
                     </div>
                     <div>
                        <input type="radio" id="vis-no" name="visibility" value="1"<?php if($cat['Visibility'] == 1){echo 'checked';}?> >
                        <label for="vis-no">No</label>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Commenting</label>
                  <div class="col-sm-10">
                     <div>
                        <input type="radio" id="com-yes" name="commenting" value="0"<?php if($cat['Allow_Comment'] == 0){echo 'checked';}?>>
                        <label for="com-yes">Yes</label>
                     </div>
                     <div>
                        <input type="radio" id="com-no" name="commenting" value="1"<?php if($cat['Allow_Comment'] == 1){echo 'checked';}?> >
                        <label for="com-no">No</label>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-2 control-label">Ads</label>
                  <div class="col-sm-10">
                     <div>
                        <input type="radio" id="ads-yes" name="ads" value="0"<?php if($cat['Allow_Ads'] == 0){echo 'checked';}?>>
                        <label for="ads-yes">Yes</label>
                     </div>
                     <div>
                        <input type="radio" id="ads-no" name="ads" value="1"<?php if($cat['Allow_Ads'] == 1){echo 'checked';}?> >
                        <label for="ads-no">No</label>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                     <input type="submit" value="Edit Category" class="btn btn-info">
                  </div>
               </div>
            </form>
         </div>
    
      <?php
      }else{
         echo "<div class ='container'>";
         $theMsg =  " <div class='alert alert-danger'>ID is not found</div>";
         redirctHome($theMsg);
         echo '</div>';
         }
      
    }elseif($do == 'Update'){
      echo '<h2 class="text-center">Update Category</h2>';
    
      echo '<div class="container">';
     if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $id       = $_POST['ID'];
      $name     = $_POST['name'];
      $desc     = $_POST['description'];
      $order    = $_POST['ordering'];
      $parent   = $_POST['parent'];
      $visible  = $_POST['visibility'];
      $comment  = $_POST['commenting'];
      $ads      = $_POST['ads'];

      $stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?, Ordering = ?, parent = ?,Visibility = ?, Allow_Comment = ?, Allow_ads = ?
                                  WHERE ID = ? ");
      $stmt->execute(array($name,$desc,$order,$parent,$visible,$comment,$ads,$id));
      $theMsg = '<div class="alert alert-success">'. $stmt->rowCount().'Recorde updated</div>';
      redirctHome($theMsg,'back');

     }else{
      $theMsg = '<div class="alert alert-danger">you can not browse this page</div>';
      redirctHome($theMsg);
    }
    }elseif($do == 'Delete'){
      echo '<h2 class="text-center">Delete Category</h2>';
      echo '<div class="container">';
       
      $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
      $check = checkItem('ID','categories',$catid);
      if($check > 0){
            $stmt = $con->prepare('DELETE FROM categories WHERE ID = ? LIMIT 1');
            $stmt->execute(array($catid));
           $theMsg= '<div class="alert alert-success">'. $stmt->rowCount().'Recorde Deleted</div>';
           redirctHome($theMsg,'back');
      }

    }
    include $tpl . "/footer.php";
 }else{
   header('Location: index.php');
   exit();
 }
ob_end_flush();
?>