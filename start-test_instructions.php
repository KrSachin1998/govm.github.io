<?php
session_start();
if($_SESSION["addNo"]!="" && $_SESSION["stuName"]!="" && $_SESSION["userType"]=="student")
{
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="assets/bootstrap4/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="assets/css/style-test-instruction.css"/>
        <link rel="stylesheet" href="assets/css/loader.css" />
    </head>

    <body>

<!--========= calling navbar starts ==========-->
<?php 
    include_once "header_oth.inc.php";
?>

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

    <section class="instructions">
        <div class="container box">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6 contain-instructions">
                    <div class="container instructions-box">
                        <p class="heading">
                            <h3>Please read the instructions carefully</h3>
                        </p>
                        <div class="instructions-list">

                            <h4>Steps For Accessing Your Exam Online</h4>
                            <ul>
                                <li>Close all programs, including email</li>
                                <li>Click on the Click here to open the exam link</li>
                                <li>Click "Log In For Your Exam Here" at the bottom of the screen.</li>
                                <li>Have your Proctor enter the Username and Password provided</li>
                                <li>To begin the exam, click on the link to the appropriate exam</li>
                                <li>Close all programs, including email</li>
                            </ul>

                            <h4>Before starting the exam:</h4>
                            <ul>
                                <li>Please verify that the student's last name appears correctly within the User ID box.</li>
                            </ul>

                            <h4>During the exam:</h4>
                            <ul>
                                <li>The student may not use his or her textbook, course notes, or receive help from a proctor or any other outside source.</li>
                                <li>Students must complete the 50-question multiple-choice exam within the 75-minute time frame allotted for the exam..</li>
                                <li>Students must not stop the session and then return to it. This is especially important in the online .</li>
                            </ul>
                                
                        </div>
                        <div class="contain-button">
                            <button class="btn btn-success" onclick="attendTest()" id="btnStartTest" disabled></button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </section>

<!--========= Calling the footer ==========-->
<?php 
    include_once "footer.inc.php";
?>

    </body>

    <script>
        // getting the value of test id from url..
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const testId = urlParams.get('t_id');   // getting the value of test id from url
        $(document).ready(function(){
            var flag="checkTestAvail";
            $.ajax({
                url:"call_service_loaddata.php",
                method:"POST",
                data:{
                    action:flag,
                    t_id:testId
                },
                dataType:"json",
                success:function(data){
                    var date = data.testDate;
                    var s_time = data.startTime.split(':');
                    var e_time = data.endTime.split(':');
                    var start_hr = s_time[0];
                    var start_min = s_time[1];
                    var start_sec = s_time[2];

                    var end_hr=e_time[0];
                    var end_min=e_time[1];
                    var end_sec=e_time[2];
                    var date = new Date();
                    var now_hr = date.getHours();
                    var now_min = date.getMinutes();
                    var now_sec = date.getSeconds();
                    
                    if(now_hr<start_hr){
                        $("#btnStartTest").html('Please Wait');
                        $("#container").attr('style', 'display:none');	// removing the loading circle
                    }
                    else if(now_hr==start_hr && now_min<start_min){
                        $("#btnStartTest").html('Please Wait');
                        $("#container").attr('style', 'display:none');	// removing the loading circle
                    }
                    else if(now_hr>=start_hr && now_hr<=end_hr){
                        
                        if(now_hr==end_hr){
                            if(now_min<=end_min){   // can give test
                                $("#btnStartTest").html('Start Test <i class="uil uil-caret-right"></i>'); 
                                $("#btnStartTest").removeAttr('disabled');
                                $("#container").attr('style', 'display:none');	// removing the loading circle
                            }
                            else{   // test ended
                                $("#btnStartTest").html('This Test Has Ended');
                                $("#container").attr('style', 'display:none');	// removing the loading circle
                            }
                        }
                        else if(now_hr==start_hr){
                            $("#btnStartTest").html('Start Test <i class="uil uil-caret-right"></i>'); 
                            $("#btnStartTest").removeAttr('disabled');
                            $("#container").attr('style', 'display:none');  // removing the loading circle
                        }

                    }
                    else{       
                        $("#btnStartTest").html('This Test Has Ended');
                        $("#container").attr('style', 'display:none');  // removing the loading circle
                    }

                }
            });
        });

        function attendTest(){
            var flag="testAttended";
            var test_id=testId;
            var stuId = parseInt($("#lblStuId").text());
            var stuClass = $("#lblStuClass").text();
            var stuSection = $("#lblStuSection").text();
            var stuStream = $("#lblStuStream").text();
            $.ajax({
                url:"call_service_loaddata.php",
                method:"POST",
                data:{
                    action:flag,
                    test_id:testId,
                    stu_id:stuId,
                    stu_class:stuClass,
                    stu_sec:stuSection,
                    stu_str:stuStream
                },
                dataType:"json",
                success:function(data){
                    if(data=="1"){          // student already attended the test
                        alert("You already attended this test");
                        return false;
                    }
                    else if(data=="0"){
                        window.location.href = "attend_test.php?t_id="+testId;  // passing the test id to the attend_test.php
                    }
                    else{
                        alert("something went wrong, try again later");
                        return false;
                    }
                }
            });

        }

    </script>

</html>

<?php    
}
else{
    header("Location: index.php");
}
?>