<link rel="stylesheet" href="assets/bootstrap4/css/bootstrap.min.css"/>     <!-- so that every file can get this -->
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css"/>    <!--Icons-->
<link rel="stylesheet" href="assets/css/style-header.css"/>
<nav>
    <div class="container">            
        <a href="#">
            <h6>Govind Vidyalaya</h6>
            <p>Tamulia, Jamshedpur 20 </p>
        </a>
        <?php
            if(isset($_SESSION["stuName"])){
        ?>
            <a href="#" class="item-right">Welcome <b><label id="lblNameFromHeader" ><?php echo $_SESSION["stuName"] ?></b> </label> </a>

            <!-- some hidden fields to store student data -->
            <label id="lblStuId" style="display:none;"><?php echo $_SESSION["addNo"]; ?></label>
            <label id="lblStuName" style="display:none;"><?php echo $_SESSION["stuName"]; ?></label>
            <label id="lblStuClass" style="display:none;"><?php echo $_SESSION["stuClassId"]; ?></label>
            <label id="lblStuSection" style="display:none;"><?php echo $_SESSION["stuSecId"]; ?></label>
            <label id="lblStuStream" style="display:none;"><?php echo $_SESSION["stuStreamId"]; ?></label>

        <?php
            }
            else{
        ?>
            <a href="#" class="item-right"></a>
        <?php   }
        ?>
    </div>        
</nav>

<!-- new version -->
<script src="assets/js/jquery.js"></script>                 
<script src="assets/bootstrap4/js/bootstrap.min.js"></script>

<!-- older version -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>