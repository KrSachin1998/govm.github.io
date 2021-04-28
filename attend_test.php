<?php
include("core/connpdo.php");        // calling connection
session_start();
if($_SESSION["addNo"]!="" && $_SESSION["stuName"]!="" && $_SESSION["userType"]=="student")
{

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<link rel="stylesheet" href="assets/bootstrap4/css/bootstrap.min.css" />
	<link rel="stylesheet" href="assets/css/style-attend-test.css" /> </head>
	<link rel="stylesheet" href="assets/css/loader.css" />
	<style>

		.attend-test .modal {
  			display: none; /* Hidden by default */
  			position: fixed; /* Stay in place */
  			z-index: 1; /* Sit on top */
  			padding-top: 100px; /* Location of the box */
  			left: 0;
			top: 0;
			width: 100%; /* Full width */
			height: 100%; /* Full height */
			overflow: auto; /* Enable scroll if needed */
			background-color: rgb(0,0,0); /* Fallback color */
			background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
		}

		/* Modal Content */
		.attend-test .modal-content {
			position: relative;
			background-color: #fefefe;
			margin: auto;
			padding: 0;
			border: 1px solid #888;
			width: 30%;
			box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
			-webkit-animation-name: animatetop;
			-webkit-animation-duration: 0.4s;
			animation-name: animatetop;
			animation-duration: 0.4s
		}

		/* Add Animation */
		@-webkit-keyframes animatetop {
			from {top:-300px; opacity:0} 
			to {top:0; opacity:1}
		}

		@keyframes animatetop {
			from {top:-300px; opacity:0}
			to {top:0; opacity:1}
		}

		/* The Close Button */
		.attend-test .close {
			color: white;
			float: right;
			font-size: 28px;
			font-weight: bold;
		}

		.attend-test .close:hover,
		.attend-test .close:focus {
			color: #000;
			text-decoration: none;
			cursor: pointer;
		}

		.attend-test .modal-header {
			padding: 2px 16px;
			background-color: #5cb85c;
			color: white;
		}

		.attend-test .modal-body {
			padding: 2px 16px;
			text-align: center;
			font-weight: 500;
		}

		.attend-test .modal-footer {
			padding: 2px 16px;
			background-color: #5cb85c;
			color: white;
		}

	</style>

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
	<!--========= calling navbar starts ==========-->
	<?php
		include_once "header_oth.inc.php";
		//include_once "sidebar.inc.php";
	?>
	<!--========= calling navbar ends ==========-->

	<!-- ========== some hidden fields ========== -->
	<label id="lblCorrectAnswer" style="display:none;"></label>
	<label id="lblLoadNextQuesNum" style="display:none;">0</label>	<!-- this value is used to load the questions when click on next -->
	<label id="lblTotalQues" style="display:none;">0</label>
	<label id="lblTestStartTime" style="display:none;"></label>
	<label id="lblTestEndTime" style="display:none;"></label>

	<section class="attend-test">

		<div class="container test-details">
			<div class="row">
				<div class="col-sm-4">
					<label class="class-label">Class:</label>
					<label id="lblShowClass"></label>
				</div>

				<div class="col-sm-4">
					<label class="class-label">Section:</label>
					<label id="lblShowSection"></label>
				</div>

				<div class="col-sm-4">
					<label class="class-label">Stream:</label>
					<label id="lblShowStream"></label>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-4">
					<label class="class-label">Subject:</label>
					<label id="lblShowSubject"></label>
				</div>

				<div class="col-sm-4">
					<label class="class-label">Date:</label>
					<label id="lblShowDate"></label>
				</div>
			</div>

		</div>

		<!-- The Modal for showing "All the best" message and when user clicked it it will load the first question -->
		<div id="myModal" class="modal">

			<!-- Modal content -->
			<div class="modal-content">
  				<div class="modal-body">
					<p><i class="uil uil-smile"></i> All the Best!</p>
					<input type="submit" class="btn btn-sm btn-success" onclick="closeModal()" value="close"/>
  				</div>

			</div>

		</div>
		<!-- Modal end -->


		<div class="container-fluid">

			<div class="row contain-timer">
				<div class="col-sm-4"></div>
				<div class="col-sm-4"></div>
				<div class="col-sm-4">
					<div class="timer-box">
						<p class="timer" id="timer"> 00 : 00 : 00 </p>
					</div>
				</div>
			</div>

			<div class="row contain-questions">
				<div class="col-sm-4">

					<!-- The Question navigation Table -->
					<div id="questionTable" class="question-table">
						<!-- Question navigation Table content -->
						<div class="question-table-content">
							<div class="question-table-header">
								<p>Get Question</p>
                            </div>
							<div class="question-table-body" id="bindQuesLblIds">
								<!-- labels for question navigation -->

							</div>
						</div>
					</div>

				</div>
				<div class="col-sm-8">

                    <!-- The Question Table -->
					<div id="questionTable" class="question-table">
						<!-- Question Table content -->
						<div class="question-table-content">
							<div class="question-table-header">
								<p>Question: <label id="lblQuesNumber"></label> </p> 
                            </div>
							<div class="question-table-body">

								<h6><strong>Question: <label id="lblQuesOriginalId" style="display:none;" ></label> </strong></h6>
								<p class="question" id="txtQuestionTitle">
									
								</p>

								<h6><strong>Choices:</strong></h6>
								<div class="question-choices">
									
								<div class="row col-sm-12">
									<div class="col-sm-10">
										<label>A. </label>
										<label id="lblOption1">Option 1</label>
									</div>

									<div class="col-sm-2">
										<input type="radio" id="rdbOption1" name="correctOption">
									</div>
                                </div>
                                
                                <div class="row col-sm-12">
									<div class="col-sm-10">
										<label>B. </label>
                                    	<label id="lblOption2">Option 2</label>
									</div>

									<div class="col-sm-2">
										<input type="radio" id="rdbOption2" name="correctOption">
									</div>
                                </div>

                                <div class="row col-sm-12">
									<div class="col-sm-10">
										<label>C. </label>
                                    	<label id="lblOption3">Option 3</label>
									</div>

									<div class="col-sm-2">
										<input type="radio" id="rdbOption3" name="correctOption">
									</div>
                                    
                                </div>

                                <div class="row col-sm-12">
									<div class="col-sm-10">
										<label>D. </label>
                                    	<label id="lblOption4">Option 4</label>
									</div>

									<div class="col-sm-2">
										<input type="radio" id="rdbOption4" name="correctOption">
									</div>
                                </div>

								</div>

								<div class="contain-buttons">
									<input type="submit" class="btn btn-sm btn-primary" onclick="saveAnswer()" value="Save Answer"/>
									<input type="submit" class="btn btn-sm btn-danger" onclick="finishTest()" style="float:right;" value="Finish Exam"/>
								</div>

							</div>
						</div>
					</div>

                </div>
			</div>
		</div>
	</section>
</body>

<script>

	var flag = "selectQuesForStu";
	// getting the value of test id from url..
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	const testId = urlParams.get('t_id');				// getting test id from url (global variable)

	$(document).ready(function(){
		$("#container").attr('style', 'display:none');	// removing the loading circle
		loadQuestionsIds();
		getTestDetails();		// load test details
		showModal();				// after closing this modal it will load the first questions
	});

	//this function will bind test details..
	function getTestDetails(){
		var flag="getTestDetails";
		var startTime = "";
		var endTime = "";
		var testDate = "";
		$.ajax({
			url:"call_service_loaddata.php",
			method:"POST",
			data:{
				action:flag,
				t_id:testId
			},
			dataType:"json",
			success:function(data){
				$("#lblShowClass").text(data.testClass);
				$("#lblShowSection").text(data.testSection);
				$("#lblShowStream").text(data.testStream);
				$("#lblShowSubject").text(data.testSubject);
				$("#lblShowDate").text(data.testDate);
				$("#lblTestStartTime").text(data.testStartTime);
				$("#lblTestEndTime").text(data.testEndTime);
				setTimer(data.testStartTime,data.testEndTime,data.testDate);
			}
		});
		
	}


	function setTimer(startTime,endTime,testDate){
		var dateArr = testDate.split('-');
		var year = dateArr[0];
		var month = dateArr[1];
		var day = dateArr[2];
		var makeEndTime= month+' '+day+', '+year+' '+endTime;

		// "04 27, 2021 14:50:00" this is the format of the date time we have to give..
		var countDownDate = new Date(makeEndTime).getTime();
		// Update the count down every 1 second
		var x = setInterval(function() {

  		var now = new Date().getTime();
  		var distance = countDownDate - now;
    
  		// Time calculations for days, hours, minutes and seconds
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
		document.getElementById("timer").innerHTML = hours + " : "+ minutes + " : " + seconds;
    
  		// If the count down is over, end the test
  		if (distance < 0) {
			document.getElementById("timer").innerHTML = "EXPIRED";
    		endTest();				// this function will end the test
			window.location.href="student_dashboard.php";
  		}
		}, 1000);
	}


	function endTest(){
		var flag="endTest";
		$.ajax({
			url:"call_service_loaddata.php",
			method:"POST",
			data:{
				action:flag,
				t_id:testId
			},
			dataType:"json",
			success:function(data){
				window.location.href = "student_dashboard.php";
			}
		});
	}

	// this function will bind the question ids in the table so that student can freely navigate
	function loadQuestionsIds(){
		var flag = "selectQuesForStu";
		var ques_count = $("#lblTotalQuesCount").text();
		var stuId = $("#lblStuId").text();
		var stuClass = $("#lblStuClass").text();
        var stuSection = $("#lblStuSection").text();
        var stuStream = $("#lblStuStream").text();
		$.ajax({
			url:"call_service_loaddata.php",
			method:"POST",
			data:{
				action:flag,
				test_id:testId,
				stu_id:stuId
			},
			dataType:"json",
			success:function(data){
				var len = data.length;
				$("#lblTotalQues").text(len);
				$.each(data, function(i, item){
					$("#bindQuesLblIds").append('<label class="navigate-question" onclick="loadThisQuestion(this)" id="'+data[i].quesId+'" >'+ (i+1) +'</label> ');
				});
				
			},
			error:function(xhr, status, error){
                console.log(error);
				console.warn(xhr.responseText);
			}
		});

	}

	function loadFirstQuestion(){
		var element = document.getElementById('bindQuesLblIds').getElementsByClassName('navigate-question')[0];
		var quesId = element.id;
		loadThisQuestion(element);
	}

	// function to show the modal
	function showModal(){
		var modal = document.getElementById("myModal");
		modal.style.display = "block";
	}

	// function to close the modal
	function closeModal(){
		var modal = document.getElementById("myModal");
		modal.style.display = "none";
		loadFirstQuestion();			// loading the first question when user clicked on close modal
	}

	// function to unselect the radio button
	function resetItems(){
		document.getElementById("rdbOption1").checked=false;
		document.getElementById("rdbOption2").checked=false;
		document.getElementById("rdbOption3").checked=false;
		document.getElementById("rdbOption4").checked=false;
	}

	function loadThisQuestion(get){
		$("#container").removeAttr('style');	// displaying the loading screen	
		resetItems();	// unselecting the radio button if anyone of them is already selected

		var quesId = get.id;		// getting the question id;
		var flag = "bindThisQuestion";
		var makeId = '#'+quesId;		// creating the id of the clicked label..
		var quesNum = $(makeId).text();
		var stuId = $("#lblStuId").text();
		
		$("#lblQuesNumber").text(quesNum);

		//var testId = 1;
		$.ajax({
			url:"call_service_loaddata.php",
			method:"POST",
			data:{
				action:flag,
				test_id:testId,
				ques_id:quesId,
				stu_id:stuId
			},
			dataType:"json",
			success:function(data){
				//var getDataLen = 0;
				//getDataLen = data.stuAnswer.length;
				if(data.stuAnswer){	// answer response has already been submitted and again student is loading the question
					$("#txtQuestionTitle").html(data.quesTitle);
					$("#lblQuesOriginalId").text(data.quesId);
					$("#lblCorrectAnswer").text(data.quesCorrOption);
					$("#lblOption1").text(data.quesOption1);
					$("#lblOption2").text(data.quesOption2);
					$("#lblOption3").text(data.quesOption3);
					$("#lblOption4").text(data.quesOption4);

					var option1 = $("#lblOption1").text();           // getting option 1
					var option2 = $("#lblOption2").text();           // getting option 2
					var option3 = $("#lblOption3").text();           // getting option 3
					var option4 = $("#lblOption4").text();           // getting option 4

					var op1 = document.getElementById("rdbOption1");	// getting the radio button 1
					var op2 = document.getElementById("rdbOption2");	// getting the radio button 2
					var op3 = document.getElementById("rdbOption3");	// getting the radio button 3
					var op4 = document.getElementById("rdbOption4");	// getting the radio button 4

					document.getElementById(data.quesId).style.backgroundColor="#22bf22";// changing the background colour of attended question to green

					// checking the radiobutton which was selected by the student
					if(option1 == data.stuAnswer){	// option 1 was checked by the student
						op1.checked=true;
					}
					else if(option2 == data.stuAnswer){		// option 2 was checked by the student
						op2.checked=true;
					}
					else if(option3 == data.stuAnswer){		// option 3 was checked by the student
						op3.checked=true;
					}
					else if(option4 == data.stuAnswer){		// option 4 was checked by the student
						op4.checked=true;
					}
				}
				
				else{			// requesting question for the first time
					$("#txtQuestionTitle").html(data.quesTitle);
					$("#lblQuesOriginalId").text(data.quesId);
					$("#lblCorrectAnswer").text(data.quesCorrOption);
					$("#lblOption1").text(data.quesOption1);
					$("#lblOption2").text(data.quesOption2);
					$("#lblOption3").text(data.quesOption3);
					$("#lblOption4").text(data.quesOption4);
				}
				$("#container").attr('style', 'display:none');	// removing the loading screen
			},
			error:function(xhr, status, error){
                console.log(error);
				console.warn(xhr.responseText);
			}
		});
		
	}

	function saveAnswer(){
		$("#container").removeAttr('style');	// displaying the loading screen
		var flag = "saveThisAnswer";
		var stuId = parseInt($("#lblStuId").text());
		var ques_id = $("#lblQuesOriginalId").text();	// label id is also same
		var test_id = testId;
		var correct_ans = $("#lblCorrectAnswer").text();
		var stu_ans = "";									// for receiving student answer
		var isCorrect = 0;
		var countNext = parseInt($("#lblLoadNextQuesNum").text());	// this will help to load the next question..
		var totQues = parseInt($("#lblTotalQues").text());

		var option1 = $("#lblOption1").text();           // getting option 1
		var option2 = $("#lblOption2").text();           // getting option 2
		var option3 = $("#lblOption3").text();           // getting option 3
		var option4 = $("#lblOption4").text();           // getting option 4

		var op1 = document.getElementById("rdbOption1");	// getting the radio button 1
		var op2 = document.getElementById("rdbOption2");	// getting the radio button 2
		var op3 = document.getElementById("rdbOption3");	// getting the radio button 3
		var op4 = document.getElementById("rdbOption4");	// getting the radio button 4

		if(op1.checked || op2.checked || op3.checked || op4.checked){
			if(op1.checked){
				stu_ans = option1;
			}
			else if(op2.checked){
				stu_ans = option2;
			}
			else if(op3.checked){
				stu_ans = option3;
			}
			else if(op4.checked){
				stu_ans = option4;
			}

			if(stu_ans == correct_ans){			// checking if the student answer is correct or not
				isCorrect = 1;
			}

			$.ajax({
				url:"call_service_loaddata.php",
				method:"POST",
				data:{
					action:flag,
					stu_id: stuId,
					test_id: test_id,
					ques_id: ques_id,
					is_correct: isCorrect,
					student_option: stu_ans
				},
				dataType:"json",
				success:function(data){
					countNext++;
					$("#lblLoadNextQuesNum").text(countNext);	// again assigning the count for further use
					document.getElementById(ques_id).style.backgroundColor="#22bf22";	// changing the background colour of attended question to green

					if(countNext<totQues){	// if this condition failed, it means last question reached
						var element = document.getElementById('bindQuesLblIds').getElementsByClassName('navigate-question')[countNext];
						var quesId = element.id;
						loadThisQuestion(element);
						resetItems();					// unselecting the radio button 
					}

					$("#container").attr('style', 'display:none');	// removing the loading screen
				}
			});

		}
		else{
			alert("please select your answer");
			$("#container").attr('style', 'display:none');	// removing the loading screen
			return false;
		}

	}

	function finishTest(){
		var chk = confirm("Are you sure, you want to finish test?");
		if(chk){
			var flag = "finishTest";
			var stuId = parseInt($("#lblStuId").text());
			var test_id = testId;
			$.ajax({
				url:"call_service_loaddata.php",
				method:"POST",
				data:{
					action:flag,
					stu_id:stuId,
					t_id:test_id
				},
				dataType:"json",
				success:function(data){
					if(data=="1"){
						window.location.href = "student_dashboard.php"; 
					}
					else{
						
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