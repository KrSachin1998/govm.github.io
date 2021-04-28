<?php
session_start();   // before using the session varibale we have to start the session
    if(($_SESSION["userType"]="teacher" || $_SESSION["userType"]="admin") && ($_SESSION["adminId"]!="" || $_SESSION["teachId"]!=""))
	{
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add Questions</title>
        <link href="assets/css/style-show-occuring-test.css" rel="stylesheet"/>
    </head>
    <body>
        <!--========= calling navbar and sidebar ==========-->
        <?php
            include_once "header.inc.php";
            include_once "sidebar.inc.php";
        ?>
        <section class="test-occuring" id="testOccuringDetails">
            <!-- Showing details of test start -->
            <div class="contain-test-details">
                <h4>Dashboard/Occuring Test</h4>
                <h5 class="containTestId">
                    <label class="lblTestId">Test ID:</label>
                    <label class="lblBindTestId" id="lblBindTestId"> <?php echo $_GET["t_id"]; ?> </label>        <!-- will be from database -->
                </h5>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>Test title:</label>
                        <label id="lblShowTestTitle"></label>
                    </div>
                    <div class="col-sm-6">
                        <label>Test Date:</label>
                        <label id="lblTestDate"></label>
                    </div>   
                </div>

                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>Test Start:</label>
                        <label id="lblTestStartTime"></label>
                    </div>
                    <div class="col-sm-6">
                        <label>For Class:</label>
                        <label id="lblTestForClass"></label>
                    </div>   
                </div>

                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>For Section:</label>
                        <label id="lblTestForSection"></label>
                    </div>
                    <div class="col-sm-6">
                        <label>For Stream:</label>
                        <label id="lblTestForStream"></label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>Test End:</label>
                        <label id="lblTestEndTime"></label>
                    </div>
                    <div class="col-sm-6">
                        <label>Test Subject:</label>
                        <label id="lblTestSubject"></label>
                    </div>
                </div>

            </div>
            <!-- Showing details of test ends -->

            <br>
            <!-- Show students attending test start -->
            <h6>Students Attended This Test</h6>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Stream</th>
                        <th>Phone No</th>
                    </tr>
                </thead>
                <tbody id="tblStuAttendingTest">
                    <tr>
                        <td>No data</td>
                    </tr>
                </tbody>
            </table>

            <!-- Show students attending test ends -->

            <hr>
        </section>

    </body>

    <script>
        // getting the value of test id from url..
        const queryString = window.location.search;
	    const urlParams = new URLSearchParams(queryString);
	    const testId = urlParams.get('t_id');				// getting test id from url (global variable)
            
        $(document).ready(function(){   
            loadTestDetails();
            stuAttenTest();
        });

        // this function will load test details like test_class, test_section etc on page load.
        function loadTestDetails(){
            var testId = $("#lblBindTestId").text();        // getting test id
            var createdBy = $("#lblUserUnid").text();       // getting user unique id
            var flag = "loadTestDetails";
            $.ajax({
                url: "call_service_loaddata.php",
                method:"POST",
                data: {
                    action: flag,
                    test_id: testId,
                    created_by: createdBy
                },
                dataType:"json",
                success:function(data){
                    $("#lblShowTestTitle").text(data.testTitle);
                    $("#lblTestDate").text(data.testDate);
                    $("#lblTestStartTime").text(data.testStartTime);
                    $("#lblTestForClass").text(data.forClass);
                    $("#lblTestForSection").text(data.forSection);
                    $("#lblTestForStream").text(data.forStream);
                    $("#lblTestEndTime").text(data.testEndTime);
                    $("#lblTestSubject").text(data.testSubject);
                }
            });
        }

        function stuAttenTest(){
            var flag="stuAttenTest";
            var test_id=testId;
            var adminId=$("#lblUserUnid").text();
            var count=1
            $.ajax({
                url:"call_service_loaddata.php",
                method:"POST",
                data:{
                    action:flag,
                    t_id:test_id,
                    creator:adminId
                },
                dataType:"json",
                success:function(data){
                    $("#tblStuAttendingTest").empty();
                    $.each(data, function(i, item){
                        $("#tblStuAttendingTest").append("<tr> <td>"+count+"</td> <td>"+ data[i].stuId +"</td> <td>"+ data[i].stuName +"</td> <td>"+ data[i].classId +"</td> <td>"+ data[i].sectionId +"</td> <td>"+ data[i].streamId +"</td> <td>"+ data[i].phoneNo +"</td> </tr>");
                        count++;
                    });
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