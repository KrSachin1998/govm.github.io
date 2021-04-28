<?php
session_start();   // before using the session varibale we have to start the session
    if($_SESSION["teachId"]!="" && $_SESSION["teachName"]!="" && $_SESSION["userType"]=="teacher" ){  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css"/>    <!--Icons-->
    <link href="assets/bootstrap4/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/style-admin-dashboard.css" rel="stylesheet" />
</head>
<body>

    <!--========= calling navbar and sidebar start ==========-->
    <?php
        include_once "header.inc.php";
        include_once "sidebar.inc.php";
    ?>
    <!--========= calling navbar and sidebar end ==========-->

    <section class="contain-dashboard">
        <div class="row">

            <div class="col-xl-3 col-sm-6 mb-3" onclick="document.location='index.php'">
                <div class="card text-white bg-primary o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="uil uil-user-md"></i>
                        </div>
                        <div class="mr-5"><label id="lblTotalStudents">1250</label> Students</div>
                    </div>
                    <a class="card-footer text-white" href="#">
                        <span class="footer-text">View Details</span>
                        <span class="footer-arrow float-right">
                            <i class="uil uil-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3" onclick="document.location='add_student.php'">
                <div class="card text-white bg-warning o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="uil uil-user-plus"></i>
                        </div>
                        <div class="mr-5">Add Student</div>
                    </div>
                    <a class="card-footer text-white" href="#">
                        <span class="footer-text">View Details</span>
                        <span class="footer-arrow float-right">
                            <i class="uil uil-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3" onclick="document.location='add_test.php'">
                <div class="card text-white bg-success o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="uil uil-clipboard-notes"></i>
                        </div>
                        <div class="mr-5">Create Test</div>
                    </div>
                    <a class="card-footer text-white" href="#">
                        <span class="footer-text">View Details</span>
                        <span class="footer-arrow float-right">
                            <i class="uil uil-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>

            <!--<div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-danger o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="uil uil-life-ring"></i>
                        </div>
                        <div class="mr-5">26 New Messages!</div>
                    </div>
                    <a class="card-footer text-white" href="#">
                        <span class="footer-text">View Details</span>
                        <span class="footer-arrow float-right">
                            <i class="uil uil-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>-->

        </div>

        <hr>

        <div class="contain-tables row">

            <div class="col-sm-6">
                <h5>Completed Tests</h5>
                
                 <!-- table to bind registered students starts -->
                <table class="table table-striped">

                    <thead>
                        <tr>
                            <th>Test Title</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Data structure and web programming</td>
                            <td>
                                <input type="submit" class="btn btn-success btn-sm" value="Details"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Java Programming</td>
                            <td>
                                <input type="submit" class="btn btn-success btn-sm" value="Details"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Python programming</td>
                            <td>
                                <input type="submit" class="btn btn-success btn-sm" value="Details"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Data structure</td>
                            <td>
                                <input type="submit" class="btn btn-success btn-sm" value="Details"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Internet and web programming</td>
                            <td>
                                <input type="submit" class="btn btn-success btn-sm" value="Details"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Soft Skills</td>
                            <td>
                                <input type="submit" class="btn btn-success btn-sm" value="Details"/>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-sm-6">
                <h5>Upcoming Tests</h5>
                
                 <!-- table to bind registered students starts -->
                <table class="table table-striped">

                    <thead>
                        <tr>
                            <th>Test Title</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Statistics</td>
                            <td>
                                <input type="submit" class="btn btn-success btn-sm" value="Details"/>
                            </td>
                        </tr>
                        <tr>
                            <td>C++ Programming</td>
                            <td>
                                <input type="submit" class="btn btn-success btn-sm" value="Details"/>
                            </td>
                        </tr>
                        <tr>
                            <td>HTML and CSS</td>
                            <td>
                                <input type="submit" class="btn btn-success btn-sm" value="Details"/>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>

            </div>

        </div>

    </section>
</body>
</html>

<?php } 
    else
    {
        header("Location: index.php");
    }   
?>


