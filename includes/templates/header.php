<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF_8">
    <!-- <title></title> -->
    <link rel="stylesheet" href="layouts/css/front.css">
    <link rel="stylesheet" href="layouts/css/jquery-ui.min.css">
    <link rel="stylesheet" href="layouts/css/jquery.selectBoxIt.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
    <link rel="stylesheet" href="layouts/css/font-awesome.min.css">
   
    <title><?php getTitle()?></title> 
  </head>
  <div class="upper-bar">
    <div class="container">
      <?php
      if(isset($_SESSION['user'])){?>
        <img class="my-image img-thumbnail" src="" alt=""/>
        <div class="btn-group">
          <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <?php echo $sessionUser; ?>
            <span class="caret"></span>
          </span>
          <ul class="dropdown-menu">
            <li><a href="profile.php">My Profile</a></li>
            <li><a href="newads.php">New Item</a></li>
            <li><a href="profile.php#My-item">My Item</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>

        <?php
        
      }else{
      ?>
     <a href="login.php">Login</a> || <a href="signup.php">Signup</a>
     <?php } ?>
    </div>
   </div>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Home Page  </a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav">
        <?php
        $allcats = getAll("*","categories","where parent=0","","ID","ASC");
        foreach($allcats as $cat){
          echo '<div class="container">';
            echo '<li class="nav-item">
            <a class="nav-link" href="categories.php?pageid='.$cat['ID'].'">'.$cat['Name'].'</a></li>';
          echo '</div>';
        }
      ?>
      </ul>
    </div>
  </nav>

<body>




