<link rel="stylesheet" href="assets/bootstrap4/css/bootstrap.min.css"/>     <!-- so that every file can get this -->
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css"/>    <!--Icons-->

<!-- new version jquery -->
<script src="assets/js/jquery.js"></script>                 
<script src="assets/bootstrap4/js/bootstrap.min.js"></script>

<!-- older version jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="assets/css/style-header.css"/>


<?php
//session_start();
    if(isset($_SESSION["userType"])){
?>
<nav>
    <div class="container">            
        <a href="#">
            <h6>Govind Vidyalaya</h6>
            <p>Tamulia, Jamshedpur 20 </p>
        </a>
        <!--<a href="#">Contact</a>
        <a href="#">Download</a>-->
        <a href="#" class="item-right">Welcome <b><label id="lblNameFromHeader" ><?php echo $_SESSION["userName"] ?></b> </label> </a>
    </div>        
</nav>

<!-- hidden field to store user unique id -->
<?php
    if(isset($_SESSION["teachId"])){
?>
<!-- html goes here --> 
<label id="lblUserUnid" style="display:none;" ><?php echo $_SESSION["teachId"] ?></label>
<?php    }
    else if(isset($_SESSION["adminId"])){
?>
<!-- html goes here --> 
<label id="lblUserUnid" style="display:none;" ><?php echo $_SESSION["adminId"] ?></label>
<?php
    }
?>

<?php
    }
    else{
        header("Location: index.php");
    }
?>



