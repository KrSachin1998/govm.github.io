<?php

include("core/connpdo.php");        // calling connection
try {
    
    if (isset($_POST["action"])){

        if($_POST["action"] == "loadClass"){
            $flag='loadClass';
            $stmt = $conn->prepare('call USP_REGISTER_STUDENT_DATABIND(?)');
            $stmt->bindParam(1, $flag);
            $stmt->execute();  
            foreach($stmt->fetchAll() as $res) {
                $arr[]=array(
                    'classId'=>$res['INT_CLASS_ID'],
                    'className'=>$res['VCH_CLASS_NAME']
                );  
            }
            echo json_encode($arr);
        }

        if($_POST["action"] == "loadSection"){
            $flag='loadSection';
            $stmt = $conn->prepare('call USP_REGISTER_STUDENT_DATABIND(?)');
            $stmt->bindParam(1, $flag);
            $stmt->execute();  
            foreach($stmt->fetchAll() as $res) {
                $arr[]=array(
                    'sectionId'=>$res['INT_SEC_ID'],
                    'sectionName'=>$res['VCH_SECTION_NAME']
                );  
            }
            echo json_encode($arr);
        }

        if($_POST["action"] == "stuAddedToday"){
            $flag='stuAddedToday';
            $todaydate = $_POST["tdate"];
            $created = $_POST["createdBy"];
            $stmt = $conn->prepare('call USP_USER_ADD_TODAY(?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $created); 
            $stmt->bindParam(3, $todaydate);   
            $stmt->execute();  
            foreach($stmt->fetchAll() as $res) {
                $arr[]=array(
                    'sadd'=>$res['INT_ADMISSION_NO'],
                    'sclassroll'=>$res['INT_CLASS_ROLLNO'],
                    'sclass'=>$res['INT_CLASS_ID'],
                    'ssection'=>$res['INT_SECTION_ID'],
                    'sname'=>$res['VCH_STU_NAME'],
                    'semail'=>$res['VCH_STU_EMAIL']
                );  
            }
            echo json_encode($arr);
        }

        if($_POST["action"] == "bindStuDetails"){       // to bind student details
            $getQuery = $_POST["query"];
            $stmt=$conn->prepare($getQuery);
            $result=$stmt->execute();
            
            $countofSearchResult = $stmt->rowCount();

            if($countofSearchResult>0){
                foreach($stmt->fetchAll() as $res) {
                    $arr[]=array(
                        'sid'=>$res['INT_STU_ID'],
                        'sname'=>$res['VCH_STU_NAME'],
                        'semail'=>$res['VCH_STU_EMAIL'],
                        'sadd'=>$res['INT_ADMISSION_NO'],
                        'sclassroll'=>$res['INT_CLASS_ROLLNO'],
                        'sclass'=>$res['INT_CLASS_ID'],
                        'ssection'=>$res['INT_SECTION_ID']
                    );  
                }
                echo json_encode($arr);
            }
            else{
                echo json_encode("0");          // no data found..
            }
        }      // to bind student details end..

        if($_POST["action"]=="getStuDetailToEdit"){     // get student details to edit based on user id
            $flag = $_POST["action"];
            $stuId = $_POST["id"];
            $teaId="";
            $stmt = $conn->prepare('call USP_GET_USR_DETAILS(?,?)');  // 3 params 1-action, 2-stuid, 3-teaid
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $stuId);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res) {

                $arr['sid']=$res['INT_STU_ID'];
                $arr['sname']=$res['VCH_STU_NAME'];
                $arr['semail']=$res['VCH_STU_EMAIL'];
                $arr['sadd']=$res['INT_ADMISSION_NO'];
                $arr['sclassroll']=$res['INT_CLASS_ROLLNO'];
                $arr['sclass']=$res['INT_CLASS_ID'];    
                $arr['ssection']=$res['INT_SECTION_ID']; 
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="teaAddedToday"){
            $flag = $_POST["action"];
            $todaydate = $_POST["tdate"];
            $created = $_POST["createdBy"];
            $stmt = $conn->prepare('call USP_USER_ADD_TODAY(?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $created); 
            $stmt->bindParam(3, $todaydate);   
            $stmt->execute();  
            foreach($stmt->fetchAll() as $res) {
                $arr[]=array(
                    'unid'=>$res['VCH_UNI_ID'],
                    'teapass'=>$res['VCH_TEACH_PASSWORD'],
                    'teaname'=>$res['VCH_TEACH_NAME'],
                    'teaemail'=>$res['VCH_TEACH_EMAIL']
                );
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="teachAuthClass"){
            $flag = "teachAuthClass";
            $tId = $_POST["teaId"];
            $stmt = $conn->prepare('call USP_TEA_AUTH_CLASS(?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $tId); 
            $stmt->execute();
            foreach($stmt->fetchAll() as $res) {
                $arr['c1']=$res['CLASS_1'];
                $arr['c2']=$res['CLASS_2'];
                $arr['c3']=$res['CLASS_3'];
                $arr['c4']=$res['CLASS_4'];
                $arr['c5']=$res['CLASS_5'];
                $arr['c6']=$res['CLASS_6'];
                $arr['c7']=$res['CLASS_7'];
                $arr['c8']=$res['CLASS_8'];
                $arr['c9']=$res['CLASS_9'];
                $arr['c10']=$res['CLASS_10'];
                $arr['c11']=$res['CLASS_11'];
                $arr['c12']=$res['CLASS_12'];
            }
            echo json_encode($arr);
        }

        if($_POST["action"] == "bindTeaDetails"){       // to bind teacher details
            $getQuery = $_POST["query"];
            $stmt=$conn->prepare($getQuery);
            $result=$stmt->execute();
            
            $countofSearchResult = $stmt->rowCount();

            if($countofSearchResult>0){
                foreach($stmt->fetchAll() as $res) {
                    $arr[]=array(
                        'tUnId'=>$res['VCH_UNI_ID'],
                        'tname'=>$res['VCH_TEACH_NAME'],
                        'temail'=>$res['VCH_TEACH_EMAIL'],
                        'class1'=>$res['CLASS_1'],
                        'class2'=>$res['CLASS_2'],
                        'class3'=>$res['CLASS_3'],
                        'class4'=>$res['CLASS_4'],
                        'class5'=>$res['CLASS_5'],
                        'class6'=>$res['CLASS_6'],
                        'class7'=>$res['CLASS_7'],
                        'class8'=>$res['CLASS_8'],
                        'class9'=>$res['CLASS_9'],
                        'class10'=>$res['CLASS_10'],
                        'class11'=>$res['CLASS_11'],
                        'class12'=>$res['CLASS_12']
                    );  
                }
                echo json_encode($arr);
            }
            else{
                echo json_encode("0");          // no data found..
            }
        }

        if($_POST["action"]=="getTeaDetailToEdit"){
            $flag = $_POST["action"];
            $stuId = 0;
            $teaId = $_POST["id"];
            $stmt = $conn->prepare('call USP_GET_TEA_DETAILS(?,?)');  // 3 params 1-action, 2-stuid, 3-teaid
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $teaId); 
            $stmt->execute();
            $rowcount=$stmt->rowCount();
            foreach($stmt->fetchAll() as $res) {
                $arr['tUnId']=$res['VCH_UNI_ID'];
                $arr['tname']=$res['VCH_TEACH_NAME'];
                $arr['temail']=$res['VCH_TEACH_EMAIL'];
                $arr['class1']=$res['CLASS_1'];
                $arr['class2']=$res['CLASS_2'];
                $arr['class3']=$res['CLASS_3'];
                $arr['class4']=$res['CLASS_4'];
                $arr['class5']=$res['CLASS_5'];
                $arr['class6']=$res['CLASS_6'];
                $arr['class7']=$res['CLASS_7'];
                $arr['class8']=$res['CLASS_8'];
                $arr['class9']=$res['CLASS_9'];
                $arr['class10']=$res['CLASS_10'];
                $arr['class11']=$res['CLASS_11'];
                $arr['class12']=$res['CLASS_12'];

            }
            echo json_encode($arr);
        }

        if($_POST["action"] == "getsubject"){
            $flag = "getsubject";
            $class_id = $_POST["class"];
            $stmt = $conn->prepare('call USP_GET_DATA(?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $class_id); 
            $stmt->execute();
            foreach($stmt->fetchAll() as $res) {
                $arr[]=array(
                    'subId'=>$res['INT_SUB_ID'],
                    'subName'=>$res['VCH_SUBJECT']
                );  
            }
            echo json_encode($arr);
        }

        if($_POST["action"] == "loadstream"){
            $flag = "loadstream";
            $c_id = 0;              // false value..
            $stmt = $conn->prepare('call USP_GET_DATA(?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $c_id); 
            $stmt->execute();
            foreach($stmt->fetchAll() as $res) {
                $arr[]=array(
                    'streamId'=>$res['INT_STREAM_ID'],
                    'streamName'=>$res['VCH_STREAM']
                );  
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="loadAllTest"){
            $flag = "getAllTest";
            $created_by = $_POST["creator"];
            $test_id = 0;       // false value to full fill the number of parameters
            $stmt = $conn->prepare('call USP_GET_ALL_TEST(?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $created_by);
            $stmt->bindParam(3, $test_id);  
            $stmt->execute();
            foreach($stmt->fetchAll() as $res) {
                $arr[]=array(
                    'testId'=>$res['INT_TEST_ID'],
                    'testTitle'=>$res['VCH_TEST_TITLE'],
                    'class'=>$res['VCH_CLASS_NAME'],
                    'section'=>$res['VCH_SECTION_NAME'],
                    'subject'=>$res['VCH_SUBJECT'],
                    'stream'=>$res['VCH_STREAM'],
                    'testDate'=>$res['DTM_SCHEDULE_DATE'],
                    't_start_time'=>$res['TEST_START_TIME'],
                    't_end_time'=>$res['TEST_END_TIME'],
                    'assign'=>$res['BIT_TEST_ASSIGN']
                );  
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="bindTestDetail"){
            $flag = "bindTestDetail";
            $stmt = $conn->prepare('call USP_GET_ALL_TEST(?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $_POST["created_by"]);
            $stmt->bindParam(3, $_POST["test_id"]); 
            $stmt->execute();
            $rowcount=$stmt->rowCount();
            foreach($stmt->fetchAll() as $res) {
                $arr['testId']=$res['INT_TEST_ID'];
                $arr['testTitle']=$res['VCH_TEST_TITLE'];
                $arr['forClass']=$res['INT_FOR_CLASS_ID'];
                $arr['forSection']=$res['INT_FOR_SECTION'];
                $arr['testSubject']=$res['INT_SUBJECT_ID'];
                $arr['forStream']=$res['INT_STREAM_ID'];
                $arr['testDate']=$res['DTM_SCHEDULE_DATE'];
                $arr['testStar']=$res['TEST_START_TIME'];
                $arr['testEnd']=$res['TEST_END_TIME'];
                $arr['testAssign']=$res['BIT_TEST_ASSIGN'];
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="loadTestDetails"){
            $flag = "loadTestDetails";
            $stmt = $conn->prepare('call USP_GET_ALL_TEST(?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $_POST["created_by"]);
            $stmt->bindParam(3, $_POST["test_id"]); 
            $stmt->execute();
            foreach($stmt->fetchAll() as $res) {
                $arr['testId']=$res['INT_TEST_ID'];
                $arr['testTitle']=$res['VCH_TEST_TITLE'];
                $arr['forClass']=$res['VCH_CLASS_NAME'];
                $arr['forSection']=$res['VCH_SECTION_NAME'];
                $arr['testSubject']=$res['VCH_SUBJECT'];
                $arr['forStream']=$res['VCH_STREAM'];
                $arr['testDate']=$res['DTM_SCHEDULE_DATE'];
                $arr['testStartTime']=$res['TEST_START_TIME'];
                $arr['testEndTime']=$res['TEST_END_TIME'];
                $arr['testAssign']=$res['BIT_TEST_ASSIGN'];
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="loadQuestions"){
            $flag = "loadQuestions";
            $dummy = 0;                 // only to fullfill the number of parameters for procedure
            $stmt = $conn->prepare('call USP_LOAD_EDIT_QUESTION(?,?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $_POST["test_id"]); 
            $stmt->bindParam(3, $_POST["created_by"]);
            $stmt->bindParam(4, $dummy);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr[]=array(
                    'quesId'=>$res['INT_QUES_ID'],
                    'quesTitle'=>$res['VCH_QUES_TITLE'],
                    'option1'=>$res['VCH_OPTION_1'],
                    'option2'=>$res['VCH_OPTION_2'],
                    'option3'=>$res['VCH_OPTION_3'],
                    'option4'=>$res['VCH_OPTION_4'],
                    'correctOption'=>$res['VCH_CORRECT_OPTION']
                );
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="editQuestion"){
            $flag = "editQuestion";
            $dummy = 0;                 // only to fullfill the number of parameters for procedure
            $stmt = $conn->prepare('call USP_LOAD_EDIT_QUESTION(?,?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $_POST["test_id"]); 
            $stmt->bindParam(3, $_POST["created_by"]);
            $stmt->bindParam(4, $_POST["ques_id"]);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr['quesId']=$res['INT_QUES_ID'];
                $arr['quesTitle']=$res['VCH_QUES_TITLE'];
                $arr['option1']=$res['VCH_OPTION_1'];
                $arr['option2']=$res['VCH_OPTION_2'];
                $arr['option3']=$res['VCH_OPTION_3'];
                $arr['option4']=$res['VCH_OPTION_4'];
                $arr['correctOption']=$res['VCH_CORRECT_OPTION'];
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="deleteQues"){
            $flag="deleteQues";
            $dummy = 0;                 // only to fullfill the number of parameters for procedure
            $stmt = $conn->prepare('call USP_LOAD_EDIT_QUESTION(?,?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $_POST["test_id"]); 
            $stmt->bindParam(3, $_POST["created_by"]);
            $stmt->bindParam(4, $_POST["ques_id"]);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $result = $res["msg"];
            }
            echo json_encode($result);
        }

        if($_POST["action"]=="upComingTest"){   // for student dashboard
            $flag = "upComingTest";
            $stmt = $conn->prepare('call USP_STU_LOAD_TEST(?,?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $_POST["classId"]); 
            $stmt->bindParam(3, $_POST["sectionId"]);
            $stmt->bindParam(4, $_POST["streamId"]);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr[]=array(
                    'testId'=>$res['INT_TEST_ID'],
                    'testTitle'=>$res['VCH_TEST_TITLE'],
                    'className'=>$res['VCH_CLASS_NAME'],
                    'sectionName'=>$res['VCH_SECTION_NAME'],
                    'streamName'=>$res['VCH_STREAM'],
                    'subName'=>$res['VCH_SUBJECT'],
                    'scheduleDate'=>$res['DTM_SCHEDULE_DATE'],
                    'startTime'=>$res['TEST_START_TIME'],
                    'endTime'=>$res['TEST_END_TIME'],
                    'isTestEnd'=>$res['INT_IS_TEST_ENDED']
                );
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="completedTest"){   // for student dashboard
            $flag = "completedTest";
            $stmt = $conn->prepare('call USP_STU_LOAD_TEST(?,?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $_POST["classId"]); 
            $stmt->bindParam(3, $_POST["sectionId"]);
            $stmt->bindParam(4, $_POST["streamId"]);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr[]=array(
                    'testId'=>$res['INT_TEST_ID'],
                    'testTitle'=>$res['VCH_TEST_TITLE'],
                    'className'=>$res['VCH_CLASS_NAME'],
                    'sectionName'=>$res['VCH_SECTION_NAME'],
                    'streamName'=>$res['VCH_STREAM'],
                    'subName'=>$res['VCH_SUBJECT'],
                    'scheduleDate'=>$res['DTM_SCHEDULE_DATE'],
                    'startTime'=>$res['TEST_START_TIME'],
                    'endTime'=>$res['TEST_END_TIME'],
                    'isTestEnd'=>$res['INT_IS_TEST_ENDED']
                );
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="alreadyAttended"){
            $flag = "alreadyAttended";
            $testId = 0;
            $stuId = $_POST["stu_id"];
            $stuClass = $_POST["stu_class"];
            $stuSection = $_POST["stu_sec"];
            $stuStream = $_POST["stu_stream"];
            $stmt = $conn->prepare('call USP_CHECK_TEST_ATTENDED(?,?,?,?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $testId); 
            $stmt->bindParam(3, $stuId);
            $stmt->bindParam(4, $stuClass);
            $stmt->bindParam(5, $stuSection);
            $stmt->bindParam(6, $stuStream );
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr[]=array(
                    'testId'=>$res['INT_TEST_ID'],
                    'testTitle'=>$res['VCH_TEST_TITLE'],
                    'testDate'=>$res['DTM_SCHEDULE_DATE'],
                    'startTime'=>$res['TEST_START_TIME'],
                    'endTime'=>$res['TEST_END_TIME']
                );
            }
            echo json_encode($arr);
        }

        // it will check if the student is already attended the test or not
        if($_POST["action"]=="testAttended"){ 
            $flag = "testAttended";
            $testId = (int)$_POST["test_id"];
            $stuId = $_POST["stu_id"];
            $stuClass = $_POST["stu_class"];
            $stuSection = $_POST["stu_sec"];
            $stuStream = $_POST["stu_str"];
            $stmt = $conn->prepare('call USP_CHECK_TEST_ATTENDED(?,?,?,?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $testId); 
            $stmt->bindParam(3, $stuId);
            $stmt->bindParam(4, $stuClass);
            $stmt->bindParam(5, $stuSection);
            $stmt->bindParam(6, $stuStream );
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $result=$res["msg"];
            }
            echo json_encode($result);
        }

        // selecting questions for students, shuffle it and assign to students
        /*if($_POST["action"]=="selectQuesForStu"){
            $flag = "selectQuesForStu";
            $creator = "";                  // dummy value
            $stmt = $conn->prepare('call USP_GET_ALL_TEST(?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $_POST["created_by"]); 
            $stmt->bindParam(3, $_POST["test_id"]);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr[]=array(
                    'ques_id'=>$res['INT_QUES_ID']
                );
            }
            shuffle($arr);          // shuffling the array so that each student get shuffled questions
            $size = count($arr);    // counting thee size of the array
            $ids = "";
            $allId = "";
            for($i = 0; $i < $size; $i++){
                $ids = $ids . implode("",$arr[$i]) . ",";
            }
            $allIds = rtrim($ids,",");      // this variable contain the shuffled ids of questions
            
            // now inserting question for the student in the table.
            echo json_encode($allIds);
        }*/

        // it will select the questions randomly and bind it to the label
        if($_POST["action"]=="selectQuesForStu"){
            $flag="selectQuesForStu";
            $testId = (int)$_POST["test_id"];
            $stuId = $_POST['stu_id'];
            $quesCount = 0;         // YOU HAVE TO CHANGE THE PROCEDURE NAME ....
            $stmt = $conn->prepare('call USP_ASSIGN_STU_QUESTIONS(?,?,?,?)');
	        $stmt->bindParam(1, $flag);
	        $stmt->bindParam(2, $testId);
	        $stmt->bindParam(3, $quesCount);
            $stmt->bindParam(4, $stuId);
	        $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr[]=array(
                    'quesId'=>$res['INT_QUESTION_ID']
                );
            }
            echo json_encode($arr);
        }

        // time is up and test ended..
        if($_POST["action"]=="endTest"){
            $flag="endTest";
            $testId=(int)$_POST["t_id"];
            $creator="";
            $stmt = $conn->prepare('call USP_GET_ALL_TEST(?,?,?)');
	        $stmt->bindParam(1, $flag);
	        $stmt->bindParam(2, $creator);
            $stmt->bindParam(3, $testId);
            $stmt->execute();
        }

        if($_POST["action"]=="getTestDetails"){
            $flag="getTestDetails";
            $testId=(int)$_POST["t_id"];
            $creator="";
            $stmt = $conn->prepare('call USP_GET_ALL_TEST(?,?,?)');
	        $stmt->bindParam(1, $flag);
	        $stmt->bindParam(2, $creator);
            $stmt->bindParam(3, $testId);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr['testId']=$res['INT_TEST_ID'];
                $arr['testTitle']=$res['VCH_TEST_TITLE'];
                $arr['testClass']=$res['VCH_CLASS_NAME'];
                $arr['testSection']=$res['VCH_SECTION_NAME'];
                $arr['testSubject']=$res['VCH_SUBJECT'];
                $arr['testStream']=$res['VCH_STREAM'];
                $arr['testDate']=$res['DTM_SCHEDULE_DATE'];  
                $arr['testStartTime']=$res['TEST_START_TIME'];
                $arr['testEndTime']=$res['TEST_END_TIME'];
                $arr['testAssign']=$res['BIT_TEST_ASSIGN'];
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="bindThisQuestion"){
            $flag = "bindThisQuestion";
            $stuId = $_POST["stu_id"];
            $stmt = $conn->prepare('call USP_ASSIGN_STU_QUESTIONS(?,?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $_POST["test_id"]); 
            $stmt->bindParam(3, $_POST["ques_id"]);
            $stmt->bindParam(4, $stuId);
            $stmt->execute();
            $rowCount = $stmt->rowCount();          // counting the number of rows
            foreach($stmt->fetchAll() as $res){
                $arr['quesId']=$res['INT_QUESTION_ID'];
                $arr['quesTitle']=$res['VCH_QUESTION_TITLE'];
                $arr['quesOption1']=$res['VCH_OPTION_1'];
                $arr['quesOption2']=$res['VCH_OPTION_2'];
                $arr['quesOption3']=$res['VCH_OPTION_3'];
                $arr['quesOption4']=$res['VCH_OPTION_4'];
                $arr['quesCorrOption']=$res['VCH_CORRECT_ANSWER'];  
                $arr['stuAnswer']=$res['VCH_STUDENT_ANSWER'];
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="saveThisAnswer"){
            $flag="saveThisAnswer";
            $testId = (int)$_POST["test_id"];
            $stuId = $_POST['stu_id'];
            $quesId = (int)$_POST["ques_id"];
            $correct = (int)$_POST["is_correct"];
            $stuOption = $_POST["student_option"];
            $stmt = $conn->prepare('call USP_SAVE_THIS_ANSWER(?,?,?,?,?,?)');
	        $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $stuId);
	        $stmt->bindParam(3, $testId);
	        $stmt->bindParam(4, $quesId);
            $stmt->bindParam(5, $correct);
            $stmt->bindParam(6, $stuOption);
	        $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr['result']=$res['msg'];
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="checkTestAvail"){
            $testId = (int)$_POST["t_id"];
            $flag="checkTestAvail";
            $creator="";
            $stmt = $conn->prepare('call USP_GET_ALL_TEST(?,?,?)');
	        $stmt->bindParam(1, $flag);
	        $stmt->bindParam(2, $creator);
            $stmt->bindParam(3, $testId);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr['testDate']=$res['DTM_SCHEDULE_DATE'];
                $arr['startTime']=$res['TEST_START_TIME'];
                $arr['endTime']=$res['TEST_END_TIME'];
            }
            echo json_encode($arr);
        }

        // when student clicked on finish test
        if($_POST["action"]=="finishTest"){
            $stuId=$_POST["stu_id"];
            $testId = (int)$_POST["t_id"];
            $flag="finishTest";
            $stmt = $conn->prepare('call USP_FINISH_TEST(?,?,?)');
	        $stmt->bindParam(1, $flag);
	        $stmt->bindParam(2, $stuId);
            $stmt->bindParam(3, $testId);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $result = $res["msg"];
            }
            echo json_encode($result);
        }

        //show the test result
        if($_POST["action"]=="testResult"){
            $flag="testResult";
            $stuId=$_POST["stu_id"];
            $testId = (int)$_POST["test_id"];
            $stmt = $conn->prepare('call USP_TEST_RESULT(?,?,?)');
	        $stmt->bindParam(1, $flag);
	        $stmt->bindParam(2, $stuId);
            $stmt->bindParam(3, $testId);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $result['totMarks'] = $res["INT_TOTAL_MARKS"];
                $result['scoredMarks'] = $res["INT_SCORED_MARKS"];
            }
            echo json_encode($result);
        }

        //load upcoming test for admin 
        if($_POST["action"]=="upcomingTest"){
            $flag="upcomingTest";
            $userId=$_POST["user_id"];
            $stmt = $conn->prepare('call USP_GET_ADMIN_TEA_TEST(?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $userId);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr[]=array(
                    'testId'=>$res['INT_TEST_ID'],
                    'testTitle'=>$res['VCH_TEST_TITLE'],
                    'startTime'=>$res['TEST_START_TIME'],
                    'endTime'=>$res['TEST_END_TIME'],
                    'scheduleDate'=>$res['DTM_SCHEDULE_DATE']
                );
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="creatorCompTest"){
            $flag="creatorCompTest";
            $userId=$_POST["user_id"];
            $stmt = $conn->prepare('call USP_GET_ADMIN_TEA_TEST(?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $userId);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr[]=array(
                    'testId'=>$res['INT_TEST_ID'],
                    'testTitle'=>$res['VCH_TEST_TITLE'],
                    'startTime'=>$res['TEST_START_TIME'],
                    'endTime'=>$res['TEST_END_TIME'],
                    'scheduleDate'=>$res['DTM_SCHEDULE_DATE']
                );
            }
            echo json_encode($arr);
        }

        if($_POST["action"]=="stuAttenTest"){
            $flag="stuAttenTest";
            $userId=$_POST["creator"];
            $testId=$_POST["t_id"];
            $stmt = $conn->prepare('call USP_STU_ATTENDED_TEST(?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $testId);
            $stmt->bindParam(3, $userId);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr[]=array(
                    'stuId'=>$res['INT_ADMISSION_NO'],
                    'stuName'=>$res['VCH_STU_NAME'],
                    'classId'=>$res['VCH_CLASS_NAME'],
                    'sectionId'=>$res['VCH_SECTION_NAME'],
                    'streamId'=>$res['VCH_STREAM'],
                    'phoneNo'=>$res['VCH_STU_PHONE']
                );
            }
            echo json_encode($arr);
        }

    }
}

catch (\Throwable $th) {
    echo $th;
}


?>