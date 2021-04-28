<?php
session_start();   // before using the session varibale we have to start the session
    if(($_SESSION["userType"]="teacher" || $_SESSION["userType"]="admin") && ($_SESSION["adminId"]!="" || $_SESSION["teachId"]!=""))
	{

        //echo $_GET["test_id"];        // to get the test id from url..

    /*include_once "header.inc.php";
    include_once "sidebar.inc.php";*/
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add Questions</title>
        <link href="assets/css/style-add-questions.css" rel="stylesheet"/>
    </head>
    <body>
        <!--========= calling navbar and sidebar ==========-->
        <?php
            include_once "header.inc.php";
            include_once "sidebar.inc.php";
        ?>
        <section class="add-question" id="addQuestionForm">
            <!-- Showing details of test start -->
            <div class="contain-test-details">
                <h4>Dashboard/Add Questions</h4>
                <h5 class="containTestId">
                    <label class="lblTestId">Test ID:</label>
                    <label class="lblBindTestId" id="lblBindTestId"> <?php echo $_GET["test_id"]; ?> </label>        <!-- will be from database -->
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
            <!-- Showing details of test start -->
            <hr>

            <!-- Add Questions here start -->
            <div class="contain-add-question">
                <div class="form-group row">
                    <div class="col-sm-8">
                        <label>Question Title</label>
                        <textarea id="txtTestQuesTitle" class="form-control" rows="4" cols="50"></textarea>
                    </div>   
                </div>

                <div class="form-group row">
                    <div class="col-sm-6">
                        <div class="question-choices">
							
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>A</label>
                                    <input type="text" id="txtOption1" class="form-control" />
                                    <input type="radio" id="radioOption1" name="correctOption">
                                </div>
                                
                                <div class="col-sm-12">
                                    <label>B</label>
                                    <input type="text" id="txtOption2" class="form-control" />
                                    <input type="radio" id="radioOption2" name="correctOption">
                                </div>

                                <div class="col-sm-12">
                                    <label>C</label>
                                    <input type="text" id="txtOption3" class="form-control" />
                                    <input type="radio" id="radioOption3" name="correctOption">
                                </div>

                                <div class="col-sm-12">
                                    <label>D</label>
                                    <input type="text" id="txtOption4" class="form-control" />
                                    <input type="radio" id="radioOption4" name="correctOption">
                                </div>

                                <div class="col-sm-12">
                                    <input type="submit" class="btn btn-success btn-sm" onclick="saveQuestion()" value="Save"/>
                                </div>
                                
                            </div> 

						</div>
                    </div>
                </div>

                 <!-- table to bind questions starts -->
                <table class="table table-striped">

                    <thead>
                        <tr>
                            <th>S No</th>
                            <th>Question Title</th>
                            <th>Option-1</th>
                            <th>Option-2</th>
                            <th>Option-3</th>
                            <th>Option-4</th>
                            <th>Correct Option</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tblCreatedTest">
                        <tr>
                            <td>No Questions Added Yet</td>
                        </tr>
                    </tbody>
                </table>
                <!-- table to bind questions ends -->

            </div>
            <!-- Add Questions here ends -->
        </section>

    </body>

    <script>
        $(document).ready(function(){
            loadTestDetails();
            allQuestions();
        });

        // function to clear the question title and options..
        function clearFileds(){
            $("#txtTestQuesTitle").val("");
            $("#txtOption1").val("");
            $("#txtOption2").val("");
            $("#txtOption3").val("");
            $("#txtOption4").val("");
            document.getElementById("radioOption1").checked = false;
            document.getElementById("radioOption2").checked = false;
            document.getElementById("radioOption3").checked = false;
            document.getElementById("radioOption4").checked = false;
        }

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

        function clearAllBorder(){
            $("#txtTestQuesTitle").css("border", "1px solid #ccc");
            $("#txtOption1").css("border", "1px solid #ccc");
            $("#txtOption2").css("border", "1px solid #ccc");
            $("#txtOption3").css("border", "1px solid #ccc");
            $("#txtOption4").css("border", "1px solid #ccc");
        }

        function saveQuestion(){
            var quesTitle = $("#txtTestQuesTitle").val();       // getting question title
            var option1 = $("#txtOption1").val();           // getting option 1
            var option2 = $("#txtOption2").val();           // getting option 2
            var option3 = $("#txtOption3").val();           // getting option 3
            var option4 = $("#txtOption4").val();           // getting option 4

            var op1 = document.getElementById("radioOption1");
            var op2 = document.getElementById("radioOption2");
            var op3 = document.getElementById("radioOption3");
            var op4 = document.getElementById("radioOption4");
            var correctOption = "";                             // variable to get the correct option

            if(op1.checked){
                correctOption = $("#txtOption1").val();
            }
            else if(op2.checked){
                correctOption = $("#txtOption2").val();
            }
            else if(op3.checked){
                correctOption = $("#txtOption3").val();
            }
            else if(op4.checked){
                correctOption = $("#txtOption4").val();
            }

            // applying client side validation
            if(quesTitle.trim()==""){
                clearAllBorder();
                $("#txtTestQuesTitle").attr("placeholder","* Enter Question");
                $("#txtTestQuesTitle").css("border", "1px solid red");
                return false;
            }
            else if(option1.trim()==""){
                clearAllBorder();
                $("#txtOption1").attr("placeholder","* Enter Option 1");
                $("#txtOption1").css("border", "1px solid red");
                return false;
            }
            else if(option2.trim()==""){
                clearAllBorder();

                $("#txtOption2").attr("placeholder","* Enter Option 2");
                $("#txtOption2").css("border", "1px solid red");
                return false;
            }
            else if(option3.trim()==""){
                clearAllBorder();

                $("#txtOption3").attr("placeholder","* Enter Option 3");
                $("#txtOption3").css("border", "1px solid red");
                return false
            }
            else if(option4.trim()==""){
                clearAllBorder();

                $("#txtOption4").attr("placeholder","* Enter Option 4");
                $("#txtOption4").css("border", "1px solid red");
                return false
            }
            else if(op1.checked == false && op2.checked == false && op3.checked == false && op4.checked == false ){
                clearAllBorder();
                alert("Please Select One Correct Option");
                return false;
            }
            else{
                var flag = "insertQuestion";
                var testId = $("#lblBindTestId").text();        // getting test id
                var createdBy = $("#lblUserUnid").text();       // getting user unique id
                var class_id = $("#lblTestForClass").text();
                var section_id = $("#lblTestForSection").text(); 
                var stream_id = $("#lblTestForStream").text();
                var subject_id = $("#lblTestSubject").text();
                $.ajax({
                    url:"call_service.php",
                    method:"POST",
                    data:{
                        action:flag,
                        test_id: testId,
                        q_title: quesTitle,
                        option_1: option1,
                        option_2: option2,
                        option_3: option3,
                        option_4: option4,
                        correct_option: correctOption,
                        class: class_id,
                        section: section_id,
                        stream: stream_id,
                        subject: subject_id,
                        created_by: createdBy
                    },
                    dataType:"json",
                    success:function(data){
                        if(data == "11"){
                            alert("Some thing Went Wrong");     // server side validation failed
                        }
                        else if(data == "1"){
                            alert("Question Added Successfully");
                            allQuestions()      // loading all the questions
                            clearFileds();      // clearing all the fields
                        }
                    }
                });
            }

        }

        // function to load all questions of the test..
        function allQuestions(){
            var testId = $("#lblBindTestId").text();        // getting test id
            var createdBy = $("#lblUserUnid").text();       // getting user unique id
            var flag = "loadQuestions";
            var count = 1;
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data:{
                    action:flag,
                    test_id:testId,
                    created_by:createdBy
                },
                dataType:"json",
                success:function(data){
                    $("#tblCreatedTest").empty();
                    $.each(data, function(i, item){
                        $("#tblCreatedTest").append("<tr> <td>"+ count +"</td> <td>"+ data[i].quesTitle +"</td> <td>"+ data[i].option1 +"</td> <td>"+ data[i].option2 +"</td> <td>"+ data[i].option3 +"</td> <td>"+ data[i].option4 +"</td> <td>"+ data[i].correctOption +"</td> <td><input type='submit' class='btn btn-sm btn-primary' id="+data[i].quesId+" value='Edit' onclick='editQuestion(this)' /> <input type='submit' class='btn btn-sm btn-danger' id="+data[i].quesId+" value='Delete' onclick='deleteQuestion(this)' /> </td> </tr>");
                        count++;
                    });

                }
            });
        }

        function editQuestion(get){
            var quesId = get.id;                // getting the question id
            var flag = "editQuestion";  
            var testId = $("#lblBindTestId").text();        // getting test id
            var creator = $("#lblUserUnid").text();       // getting user unique id
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data:{
                    action:flag,
                    ques_id:quesId,
                    test_id:testId,
                    created_by:creator
                },
                dataType:"json",
                success:function(data){
                    $("#txtTestQuesTitle").val(data.quesTitle);
                    $("#txtOption1").val(data.option1);
                    $("#txtOption2").val(data.option2);
                    $("#txtOption3").val(data.option3);
                    $("#txtOption4").val(data.option4);
                    if(data.option1 == data.correctOption){
                        document.getElementById("radioOption1").checked = true;
                        document.getElementById("radioOption2").checked = false;
                        document.getElementById("radioOption3").checked = false;
                        document.getElementById("radioOption4").checked = false;
                    }
                    else if(data.option2 == data.correctOption){
                        document.getElementById("radioOption1").checked = false;
                        document.getElementById("radioOption2").checked = true;
                        document.getElementById("radioOption3").checked = false;
                        document.getElementById("radioOption4").checked = false;
                    }
                    else if(data.option3 == data.correctOption){
                        document.getElementById("radioOption1").checked = false;
                        document.getElementById("radioOption2").checked = false;
                        document.getElementById("radioOption3").checked = true;
                        document.getElementById("radioOption4").checked = false;
                    }
                    else if(data.option4 == data.correctOption){
                        document.getElementById("radioOption1").checked = false;
                        document.getElementById("radioOption2").checked = false;
                        document.getElementById("radioOption3").checked = false;
                        document.getElementById("radioOption4").checked = true;
                    }
                }
            });
        }

        function deleteQuestion(get){
            var chk = confirm("Are you sure you want to delete?");
            if (chk){
                var quesId = get.id;
                var flag = "deleteQues";
                var testId = $("#lblBindTestId").text();        // getting test id
                var creator = $("#lblUserUnid").text();       // getting user unique id
                $.ajax({
                    url:"call_service_loaddata.php",
                    method:"POST",
                    data:{
                        action:flag,
                        ques_id:quesId,
                        test_id:testId,
                        created_by:creator
                    },
                    dataType:"json",
                    success:function(data){
                        if(data=="1"){
                            allQuestions();
                        }
                        else{
                            alert("something went wrong");
                        }
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
	else{
		header("Location: index.php");
	}
?>