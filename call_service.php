<?php
include("core/connpdo.php");        // calling connection
use PHPMailer\PHPMailer\PHPMailer;
try {
    
    function sendMail($stuEmaiId,$pass,$adno){            // this function will send mail..     
        
        $subject = "This mail for testing the Mailer Server";
        $body = "Your temporary password is: ".$pass." and Your Admission No is: ".$adno;

        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";

        $mail = new PHPMailer();

        //smtp settings
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "oncodeproject@gmail.com";
        $mail->Password = 'QwertyBUX%toR<%>';
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";

        //email settings
        $myEmail = "oncodeproject@gmail.com";
        $mail->isHTML(true);
        $mail->setFrom($myEmail);                // sender's address
        $mail->addAddress($stuEmaiId);               // receiver's address
        $mail->Subject = ($subject);
        $mail->Body = $body;
        if($mail->send()){
            return "success";
        }
        else
        {
            return "failure";
        }
    }

    if (isset($_POST["action"])){

        if($_POST["action"] == "userLogin"){
            
            // server side validation starts..
            if( ($_POST["admissionNo"]=="") || ($_POST["userRole"]=="") || ($_POST["userPass"]=="") ){
                echo "11"; 
            }
            // server side validation ends
            
            else{
                // checking for the user type..
                if($_POST["userRole"]=="1"){     
                    $flag = "stulogin";                 // STUDENT
                    $stmt = $conn->prepare('call USP_USER_LOGIN(?,?,?)');
                    $stmt->bindParam(1, $flag);
                    $stmt->bindParam(2, $_POST["admissionNo"]);
                    $stmt->bindParam(3, $_POST["userPass"]);
                    $stmt->execute();
                    foreach ($stmt->fetchAll() as $res) {
                        $result = $res["stumsg"];
                    }

                    if($result == '0'){
                        echo json_encode($result);
                    }
                    else if($result == '1'){
                        echo json_encode($result);
                    }
                    else{                           // create session variable here
                        session_start();// we have to start the session when we want to use the session variable
                        $_SESSION["addNo"]= $res["INT_ADMISSION_NO"];
                        $_SESSION["stuName"]= $res["stumsg"];  // since stumsg represent name of student if all clear
                        $_SESSION["userType"]= $res["VCH_USER_TYPE"];
                        $_SESSION["userName"]= $res["stumsg"];
                        $_SESSION["stuClassId"]= $res["INT_CLASS_ID"];
                        $_SESSION["stuSecId"]= $res["INT_SECTION_ID"];
                        $_SESSION["stuStreamId"]=$res["INT_STREAM_ID"];
                        echo json_encode("student");            // sending key to identify user
                    }
                }
                else if($_POST["userRole"]=="2"){       
                    $flag = "teachlogin";                 // TEACHER
                    $stmt = $conn->prepare('call USP_USER_LOGIN(?,?,?)');
                    $stmt->bindParam(1, $flag);
                    $stmt->bindParam(2, $_POST["admissionNo"]);
                    $stmt->bindParam(3, $_POST["userPass"]);
                    $stmt->execute();
                    foreach ($stmt->fetchAll() as $res) {
                        $result = $res["teacmsg"];
                    }
                    if($result == '0'){
                        echo json_encode($result);
                    }
                    else if($result == '1'){
                        echo json_encode($result);
                    }
                    else{
                        session_start();// we have to start the session when we want to use the session variable
                        $_SESSION["teachId"]= $res["VCH_UNI_ID"];
                        $_SESSION["teachName"]= $res["teacmsg"];  // since stumsg represent name of student if all clear
                        $_SESSION["userType"]= $res["VCH_USER_TYPE"];
                        $_SESSION["userName"]= $res["teacmsg"];
                        echo json_encode("teacher");            // sending key to identify user
                    } 
                }
                else if($_POST["userRole"]=="3"){
                    $flag = "adminlogin";                 // ADMIN
                    $stmt = $conn->prepare('call USP_USER_LOGIN(?,?,?)');
                    $stmt->bindParam(1, $flag);
                    $stmt->bindParam(2, $_POST["admissionNo"]);
                    $stmt->bindParam(3, $_POST["userPass"]);
                    $stmt->execute();
                    foreach ($stmt->fetchAll() as $res) {
                        $result = $res["admimsg"];
                    }
                    if($result == '0'){
                        echo json_encode($result);
                    }
                    else if($result == '1'){
                        echo json_encode($result);
                    }
                    else{
                        session_start();// we have to start the session when we want to use the session variable
                        $_SESSION["adminId"]= $res["VCH_UNI_ID"];
                        $_SESSION["adminName"]= $res["admimsg"];  // since stumsg represent name of student if all clear
                        $_SESSION["userType"]= $res["VCH_USER_TYPE"];
                        $_SESSION["userName"]= $res["admimsg"];
                        echo json_encode("admin");          // sending key to identify user
                    }
                }
            }
        }

        // adding new student..
        if($_POST["action"] == "insertStudent"){
            if( $_POST["stuname"]=="" || $_POST["stuemail"]=="" || $_POST["stuclass"]=="" || $_POST["stusection"]=="" || $_POST["stuadno"]=="" ){
                echo "11";  // server side error.. ( 11 represent validation failed )
            }
            else{
                $flag = "insertStudent";
                $rn = rand(10,1000000);
                $tempPass = "stu" . $_POST["classrollno"] . strval($rn);    // temporary password 
                $stmt = $conn->prepare('call USP_REGISTER_STUDENT(?,?,?,?,?,?,?,?,?)');
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $_POST["stuname"]);
                $stmt->bindParam(3, $_POST["stuemail"]);
                $stmt->bindParam(4, $_POST["stuclass"]);
                $stmt->bindParam(5, $_POST["stusection"]);
                $stmt->bindParam(6, $_POST["stuadno"]);
                $stmt->bindParam(7, $_POST["createdBy"]);
                $stmt->bindParam(8, $_POST["classrollno"]);
                $stmt->bindParam(9, $tempPass);
                $stmt->execute();
                foreach ($stmt->fetchAll() as $res) {
                   $result = $res["msg"];                  // getting message from database
                }

                if($result=="2"){       // sending mail if registered successfully
                    $x=sendMail($_POST["stuemail"],$tempPass,$_POST["stuadno"]);
                }
                echo json_encode($result);

            }
        }

        // updating student detail..
        if($_POST["action"] == "updateStudent"){
            if( $_POST["stuname"]=="" || $_POST["stuemail"]=="" || $_POST["stuclass"]=="" || $_POST["stusection"]=="" || $_POST["stuadno"]=="" ){
                echo "11";  // server side error.. ( 11 represent validation failed )
            }
            else{
                $flag = "updateStudent";
                $stmt = $conn->prepare('call USP_UPDATE_STU_DETAILS(?,?,?,?,?,?,?,?,?,?)');
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $_POST["stuname"]);
                $stmt->bindParam(3, $_POST["stuemail"]);
                $stmt->bindParam(4, $_POST["stuclass"]);
                $stmt->bindParam(5, $_POST["stusection"]);
                $stmt->bindParam(6, $_POST["stuadno"]);
                $stmt->bindParam(7, $_POST["classrollno"]);
                $stmt->bindParam(8, $_POST["upBy"]);
                $stmt->bindParam(9, $_POST["upDate"]);
                $stmt->bindParam(10, $_POST["sid"]);
                $stmt->execute();
                
                foreach ($stmt->fetchAll() as $res) {
                   $result = $res["data"];
                }
                echo json_encode($result);
            }
        }

        // adding new teacher..
        if($_POST["action"] == "insertTeacher"){
            if( $_POST["tname"]=="" || $_POST["temail"]=="" || $_POST["tunid"]==""){
                echo "11";  // server side error.. ( 11 represent validation failed )
            }
            else{
                $flag = "insertTeacher";
                $rn = rand(10,1000000);
                $tempPass = "tea" . $_POST["tname"] . strval($rn);    // temporary password 
                $stmt = $conn->prepare('call USP_REGISTER_TEACHER(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $_POST["tname"]);
                $stmt->bindParam(3, $_POST["temail"]);
                $stmt->bindParam(4, $_POST["tunid"]);
                $stmt->bindParam(5, $_POST["t_c1"]);
                $stmt->bindParam(6, $_POST["t_c2"]);
                $stmt->bindParam(7, $_POST["t_c3"]);
                $stmt->bindParam(8, $_POST["t_c4"]);
                $stmt->bindParam(9, $_POST["t_c5"]);
                $stmt->bindParam(10, $_POST["t_c6"]);
                $stmt->bindParam(11, $_POST["t_c7"]);
                $stmt->bindParam(12, $_POST["t_c8"]);
                $stmt->bindParam(13, $_POST["t_c9"]);
                $stmt->bindParam(14, $_POST["t_c10"]);
                $stmt->bindParam(15, $_POST["t_c11"]);
                $stmt->bindParam(16, $_POST["t_c12"]);
                $stmt->bindParam(17, $tempPass);
                $stmt->execute();
                foreach ($stmt->fetchAll() as $res) {
                   $result = $res["msg"];                  // getting message from database
                }

                if($result=="2"){       // sending mail if registered successfully
                    //$x=sendMail($_POST["stuemail"],$tempPass,$_POST["stuadno"]);
                }
                echo json_encode($result);

            }
        }

        if($_POST["action"] == "updateTeacher"){        // updating teacher details..
            $modifyDate = $_POST["upDate"];
            if( $_POST["tname"]=="" || $_POST["temail"]=="" || $_POST["tunid"]==""){    // server side validation.. ( 11 represent validation failed )
                echo "11";  
            }
            else{   
                $flag = "updateTeacher";
                $stmt = $conn->prepare("call USP_UPDATE_TEA_DETAILS(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $_POST["tname"]);
                $stmt->bindParam(3, $_POST["temail"]);
                $stmt->bindParam(4, $_POST["tunid"]);
                $stmt->bindParam(5, $_POST["t_c1"]);
                $stmt->bindParam(6, $_POST["t_c2"]);
                $stmt->bindParam(7, $_POST["t_c3"]);
                $stmt->bindParam(8, $_POST["t_c4"]);
                $stmt->bindParam(9, $_POST["t_c5"]);
                $stmt->bindParam(10, $_POST["t_c6"]);
                $stmt->bindParam(11, $_POST["t_c7"]);
                $stmt->bindParam(12, $_POST["t_c8"]);
                $stmt->bindParam(13, $_POST["t_c9"]);
                $stmt->bindParam(14, $_POST["t_c10"]);
                $stmt->bindParam(15, $_POST["t_c11"]);
                $stmt->bindParam(16, $_POST["t_c12"]);
                $stmt->bindParam(17, $modifyDate);
                $stmt->execute();
                foreach ($stmt->fetchAll() as $res) {
                   $result = $res["msg"];                  // getting message from database
                }
                echo json_encode($result);
            }
        }

        if($_POST["action"] == "createTest"){
            if($_POST["t_title"]=="" || $_POST["t_date"]=="" || $_POST["t_StartTime"]=="" || $_POST["t_class"]=="" || $_POST["t_section"]== "" || $_POST["t_EndTime"]=="" || $_POST["t_stream"]=="" || $_POST["t_subject"]=="" || $_POST["creator"]==""){
                echo "11";         // 11 represents server side validation error
            }
            else{
                $flag = "createTest";
                $stmt = $conn->prepare('call USP_CREATE_TEST(?,?,?,?,?,?,?,?,?,?)');
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $_POST["t_title"]);
                $stmt->bindParam(3, $_POST["t_date"]);
                $stmt->bindParam(4, $_POST["t_EndTime"]); 
                $stmt->bindParam(5, $_POST["t_class"]);
                $stmt->bindParam(6, $_POST["t_section"]); 
                $stmt->bindParam(7, $_POST["t_StartTime"]);
                $stmt->bindParam(8, $_POST["t_stream"]); 
                $stmt->bindParam(9, $_POST["t_subject"]);
                $stmt->bindParam(10, $_POST["creator"]);  
                $stmt->execute();
                foreach($stmt->fetchAll() as $res){
                    $result = $res["msg"];
                }
                echo json_encode($result);
            }
        }

        if($_POST["action"] == "updateTest"){
            if($_POST["t_title"]=="" || $_POST["t_date"]=="" || $_POST["t_StartTime"]=="" || $_POST["t_class"]=="" || $_POST["t_section"]== "" || $_POST["t_EndTime"]=="" || $_POST["t_stream"]=="" || $_POST["t_subject"]=="" || $_POST["updator"]==""){
                echo "11";         // 11 represents server side validation error
            }
            else{
                $flag = "updateTest";
                $stmt = $conn->prepare('call USP_UPDATE_TEST(?,?,?,?,?,?,?,?,?,?,?)');
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $_POST["t_title"]);
                $stmt->bindParam(3, $_POST["t_date"]);
                $stmt->bindParam(4, $_POST["t_StartTime"]); 
                $stmt->bindParam(5, $_POST["t_class"]);
                $stmt->bindParam(6, $_POST["t_section"]); 
                $stmt->bindParam(7, $_POST["t_EndTime"]);
                $stmt->bindParam(8, $_POST["t_stream"]); 
                $stmt->bindParam(9, $_POST["t_subject"]);
                $stmt->bindParam(10, $_POST["updator"]);  
                $stmt->bindParam(11, $_POST["t_testId"]);
                $stmt->execute();
                foreach($stmt->fetchAll() as $res){
                    $result = $res["msg"];
                }
                echo json_encode($result);
            }
        }

        if($_POST["action"] == "insertQuestion"){
            if($_POST["q_title"]=="" || $_POST["option_1"]=="" || $_POST["option_2"]=="" || $_POST["option_3"]=="" || $_POST["option_4"]== "" || $_POST["correct_option"]=="" || $_POST["class"]=="" || $_POST["section"]=="" || $_POST["stream"]=="" || $_POST["subject"]==""){
                echo "11";         // 11 represents server side validation error
            }
            else{
                $flag = "addQuestion"; 
                $stmt = $conn->prepare('call USP_ADD_QUESTION(?,?,?,?,?,?,?,?,?,?,?,?,?)');
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $_POST["test_id"]);
                $stmt->bindParam(3, $_POST["q_title"]);
                $stmt->bindParam(4, $_POST["option_1"]); 
                $stmt->bindParam(5, $_POST["option_2"]);
                $stmt->bindParam(6, $_POST["option_3"]); 
                $stmt->bindParam(7, $_POST["option_4"]);
                $stmt->bindParam(8, $_POST["correct_option"]); 
                $stmt->bindParam(9, $_POST["class"]);
                $stmt->bindParam(10, $_POST["section"]);  
                $stmt->bindParam(11, $_POST["stream"]);
                $stmt->bindParam(12, $_POST["subject"]);
                $stmt->bindParam(13, $_POST["created_by"]);
                $stmt->execute();
                foreach($stmt->fetchAll() as $res){
                    $result = $res["msg"];
                }
                echo json_encode($result);
            }
        }

        if($_POST["action"] == "deleteTest"){
            $flag = "deleteTest";
            $stmt = $conn->prepare('call USP_REMOVE_TEST(?,?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $_POST["test_id"]); 
            $stmt->bindParam(3, $_POST["created_by"]);
            $res=$stmt->execute();
            if($res){
                echo 1;
            }
            else{
                echo 0;
            }
        }

        if($_POST["action"] == "loadAdminProfile"){
            $flag = "loadAdminProfile";
            $adminId = $_POST["admin_id"];
            $stmt = $conn->prepare('call USP_LOAD_FOR_PROFILE(?,?)');
            $stmt->bindParam(1, $flag);
            $stmt->bindParam(2, $adminId);
            $stmt->execute();
            foreach($stmt->fetchAll() as $res){
                $arr['adminUnid'] = $res["VCH_UNI_ID"];
                $arr['adminName'] = $res["VCH_ADMIN_NAME"];
                $arr['adminMobile'] = $res["VCH_MOBILE_NUMBER"];
                $arr['adminEmail'] = $res["VCH_EMAIL_ID"];
                $arr['adminAddress'] = $res["VCH_ADDRESS"];
                $arr['adminCaste'] = $res["VCH_CASTE"];
                $arr['adminCate'] = $res["VCH_CATEGORY"];
                $arr['adminGender'] = $res["VCH_GENDER"];
            }
            echo json_encode($arr);
        }

        if($_POST["action"] == "updateAdminDetails"){
            $flag = "updateAdminDetails";
            $adminId = $_POST["admin_id"];
            $adminName = $_POST["admin_name"];
            $adminNo = $_POST["admin_mob"];
            $adminEmail = $_POST["admin_email"];
            $adminAdd = $_POST["admin_add"];
            $adminCaste = $_POST["admin_caste"];
            $adminCateg = $_POST["admin_category"];
            $adminGen = $_POST["admin_gen"];

            // checking for server side validation
            if($adminId=="" || $adminName=="" || $adminNo=="" || $adminEmail=="" || $adminAdd=="" || $adminCaste=="" || $adminCateg=="" || $adminGen==""){
                echo json_encode("11");
            }   
            // server side validation passed
            else{
                $stmt = $conn->prepare('call USP_UPDATE_ADMIN(?,?,?,?,?,?,?,?,?)');
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $adminId);
                $stmt->bindParam(3, $adminName);
                $stmt->bindParam(4, $adminNo);
                $stmt->bindParam(5, $adminEmail);
                $stmt->bindParam(6, $adminAdd);
                $stmt->bindParam(7, $adminCaste);
                $stmt->bindParam(8, $adminCateg);
                $stmt->bindParam(9, $adminGen);
                $stmt->execute();
                foreach($stmt->fetchAll() as $res){
                    $result = $res['msg'];
                }
                echo json_encode($result);
            }
        }

    }
} 


catch (\Throwable $th) {
    echo $th;
}

?>