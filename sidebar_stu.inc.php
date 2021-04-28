
<link href="assets/css/style-sidebar.css" rel="stylesheet"/>

<?php
	if($_SESSION["userType"]=="student" && $_SESSION["addNo"]!="" && $_SESSION["stuName"]!="" ){		// this sidebar is only for teachers
?>

<!--========= student sidebar starts =========-->
<div class="sidebar" id="mySidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="uil uil-arrow-left"></i></a>
    <a href="student_dashboard.php"><i class="uil uil-estate"></i> <p>Home</p></a>
	<a href="profile_student.php" id="userProfile"><i class="uil uil-user-circle"></i> <p>Profile</p></a>
    <a href="#"><i class="uil uil-clipboard"></i> <p>My Test</p></a>
	<a href="#"><i class="uil uil-file-info-alt"></i> <p>Academics</p></a>
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
<!--===== student sidebar ends =====-->

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

	else{
		header("Location: index.php");
	}

?>