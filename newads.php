<?php
session_start();
$pageTitle = 'Create New Item';
include "init.php";
if(isset($_SESSION['user'])){

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $formErrors = array();
        $name   = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
        $desc   = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
        $price  = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT); 
        $country= filter_var($_POST['country'],FILTER_SANITIZE_STRING);
        $satuts = filter_var($_POST['satuts'],FILTER_SANITIZE_NUMBER_INT);
        $cat    = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
        $tags   = filter_var($_POST['tags'],FILTER_SANITIZE_STRING);
        if(empty($name)){
            $formErrors = 'Name can not be <strong>Empty</strong>';
         }
         if(empty($price)){
            $formErrors = 'Price can not be <strong>Empty</strong>';
         } 
         if($satuts == 0){
            $formErrors = 'You Must choose status';
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
               'zmember'  => $_SESSION['uid'],
               'ztags'    => $tags
   
            ));
           if($stmt){
            $successMsg = "Item Added";
           }
         }

    }

?>
<h1 class="text-center"><?php $pageTitle?></h1>
<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class=" panel-heading"><?php echo $pageTitle ?></div>
            <div class=" panel-body">
                <div class="row">
                    <div class="col-md-8">
                    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input pattern=".{4,}" title="This Field Required" type="text" name="name" class="form-control live-name" required >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <input type="text" name="description" class="form-control live-desc" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10">
                                <input type="text" name="price" class="form-control live-price" required >
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
                                    <option value="">Select..</option>
                                    <option value="1">New</option>
                                    <option value="2">Like New</option>
                                    <option value="3">Used</option>
                                    <option value="4">very Old</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10">
                                <select name="category" class="form-control">
                                    <option value="">Select..</option>
                                    <?php
                                    $cats = getAll('*','categories','where parent = 0','','ID');
                                    foreach($cats as $cat){
                                    echo '<option value="'.$cat['ID'].'">'.$cat['Name'].'</option>';
                                   
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
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag">$0</span>
                            <img class="img-responsive" src="hand.jpg" alt="" />
                            <div class="caption">
                                <h3>Title</h3>
                                <p>Description</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            if(!empty($formErrors)){
               foreach($formErrors as $error){
                echo '<div class="alert alert-danger">'.$error.'</div>';
               }
            }if(isset($successMsg)){
                echo '<div class="alert alert-success">'.$successMsg.'</div>';
              }
               ?>
        </div>
       
    </div>
</div>   
<?php 

            
}
else {
    header('location: login.php');
    exit();
}
include $tpl.'/footer.php';
?>






