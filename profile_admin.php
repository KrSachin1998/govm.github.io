<?php
session_start();   // before using the session varibale we have to start the session
    if($_SESSION["adminId"]!="" || $_SESSION["adminName"]!="" || $_SESSION["userType"]=="admin" )
	{

    include_once "header.inc.php";
    include_once "sidebar.inc.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<link rel="stylesheet" href="assets/bootstrap4/css/bootstrap_v3.min.css">
	<link rel="stylesheet" href="assets/css/style-profile.css" />
    <link rel="stylesheet" href="assets/css/loader.css" />
	<title></title>
</head>

<body>

	<!--for display loading-->
	<div id="container">
        <svg class="loader" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340 340">
            <circle cx="170" cy="170" r="160" stroke="#E2007C" />
            <circle cx="170" cy="170" r="135" stroke="#404041" />
            <circle cx="170" cy="170" r="110" stroke="#E2007C" />
            <circle cx="170" cy="170" r="85" stroke="#404041" />
        </svg>
    </div>
    <!--====================-->

	<div class="container bootstrap snippet contain-tabs">

		<div class="row">       <!-- Row 1 -->
			<div class="col-sm-10">
				<h3>Admin name</h3> 
            </div>
		</div>

		<div class="row">       <!-- Row 2 -->
			<div class="col-sm-9">

				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#home">Personal Details</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="home">
						<form class="form" action="##" method="post" id="registrationForm">

							<div class="form-group">
								<div class="col-xs-6">
									<label><h4>Full Name</h4></label>
									<input type="text" class="form-control" id="txtFullName" placeholder="Full Name">
                                </div>
							</div>

							<div class="form-group">
								<div class="col-xs-6">
									<label><h4>Mobile No</h4></label>
									<input type="text" class="form-control" id="txtMobileNumber" placeholder="Mobile Number">
                                 </div>
							</div>

							<div class="form-group">
								<div class="col-xs-6">
									<label><h4>Email</h4></label>
									<input type="email" class="form-control" id="txtEmail" placeholder="Your Email">
                                </div>
							</div>

							<div class="form-group">
								<div class="col-xs-6">
									<label><h4>Address</h4></label>
									<input type="text" class="form-control" id="txtAddress" placeholder="Address"> 
                                </div>
							</div>

							<div class="form-group">
								<div class="col-xs-6">
									<label><h4>Caste</h4></label>
									<input type="text" class="form-control" id="txtCaste" placeholder="Hindu, Muslim, Sikh etc"/>
                                 </div>
							</div>

							<div class="form-group">
								<div class="col-xs-6">
									<label for="txtCategory">
										<h4>Category</h4></label>
									<input type="text" class="form-control" id="txtCategory" placeholder="(Gen, OBC, SC, ST)"/> 
                                </div>
							</div>

                            <div class="form-group">
								<div class="col-xs-6">
									<label><h4>Gender</h4></label>
									<input type="text" class="form-control" id="txtGender" placeholder="Male, Female, Other"/> 
                                </div>
							</div>

						</form>
						<hr> 
                    </div>

				</div>  <!--/tab-content-->
				
                <div class="form-group">
                    <div class="col-xs-12">
                        <br>
                        <button class="btn btn-md btn-success" onclick="updateDetails()" type="submit"><i class="uil uil-check-square"></i> Save</button>
                        <button class="btn btn-md" type="reset"><i class="uil uil-repeat"></i> Reset</button>
                    </div>
                </div>

			</div><!--/col-9-->
			
		</div>  <!--/row 2 -->
		
	</div>
	
    <br>

</body>

<script>
	$(document).ready(function(){
		loadAdminProfile();
		$("#container").attr('style', 'display:none');	// removing the loading circle
	});

	function clearAllBorder(){
        $("#txtFullName").css("border", "1px solid #ccc");
		$("#txtMobileNumber").css("border", "1px solid #ccc");
		$("#txtEmail").css("border", "1px solid #ccc");
		$("#txtAddress").css("border", "1px solid #ccc");
		$("#txtCaste").css("border", "1px solid #ccc");
		$("#txtCategory").css("border", "1px solid #ccc");
		$("#txtGender").css("border", "1px solid #ccc");
	}

	function loadAdminProfile(){
		var adminId = $("#lblUserUnid").text();
		var flag = "loadAdminProfile";
		$.ajax({
			url:"call_service.php",
			method:"POST",
			data:{
				action:flag,
				admin_id:adminId
			},
			dataType:"json",
			success:function(data){
				$("#txtFullName").val(data.adminName);
				$("#txtMobileNumber").val(data.adminMobile);
				$("#txtEmail").val(data.adminEmail);
				$("#txtAddress").val(data.adminAddress);
				$("#txtCaste").val(data.adminCaste);
				$("#txtCategory").val(data.adminCate);
				$("#txtGender").val(data.adminGender);
			}
		});
	}

	function updateDetails(){
		var adminId = $("#lblUserUnid").text();
		var name = $("#txtFullName").val();
		var mobile = $("#txtMobileNumber").val();
		var email = $("#txtEmail").val();
		var address = $("#txtAddress").val();
		var caste = $("#txtCaste").val();
		var category = $("#txtCategory").val();
		var gender = $("#txtGender").val();
		var flag = "updateAdminDetails";
		if(name.trim()==""){
			clearAllBorder();
			$("#txtFullName").attr("placeholder","* Enter Name");
        	$("#txtFullName").css("border", "1px solid red");
        	return false;
		}
		/*else if(mobile.trim()==""){
			clearAllBorder();
			$("#txtMobileNumber").attr("placeholder","* Enter Number");
        	$("#txtMobileNumber").css("border", "1px solid red");
        	return false;
		}
		else if(email.trim()==""){
			clearAllBorder();
			$("#txtEmail").attr("placeholder","* Enter Email");
        	$("#txtEmail").css("border", "1px solid red");
		}
		else if(address.trim()==""){
			clearAllBorder();
			$("#txtAddress").attr("placeholder","* Enter Address");
        	$("#txtAddress").css("border", "1px solid red");
		}
		else if(caste.trim()==""){
			clearAllBorder();
			$("#txtCaste").attr("placeholder","* Enter Caste");
        	$("#txtCaste").css("border", "1px solid red");
		}
		else if(category.trim()==""){
			clearAllBorder();
			$("#txtCategory").attr("placeholder","* Enter Category");
        	$("#txtCategory").css("border", "1px solid red");
		}
		else if(gender.trim()==""){
			clearAllBorder();
			$("#txtGender").attr("placeholder","* Enter Gender");
        	$("#txtGender").css("border", "1px solid red");
		}*/
		else{
			clearAllBorder();
			$.ajax({
				url:"call_service.php",
				method:"POST",
				data:{
					action:flag,
					admin_id:adminId,
					admin_name:name,
					admin_mob:mobile,
					admin_email:email,
					admin_add:address,
					admin_caste:caste,
					admin_category:category,
					admin_gen:gender
				},
				dataType:"json",
				success:function(data){
					if(data=="11"){			// 11 represents server side validation (error)
						alert("Please fill all the data");
						return false;
					}
					else if(data=="1"){
						alert("Updated Successfully");
						loadAdminProfile();
						return false;
					}
					else{
						alert("Something Went Wrong");
						return false;
					}
				}
			});
		}
	}

</script>

</html>

<?php
	}
	else{
		header("Location: index.php");
	}
?>