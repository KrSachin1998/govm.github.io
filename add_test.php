<?php
session_start();   // before using the session varibale we have to start the session
if(isset($_SESSION["adminId"]) || isset($_SESSION["teachId"]) ){
    if( ($_SESSION["userType"]="teacher" || $_SESSION["userType"]="admin") ){

    include_once "header.inc.php";
    include_once "sidebar.inc.php";
    include_once "modal_update_test.php";       // modal to update the test details..
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Create Test</title>
        <link href="assets/css/style-add-test.css" rel="stylesheet"/>
    </head>
    <body>
        <section class="add-test" id="addTestForm">
            <h4>Dashboard/Create Test</h4>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label for="txtTestTitle">Test title:</label>
                    <input type="text" id="txtTestTitle" class="form-control" placeholder="Test Title"/>
                </div>
                <div class="col-sm-6">
                    <label for="txtTestDate">Test Date:</label>
                    <input type="date" id="txtTestDate" class="form-control" />
                </div>   
            </div>

            <div class="form-group row">

                <div class="col-sm-6">
                    <label for="ddlForClass">For Class:</label>
                    <select class="form-control" id="ddlForClass" onchange="loadSubject()">
                        <option value="0">Select Class</option>
                    </select>
                </div>   

                <div class="col-sm-6">
                    <label for="ddlForSection">For Section:</label>
                    <select class="form-control" id="ddlForSection">
                        <option value="0">Select Section</option>
                    </select>
                </div>

            </div>

            <div class="form-group row">

                <div class="col-sm-6">
                    <label for="txtTestStartTime">Start Time:</label>
                    <input type="time" id="txtTestStartTime" class="form-control" />
                </div>

                <div class="col-sm-6">
                    <label for="txtTestEndTime">End Time:</label>
                    <input type="time" id="txtTestEndTime" class="form-control" />
                </div>

            </div>

            <div class="form-group row">

                <div class="col-sm-6">
                    <label for="ddlStream">Stream:</label>
                    <select class="form-control" id="ddlStream">
                        <option value="0">Select Stream</option>
                    </select>
                </div>

                <div class="col-sm-6">
                    <label for="ddlSubject">Subject:</label>
                    <select class="form-control" id="ddlSubject">
                        <option value="0">Select Class First</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="submit" class="btn btn-sm btn-success" onclick="createTest()" value="Save and Continue"/>
                </div>
            </div>

            <div class="table-responsive">
            <h5>Test created By You</h5>
                <table id="tblCreatedTest" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Test Id</th>
                            <th>Test Title</th>
                            <th>For Class</th>
                            <th>For Section</th>
                            <th>Subject</th>
                            <th>For Stream</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Assign</th>
                        </tr>
                    </thead>
                    <tbody id="tblCreatedTestBody">
                        
                    </tbody>
                </table>
            </div>

        </section>

    </body>

    <script src="assets/js/jquery.js"></script>
    <script>
        $(document).ready(function(){
            loadClass();
            loadSection();
            loadStream();
            loadAllTest();
        });

        // binding classes start
        function loadClass(){
            var flag = "loadClass";
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data: {
                    action:flag
                },
                dataType:"json",
                success:function(data){
                    $.each(data, function(i, item) {
                        $('#ddlForClass').append('<option value="' + data[i].classId +
                            '">' + data[i].className + '</option>');
                    });

                    $.each(data, function(i, item) {
                        $('#ddlEditTestClass').append('<option value="' + data[i].classId +
                            '">' + data[i].className + '</option>');
                    });

                }
            });
        }
        // binding classes end

        // binding section start
        function loadSection(){
            var flag = "loadSection";
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data: {
                    action:flag
                },
                dataType:"json",
                success:function(data){
                    $.each(data, function(i, item) {
                        $('#ddlForSection').append('<option value="' + data[i].sectionId +
                            '">' + data[i].sectionName + '</option>');
                    });

                    $.each(data, function(i, item) {
                        $('#ddlEditTestSection').append('<option value="' + data[i].sectionId +
                            '">' + data[i].sectionName + '</option>');
                    });

                }
            });
        }
        // binding section end

        function loadStream(){
            var flag = "loadstream";
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data: {
                    action:flag
                },
                dataType:"json",
                success:function(data){
                    $.each(data, function(i, item) {
                        $('#ddlStream').append('<option value="' + data[i].streamId +
                            '">' + data[i].streamName + '</option>');
                    });

                    $.each(data, function(i, item) {
                        $('#ddlEditTestStream').append('<option value="' + data[i].streamId +
                            '">' + data[i].streamName + '</option>');
                    });

                }
            });
        }

        function resetAll(){
            $("#txtTestTitle").val("");
            $("#txtTestDate").val("");
            $("#txtTestEndTime").val("");
            $("#ddlForClass").prop("selectedIndex",0);
            $("#ddlForSection").prop("selectedIndex",0);
            $("#txtTestStartTime").val("");
            $("#ddlSubject").prop("selectedIndex",0);
            $("#ddlStream").prop("selectedIndex",0);
        }

        function loadSubject(){
            var flag = "getsubject";
            var class_id = $("#ddlForClass").val();
            $.ajax({
                url:"call_service_loaddata.php",
                method:"POST",
                data: {
                    action:flag,
                    class:class_id
                },
                dataType:"json",
                success:function(data){
                    $("#ddlSubject").empty();
                    $.each(data, function(i, item) {
                        $('#ddlSubject').append('<option value="' + data[i].subId +
                            '">' + data[i].subName + '</option>');
                    });
                }
            });
        }

        function loadSubjectToEdit(){
            var flag = "getsubject";
            var class_id = $("#ddlEditTestClass").val();
            $.ajax({
                url:"call_service_loaddata.php",
                method:"POST",
                data: {
                    action:flag,
                    class:class_id
                },
                dataType:"json",
                success:function(data){
                    $("#ddlEditTestSubject").empty();
                    $.each(data, function(i, item) {
                        $('#ddlEditTestSubject').append('<option value="' + data[i].subId +
                            '">' + data[i].subName + '</option>');
                    });
                }
            });
        }

        function loadAllTest(){
            var created_by = $("#lblUserUnid").text();
            var flag = "loadAllTest";
            $.ajax({
                url:"call_service_loaddata.php",
                method:"POST",
                data:{
                    action: flag,
                    creator: created_by
                },
                dataType:"json",
                success:function(data){
                    $("#tblCreatedTestBody").empty();
                    
                    $.each(data, function(i, item) {
                        //alert(data[i].testDate);
                        $('#tblCreatedTestBody').append('<tr>'+
                            '<td>'+ data[i].testId +'</td> <td>'+ data[i].testTitle +'</td> <td>'+ data[i].class +'</td> <td>'+ data[i].section +'</td> <td>'+ data[i].subject +'</td> <td>'+ data[i].stream +'</td> <td>'+ data[i].testDate +'</td> <td>'+ data[i].t_start_time +'</td> <td>'+ data[i].t_end_time +'</td> <td> <input type="submit" id="'+data[i].testId+'" value="Edit" class="btn btn-sm btn-primary" onclick="loadTestForEdit(this);"/> <input type="submit" id="'+data[i].testId+'" value="Setup" class="btn btn-sm btn-success" onclick="setupTest(this);" /> <input type="submit" id="'+data[i].testId+'" value="Delete" class="btn btn-sm btn-danger" onclick="deleteTest(this);" /> </td>'
                        +'</tr>');
                    });
                }
            });
        }

        function createTest(){
            var testTitle = $("#txtTestTitle").val();
            var testDate = $("#txtTestDate").val();
            var testEndTime = $("#txtTestEndTime").val();
            var testForClass = $("#ddlForClass").val();
            var testForSection = $("#ddlForSection").val();
            var testStartTime = $("#txtTestStartTime").val();
            var testSubject = $("#ddlSubject").val();
            var testStream = $("#ddlStream").val();
            var flag = "createTest";
            var created_by = $("#lblUserUnid").text();

            if(testTitle.trim()==""){
                $("#txtTestTitle").attr("placeholder","* Enter Test Title");
                $("#txtTestTitle").css("border", "1px solid red");
                return false;
            }
            else if(testDate.trim()==""){
                $("#txtTestTitle").css("border", "1px solid #ccc");

                $("#txtTestDate").css("border", "1px solid red");
                return false;
            }
            else if(testEndTime.trim()==""){
                $("#txtTestDate").css("border", "1px solid #ccc");

                $("#txtTestEndTime").attr("placeholder","* Enter Test Duration");
                $("#txtTestEndTime").css("border", "1px solid red");
                return false;
            }
            else if(testForClass.trim()==0 || testForClass.trim()==""){
                $("#txtTestEndTime").css("border", "1px solid #ccc");

                $("#ddlForClass").css("border", "1px solid red");
                return false;
            }
            else if(testForSection.trim()==0 || testForSection.trim()==""){
                $("#ddlForClass").css("border", "1px solid #ccc");

                $("#ddlForSection").css("border", "1px solid red");
                return false;
            }
            else if(testStartTime.trim()==""){
                $("#ddlForSection").css("border", "1px solid #ccc");

                $("#txtTestStartTime").css("border", "1px solid red");
                return false;
            }
            else if(testSubject.trim()==0 || testSubject.trim()==""){
                $("#txtTestStartTime").css("border", "1px solid #ccc");

                $("#ddlSubject").css("border", "1px solid red");
                return false;
            }
            else if(testStream.trim()==0 || testStream.trim()==""){
                $("#ddlSubject").css("border", "1px solid #ccc");

                $("#ddlStream").css("border", "1px solid red");
                return false;
            }
            else{
                $.ajax({
                    url:"call_service.php",
                    method:"POST",
                    data: {
                        action:flag,
                        t_title:testTitle,
                        t_date:testDate,
                        t_EndTime:testEndTime,
                        t_class:testForClass,
                        t_section:testForSection,
                        t_StartTime:testStartTime,
                        t_subject:testSubject,
                        t_stream:testStream,
                        creator: created_by
                    },
                    dataType:"json",
                    success:function(data){
                        if(data=="11"){
                            alert("Please enter all the fields");
                        }
                        else if(data == "1"){
                            alert("Test created successfully");
                            loadAllTest();
                        }
                        else{
                            alert("Something Went Wrong");
                        }
                    },
                    error:function(xhr, status, error){
                        console.log(error);
                        console.warn(xhr.responseText);
                    }
                });
            }
        }

        // function to bind test details in the modal to edit..
        function loadTestForEdit(get){
            openModal();                // calling the modal here..  
            $("#lblToShowTestId").text(get.id);
            var flag = "bindTestDetail";
            var testId = $("#lblToShowTestId").text();
            var creator = $("#lblUserUnid").text();
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data:{
                    action: flag,
                    test_id: testId,
                    created_by: creator
                },
                dataType:"json",
                success:function(data){
                    //alert(data.testTitle);
                    
                    $("#txtEditTestTitle").val(data.testTitle);
                    $("#txtEditTestDate").val(data.testDate);
                    $("#txtEditTestStartTime").val(data.testStar);
                    $("#txtEditTestEndTime").val(data.testEnd);

                    var testForSection = data.forSection;
                    var testForStream = data.forStream;
                    var select = document.getElementById("ddlEditTestSection");
                    for (var i = 0; i < select.length; i++) 
					{                     //iterating through each value of ddl and checking the matching value...
                        var option = select.options[i].value;
                        if (option == testForSection) {
                            var option = $('#ddlEditTestSection').children(
                                'option[value="' + option + '"]');
                            option.attr('selected', true);
                        }
                        else{
                            var option = $('#ddlEditTestSection').children(
                                'option[value="' + option + '"]');
                            option.removeAttr('selected', true);        // removing the attribute selected
                        }
                    }

                    var select1 = document.getElementById("ddlEditTestStream");
                    for (var i = 0; i < select.length; i++) 
					{                     //iterating through each value of ddl and checking the matching value...
                        var option = select1.options[i].value;
                        if (option == testForStream) {
                            var option = $('#ddlEditTestStream').children(
                                'option[value="' + option + '"]');
                            option.attr('selected', true);
                        }
                        else{
                            var option = $('#ddlEditTestStream').children(
                                'option[value="' + option + '"]');
                            option.removeAttr('selected', true);        // removing the attribute selected
                        }
                    }

                }
            });
        }

        // function to remove red border from every field if validation successfull
        function allValidationPass(){
            $("#txtEditTestTitle").css("border", "1px solid #ccc");
            $("#txtEditTestDate").css("border", "1px solid #ccc");
            $("#txtEditTestEndTime").css("border", "1px solid #ccc");
            $("#ddlEditTestClass").css("border", "1px solid #ccc");
            $("#ddlEditTestSection").css("border", "1px solid #ccc");
            $("#txtEditTestStartTime").css("border", "1px solid #ccc");
            $("#ddlEditTestSubject").css("border", "1px solid #ccc");
            $("#ddlEditTestStream").css("border", "1px solid #ccc");
        }

        // function to update test details..
        function updateTestDetails(){
            var testId = $("#lblToShowTestId").text();
            var testTitle = $("#txtEditTestTitle").val();
            var testDate = $("#txtEditTestDate").val();
            var testEndTime = $("#txtEditTestEndTime").val();
            var testForClass = $("#ddlEditTestClass").val();
            var testForSection = $("#ddlEditTestSection").val();
            var testStartTime = $("#txtEditTestStartTime").val();
            var testSubject = $("#ddlEditTestSubject").val();
            var testStream = $("#ddlEditTestStream").val();
            var flag = "updateTest";
            var updated_by = $("#lblUserUnid").text();
            if(testTitle.trim()==""){
                $("#txtEditTestTitle").attr("placeholder","* Enter Test Title");
                $("#txtEditTestTitle").css("border", "1px solid red");
                return false;
            }
            else if(testDate.trim()==""){
                $("#txtEditTestTitle").css("border", "1px solid #ccc");

                $("#txtEditTestDate").css("border", "1px solid red");
                return false;
            }
            else if(testStartTime.trim()==""){
                $("#txtEditTestDate").css("border", "1px solid #ccc");

                $("#txtEditTestStartTime").attr("placeholder","* Enter Test Duration");
                $("#txtEditTestStartTime").css("border", "1px solid red");
            }
            else if(testEndTime.trim()==""){
                $("#txtEditTestStartTime").css("border", "1px solid #ccc");

                $("#txtEditTestEndTime").attr("placeholder","* Enter Test Duration");
                $("#txtEditTestEndTime").css("border", "1px solid red");
                return false;
            }
            else if(testForClass.trim()==0 || testForClass.trim()==""){
                $("#txtEditTestEndTime").css("border", "1px solid #ccc");

                $("#ddlEditTestClass").css("border", "1px solid red");
                return false;
            }
            else if(testForSection.trim()==0 || testForSection.trim()==""){
                $("#ddlEditTestClass").css("border", "1px solid #ccc");

                $("#ddlEditTestSection").css("border", "1px solid red");
                return false;
            }
            else if(testStartTime.trim()==""){
                $("#ddlEditTestSection").css("border", "1px solid #ccc");

                $("#txtEditTestStartTime").css("border", "1px solid red");
                return false;
            }
            else if(testSubject.trim()==0 || testSubject.trim()==""){
                $("#txtEditTestStartTime").css("border", "1px solid #ccc");

                $("#ddlEditTestSubject").css("border", "1px solid red");
                return false;
            }
            else if(testStream.trim()==0 || testStream.trim()==""){
                $("#ddlEditTestSubject").css("border", "1px solid #ccc");

                $("#ddlEditTestStream").css("border", "1px solid red");
                return false;
            }
            else{
                allValidationPass();    // removing red borders..
                $.ajax({
                    url:"call_service.php",
                    method:"POST",
                    data: {
                        action:flag,
                        t_testId:testId,
                        t_title:testTitle,
                        t_date:testDate,
                        t_EndTime:testEndTime,
                        t_class:testForClass,
                        t_section:testForSection,
                        t_StartTime:testStartTime,
                        t_subject:testSubject,
                        t_stream:testStream,
                        updator: updated_by
                    },
                    dataType:"json",
                    success:function(data){
                        if(data=="11"){
                            alert("Please enter all the fields");
                        }
                        else if(data == "1"){
                            alert("Updated successfully");
                            loadAllTest();
                        }
                        else{
                            alert("Something Went Wrong");
                        }
                    },
                    error:function(xhr, status, error){
                        console.log(error);
                        console.warn(xhr.responseText);
                    }
                });
            }
        }

        // function to setup the test
        function setupTest(get){
            var testId = get.id;
            window.location.href='add_questions.php?test_id='+testId+'';
        }

        function deleteTest(get){
            var chk = confirm("Are you sure you want to delete?");
            if (chk){
                var testId = get.id;
                var creator = $("#lblUserUnid").text();       // getting user unique id
                var flag = "deleteTest";
                $.ajax({
                    url: "call_service.php",
                    method: "POST",
                    data:{
                        action:flag,
                        test_id:testId,
                        created_by:creator
                    },
                    dataType:"json",
                    success:function(data){
                        loadAllTest();          // loading all test after delete..
                    }
                });
            }
            else{
                return false;
            }
        }

    </script>

</html>

<?php
	}
}
else{
	header("Location: index.php");
}
?>