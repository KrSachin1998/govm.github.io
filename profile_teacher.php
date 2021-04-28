<?php
session_start();   // before using the session varibale we have to start the session
    if($_SESSION["teachId"]!="" || $_SESSION["teachName"]!="" || $_SESSION["userType"]=="teacher" )
	{

    include_once "header.inc.php";
    include_once "sidebar.inc.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<link rel="stylesheet" href="assets/bootstrap4/css/bootstrap_v3.min.css">
	<link rel="stylesheet" href="assets/css/style-profile.css" />
    
	<title></title>
</head>

<body>
	<div class="container bootstrap snippet contain-tabs">

		<div class="row">       <!-- Row 1 -->
			<div class="col-sm-10">
				<h3>User name</h3> 
            </div>
		</div>

		<div class="row">       <!-- Row 2 -->
			<div class="col-sm-9">

				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#home">Personal Details</a></li>
					<li><a data-toggle="tab" href="#academic">Academic Details</a></li>
					<!-- <li><a data-toggle="tab" href="#settings">Menu 2</a></li> -->
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
									<label><h4>Father's Name</h4></label>
									<input type="text" class="form-control" id="txtFatherName" placeholder="Father's Name"> 
                                </div>
							</div>

							<div class="form-group">
								<div class="col-xs-6">
									<label><h4>Mother's Name</h4></label>
									<input type="text" class="form-control" id="txtMotherName" placeholder="Mother's Name">
                                 </div>
							</div>

                            <div class="form-group">
								<div class="col-xs-6">
									<label><h4>Parent's Mobile No</h4></label>
									<input type="text" class="form-control" id="txtParentMobileNumber" placeholder="Parent's Mobile No">
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
									<label for="password2">
										<h4>Category</h4></label>
									<input type="password" class="form-control" id="txtCategory" placeholder="(Gen, OBC, SC, ST)"/> 
                                </div>
							</div>

                            <div class="form-group">
								<div class="col-xs-6">
									<label><h4>Date of Joining</h4></label>
									<input type="date" class="form-control" id="txtDateOfJoining"/> 
                                </div>
							</div>

                            <div class="form-group">
								<div class="col-xs-6">
									<label><h4>Date of Birth</h4></label>
									<input type="date" class="form-control" id="txtDateOfBirth"/> 
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

					<!--/tab-pane-->
					<div class="tab-pane" id="academic">
					
						<div class="form-group">
							<div class="col-xs-6">
								<label><h4>Class</h4></label>
                                <select class="form-control" id="ddlClass">
                                    <option value="0">Select Class</option>
                                </select>
                            </div>
						</div>

						<div class="form-group">
							<div class="col-xs-6">
								<label><h4>Section</h4></label>
                                <select class="form-control" id="ddlSection" >
                                    <option value="0">Select Section</option>
                                </select>
                            </div>
						</div>

						<div class="form-group">
							<div class="col-xs-6">
								<label for="phone"><h4>Phone</h4></label>
								<input type="text" class="form-control" id="phone" placeholder="enter phone" />
                            </div>
						</div>

					</div>
                    <!--/tab-pane-->

				</div>  <!--/tab-content-->
				
                <div class="form-group">
                    <div class="col-xs-12">
                        <br>
                        <button class="btn btn-md btn-success" type="submit"><i class="uil uil-check-square"></i> Save</button>
                        <button class="btn btn-md" type="reset"><i class="uil uil-repeat"></i> Reset</button>
                    </div>
                </div>

			</div><!--/col-9-->
			
		</div>  <!--/row 2 -->
		
	</div>  <!--/bootstrap snippet-->
	
    <br>

</body>

</html>

<?php
	}
	else{
		header("Location: index.php");
	}
?>