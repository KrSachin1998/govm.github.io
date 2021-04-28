<?php
session_start();   // before using the session varibale we have to start the session
if(isset($_SESSION["adminId"])){
    if($_SESSION["userType"]="admin"){  
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

            <div class="col-xl-3 col-sm-6 mb-3" onclick="document.location='add_teacher.php'">
                <div class="card text-white bg-danger o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="uil uil-users-alt"></i>
                        </div>
                        <div class="mr-5">Add Teacher</div>
                    </div>
                    <a class="card-footer text-white" href="#">
                        <span class="footer-text">View Details</span>
                        <span class="footer-arrow float-right">
                            <i class="uil uil-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>

        </div>

        <hr>

        <div class="contain-tables row">

            <div class="col-sm-6">
                <h5>Upcoming Tests</h5>
                
                 <!-- table to bind registered students starts -->
                <table class="table table-striped">

                    <thead>
                        <tr>
                            <th>Test Title</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tblBodyUpcomingTest">
                        <tr>
                            <td>No data Present</td>
                        </tr>
                        
                    </tbody>
                </table>

            </div>

            <div class="col-sm-6">
                <h5>Completed Tests</h5>
                
                 <!-- table to bind registered students starts -->
                <table class="table table-striped">

                    <thead>
                        <tr>
                            <th>Test Title</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tblBodyCompletedTest">
                        <tr>
                            <td>
                                No data Present
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>

    </section>
</body>

<script>
$(document).ready(function(){
    loadUpcomingTest();
    loadCompletedTest();
});

function loadUpcomingTest(){
    var flag = "upcomingTest";
    var adminId = $("#lblUserUnid").text();
    $.ajax({
        url:"call_service_loaddata.php",
        method:"POST",
        data:{
            action:flag,
            user_id:adminId
        },
        dataType:"json",
        success:function(data){
            $("#tblBodyUpcomingTest").empty();

            $.each(data, function(i, item) {
                $('#tblBodyUpcomingTest').append('<tr>'+
                        '<td>'+ data[i].testTitle +'</td> <td>'+ data[i].scheduleDate +'</td> <td>'+ data[i].startTime +'</td> <td>'+ data[i].endTime +'</td> <td> <input type="submit" id="'+data[i].testId+'" value="Details" class="btn btn-sm btn-success" onclick="loadThisTest(this);"/> </td>'
                        +'</tr>');
            });

        }
    });
}

function loadCompletedTest(){
    var flag = "creatorCompTest";
    var adminId = $("#lblUserUnid").text();
    $.ajax({
        url:"call_service_loaddata.php",
        method:"POST",
        data:{
            action:flag,
            user_id:adminId
        },
        dataType:"json",
        success:function(data){
            $("#tblBodyCompletedTest").empty();

            $.each(data, function(i, item) {
                $('#tblBodyCompletedTest').append('<tr>'+
                        '<td>'+ data[i].testTitle +'</td> <td>'+ data[i].scheduleDate +'</td> <td>'+ data[i].startTime +'</td> <td>'+ data[i].endTime +'</td> <td> <input type="submit" id="'+data[i].testId+'" value="Details" class="btn btn-sm btn-success" onclick="loadThisTest(this);"/> </td>'
                        +'</tr>');
            });

        }
    });
}

function loadThisTest(get){
    var testId = get.id;
    window.location.href="showOccuringTest.php?t_id="+testId;
}

</script>

</html>

<?php } 
}
    else
    {
        header("Location: index.php");
    }   
?>


