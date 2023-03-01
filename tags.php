<?php include "init.php";?>

<div class="container">
  
  <div class="row">
    <?php 
    if( isset($_GET['name'])){
        $tag = $_GET['name'];
        echo '<h3>'.$tag.'</h3>';
        $where ="WHERE tags LIKE '{$tag}_'";
        $getTags= getAll("*","items",$where,'AND Approve = 1','Item_ID');
        foreach($getTags as $item){
            echo '<div class="col-sm-6 col-md-3">';
            echo '<div class="thumbnail item-box">';
            echo '<span class="price-tag">'.$item['Price'].'</span>';
                echo '<img class="img-responsive" src="hand.jpg" alt="">';
                echo '<div class="caption">';
                echo '<h3><a href="items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>';
                echo '<p>'.$item['Description'].'</p>';
                echo '<div class="date">'.$item['Add_Date'].'</div>';
                echo '</div>';
            echo '</div>';
            echo '</div>';
      }
    }
    ?>
  </div>
</div>

<?php include $tpl."/footer.php";?> 