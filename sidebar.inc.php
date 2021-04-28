<link href="assets/css/style-sidebar.css" rel="stylesheet"/>

<?php
	//session_start();
	if($_SESSION["userType"]=="teacher" && isset($_SESSION["teachId"]) && isset($_SESSION["teachName"]) ){		// this sidebar is only for teachers

?>
<!--========= sidebar for teacher starts =========-->
<div class="sidebar" id="mySidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="uil uil-arrow-left"></i></a>
    <a href="teacher_dashboard.php"><i class="uil uil-estate"></i> <p>Home </p></a>
	<a href="profile_teacher.php" id="userProfile"><i class="uil uil-user-circle"></i>  <p> Profile </p> </a>
	<a href="student_details.php"><i class="uil uil-list-ol"></i> <p> Student Details </p> </a>
	<a href="add_student.php"><i class="uil uil-user-plus"></i>  <p> Add Student </p> </a>
	<a href="#"><i class="uil uil-clipboard"></i> <p> My Tests</p></a>
	<a href="#"><i class="uil uil-file-info-alt"></i> <p> Test Details</p> </a>
    
	<form method="post">
    	<button class="btn btn-sm btn-danger logout-button" name="btnLogout"><i class="uil uil-signout"></i> Logout</button>
	</form>
</div>

		<?php
			//code for logout     
			if(isset($_POST['btnLogout'])){
				session_destroy();
				header('Location: index.php');
			}
		?>

<button class="openbtn" id="opensidebar" onclick="openNav()"><i class="uil uil-bars"></i></button>
<!--===== sidebar for teacher ends =====-->

<div id="main"></div>      <!-- only for measuring the total width of the screen -->

<script>

function openNav() {
   		 document.getElementById("mySidebar").style.width = "200px";
		 document.getElementById("main").style.marginLeft = "200px";
         document.getElementById("opensidebar").style.visibility = "hidden";

  		}
  
  		function closeNav() {
    	 document.getElementById("mySidebar").style.width = "0";
		 document.getElementById("main").style.marginLeft= "0";
         document.getElementById("opensidebar").style.visibility = "visible";
		
		 (function() {
  			window.onresize = displayWindowSize;
  			window.onload = displayWindowSize;

  		function displayWindowSize() {
    		myWidth = window.innerWidth;
    		myHeight = window.innerHeight;
    	// your size calculation code here
			var size=myWidth;
			if(size>618){
				document.getElementById("mySidebar").style.width = "200px";
				document.getElementById("main").style.marginLeft = "200px";
			}
  		};
	})();

		  }

</script>


<?php
	}		// end of if.

	else if($_SESSION["userType"]=="admin" && isset($_SESSION["adminId"]) && isset($_SESSION["adminName"])){		// this sidebar is only for admins
	?>

		<!--========= sidebar for admin starts =========-->
<div class="sidebar" id="mySidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="uil uil-arrow-left"></i></a>
    <a href="admin_dashboard.php"><i class="uil uil-estate"></i> <p>Home </p></a>
	<a href="profile_admin.php" id="userProfile"><i class="uil uil-user-circle"></i> <p> Profile </p> </a>
	<a href="student_details.php"><i class="uil uil-list-ol"></i>  <p> Student Details </p> </a>
	<a href="add_student.php"><i class="uil uil-user-plus"></i> <p> Add Student </p> </a>
	<a href="add_teacher.php"><i class="uil uil-user-nurse"></i> <p> Add Teacher </p> </a>
	<a href="teacher_details.php"><i class="uil uil-user-nurse"></i> <p> Teacher Details </p> </a>
	<a href="#"><i class="uil uil-clipboard"></i> <p> My Tests</p></a>
	<a href="#"><i class="uil uil-file-info-alt"></i> <p> Test Details</p> </a>
    

	<form method="post">
    	<button class="btn btn-sm btn-danger" name="btnLogout"><i class="uil uil-signout"></i> Logout</button>
	</form>	

		<?php
			//code for logout     
			if(isset($_POST['btnLogout'])){
				session_destroy();
				header('Location: index.php');
			}
		?>

</div>
<button class="openbtn" id="opensidebar" onclick="openNav()"><i class="uil uil-bars"></i></button>
<!--===== sidebar for admin ends =====-->

<div id="main"></div>      <!-- only for measuring the total width of the screen -->

<script>

	function openNav() {
   		document.getElementById("mySidebar").style.width = "200px";
		document.getElementById("main").style.marginLeft = "200px";
        document.getElementById("opensidebar").style.visibility = "hidden";
	}
  
  	function closeNav() {
    	document.getElementById("mySidebar").style.width = "0";
		document.getElementById("main").style.marginLeft= "0";
        document.getElementById("opensidebar").style.visibility = "visible";
		(function() {
  			window.onresize = displayWindowSize;
  			window.onload = displayWindowSize;

  			function displayWindowSize() {
    			myWidth = window.innerWidth;
    			myHeight = window.innerHeight;
    			// your size calculation code here
				var size=myWidth;
				if(size>618){
					document.getElementById("mySidebar").style.width = "200px";
					document.getElementById("main").style.marginLeft = "200px";
				}
  			};
		})();
	}

</script>

<?php
	}		// end of else if.

	else{
		header("Location: index.php");
	}		// end of else.
?>