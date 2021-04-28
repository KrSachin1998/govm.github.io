<?php
session_start();   // before using the session varibale we have to start the session
    if($_SESSION["addNo"]!="" && $_SESSION["stuName"]!="" && $_SESSION["userType"]=="student" ){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css"/>    <!--Icons-->
    <link href="assets/bootstrap4/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/style-student-dashboard.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/loader.css" />
</head>
<body>

    <!--========= calling navbar and sidebar start ==========-->
    <?php
        include_once "header_oth.inc.php";
        include_once "sidebar_stu.inc.php";
    ?>
    <!--========= calling navbar and sidebar end ==========-->

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

    <section class="contain-dashboard">

        <hr>

        <div class="contain-tables row">

            <div class="col-sm-12">
                <h5>Upcoming Tests</h5>
                
                 <!-- table to bind upcoming test starts -->
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
                            <td>No test yet</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-sm-12">
                <h5>Completed Tests</h5>
                
                 <!-- table to bind completed test starts -->
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

                    <tbody id="tblBodyCompleteTest">
                        <tr>
                            <td>No test yet</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-sm-12">
                <h5>Attended Tests</h5>
                
                 <!-- table to bind completed test starts -->
                <table class="table table-striped">

                    <thead>
                        <tr>
                            <th>Test Title</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Result</th>
                        </tr>
                    </thead>

                    <tbody id="tblBodyAttendedTest">
                        <tr>
                            <td>No test yet</td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>

    </section>
</body>

<script>
    
    $(document).ready(function(){
        upcomingTest();     // it will show the upcoming test 
        completedTest();    // it will show the test already passed
        attendedTest()      // it will show the test attended by the student
        $("#container").attr('style', 'display:none');	// removing the loading circle
    });

    function getdate(){          // function to return date in format (yyyy-mm-dd)
        var date = new Date();
        var year = date.getFullYear().toString();
        var month = date.getMonth();
        var original_month = (month+1).toString();
        var day = (date).getDate().toString();
        var ele = document.getElementById("showDate");
        var form_date ="";
        if(original_month.length < 2 && day.length == 2 ){
            form_date = year +'-'+ '0'+original_month +'-'+ day;
        }
        else if(day.length < 2 &&  original_month.length == 2){
            form_date = year +'-'+ original_month +'-'+  '0'+day;
        }
        else if(day.length < 2 && original_month.length < 2){
            form_date = year +'-'+ '0'+original_month +'-'+  '0'+day;
        }
        else{
            form_date = year +'-'+ original_month +'-'+ day; 
        }
        return form_date;
    }
    
    
    function enableDiableButton(t_id,t_date,t_stime,t_etime){
        /*var todayDate = getdate();

        if(todayDate == testDate){  // date is matching
            var date = new Date();
            var hour = date.getHours();
            var min = date.getMinutes();
            var sec = date.getSeconds();
            var current = hour+':'+min+':'+sec;
            if(Date.parse(current) > Date.parse(t_stime) && Date.parse(current) > Date.parse(t_stime)  ){
                console.log("current is greator");
            }
            else{
                console.log("current is smaller");
            }
            console.log(current);
            console.log(t_stime);
        }
        else{
            console.log("date is not matching");
        }*/
    }

    function upcomingTest(){
        var flag = "upComingTest";
        var stuClass = $("#lblStuClass").text();
        var stuSection = $("#lblStuSection").text();
        var stuStream = $("#lblStuStream").text();
        $.ajax({
            url:"call_service_loaddata.php",
            method:"POST",
            data:{
                action:flag,
                classId:stuClass,
                sectionId:stuSection,
                streamId:stuStream
            },
            dataType:"json",
            success:function(data){
                $("#tblBodyUpcomingTest").empty();
                $.each(data, function(i, item){
                    $("#tblBodyUpcomingTest").append('<tr> <td>'+data[i].testTitle+'</td><td>'+data[i].scheduleDate+'</td><td>'+data[i].startTime+'</td><td>'+data[i].endTime+'</td> <td> <input type="submit" id='+data[i].testId+' class="btn btn-sm btn-success" onclick="attendTest('+data[i].testId+')" value="Attend" disabled/> </td> </tr>');

                    var testDate = data[i].scheduleDate.split('-');
                    var test_day = testDate[2];
                    var test_mon = testDate[1];
                    var test_year = testDate[0];

                    var date = new Date();
                    var today_day=(date).getDate();
                    var today_mon=date.getMonth()+1;    // since month starts from 0 - 11
                    var today_year=date.getFullYear();
                    var btnId = '#'+data[i].testId;

                    if(test_day==today_day && test_mon==today_mon && test_year==today_year){
                        $(btnId).removeAttr("disabled");
                    }
                    

                });
                
                enableDiableButton(data.testId,data.scheduleDate,data.startTime,data.endTime);
            }
        });
    }

    function completedTest(){
        var flag = "completedTest";
        var stuClass = $("#lblStuClass").text();
        var stuSection = $("#lblStuSection").text();
        var stuStream = $("#lblStuStream").text();
        $.ajax({
            url:"call_service_loaddata.php",
            method:"POST",
            data:{
                action:flag,
                classId:stuClass,
                sectionId:stuSection,
                streamId:stuStream
            },
            dataType:"json",
            success:function(data){
                $("#tblBodyCompleteTest").empty();
                $.each(data, function(i, item){
                    $("#tblBodyCompleteTest").append('<tr> <td>'+data[i].testTitle+'</td><td>'+data[i].scheduleDate+'</td><td>'+data[i].startTime+'</td><td>'+data[i].endTime+'</td></tr>');

                });
                
            }
        });
    }

    function attendedTest(){
        var flag="alreadyAttended";
        var stuId = parseInt($("#lblStuId").text());
        var stuClass = $("#lblStuClass").text();
        var stuSection = $("#lblStuSection").text();
        var stuStream = $("#lblStuStream").text();
        $.ajax({
            url:"call_service_loaddata.php",
            method:"POST",
            data:{
                action:flag,
                stu_id:stuId,
                stu_class:stuClass,
                stu_sec:stuSection,
                stu_stream:stuStream
            },
            dataType:"json",
            success:function(data){
                $("#tblBodyAttendedTest").empty();
                $.each(data, function(i, item){
                    $("#tblBodyAttendedTest").append('<tr> <td>'+data[i].testTitle+'</td><td>'+data[i].testDate+'</td><td>'+data[i].startTime+'</td><td>'+data[i].endTime+'</td> <td> <label id='+"lblShowResult"+data[i].testId+' class="lblShowTestMarks" style="color:blue;cursor:pointer;font-size:15px;" onclick="viewResult('+data[i].testId+')">Result</label> </td> </tr>');
                });
            }
        });
    }

    function attendTest(get){
        var test_id = get;
        /*var stuName = $("#lblStuName").text();  // getting value from hidden field (present in header_oth.inc.php)..
        var stuClass = $("#lblStuClass").text();
        var stuSection = $("#lblStuSection").text();
        var stuStream = $("#lblStuStream").text();
        var flag="testAttended";*/
        window.location.href = "start-test_instructions.php?t_id="+test_id;
    }

    function viewResult(get){
        var testId=get;
        var stuId = parseInt($("#lblStuId").text());
        var flag="testResult";
        var lblId = '#lblShowResult'+get;
        $.ajax({
            url:"call_service_loaddata.php",
            method:"POST",
            data:{
                action:flag,
                stu_id:stuId,
                test_id:testId
            },
            dataType:"json",
            success:function(data){
                $(lblId).text(data.scoredMarks+' / '+data.totMarks);
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

