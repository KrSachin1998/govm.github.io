<!DOCTYPE html>
<html lang="en">
<head>
    
<title>gvt login</title>
<link rel="stylesheet" href="assets/css/style-index.css"/>


</head>

<body>

<!--========= calling navbar starts ==========-->
<?php
    include_once "header_oth.inc.php";
?>
<!--========= calling navbar ends ==========-->

<!--========= Login box starts ==========-->
<section id="login">

<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>

        <div class="col-sm-4 login-box">
            <h2 class="heading-login">Login</h2>
            <hr>

            <!-- for showing log in error -->
            <div class="error-message" id="showMessage">
                
                
            </div>
            
            <div class="col-sm-12">
                <div class="contain-textbox">
                    <i class='uil uil-user user-icon'></i>
                    <input type="text" class="form-control" id="txtAdmissionNo" placeholder="Admission No" />
                </div>
            </div>
    
            <div class="col-sm-12">
                <div class="contain-textbox">
                    <i class="uil uil-list-ol user-icon"></i>
                    <select name="cars" class="form-control" id="ddlUserRole">
                        <option value="" selected>Select Role</option>
                        <option value="1" >Student</option>
                        <option value="2">Teacher</option>
                        <option value="3">Admin</option>
                      </select>
                    <!-- <input type="text" class="form-control" /> -->
                </div>
            </div>
    
            <div class="col-sm-12">
                <div class="contain-textbox">
                    <i class="uil uil-key-skeleton user-icon"></i>
                    <input type="text" class="form-control" id="txtUserPassword" placeholder="Password" />
                </div>
            </div>

            <div class="col-sm-12 contain-buttons">
                <input type="submit" class="btn btn-primary" onclick="userLogin()" id="btnLogin" value="Login"/>
                <a href="#" id="lblForgetPassword">Forget Password?</a>           
            </div>

        </div>

        <div class="col-sm-4"></div>
    </div>
</div>

</section>
<!--========= Login box ends ==========-->

<!--========= Calling the footer ==========-->
<?php
include_once "footer.inc.php";
?>

</body>

<script src="assets/js/jquery.js"></script>
<script>

    function removeMsg(){
        $("#showMessage").css("display","none");        // removing the message box
    }

    function incorrectPass(){
        $("#showMessage").css("display","block");
        $("#showMessage").html(" <i href='#' class='close' onclick='removeMsg()' aria-label='close'>&times;</i>"+
                                "<i class='uil uil-frown'></i> Incorrect Password");
    }

    function noUserFound(){
        $("#showMessage").css("display","block");
        $("#showMessage").html(" <i href='#' class='close' onclick='removeMsg()' aria-label='close'>&times;</i>"+
                                "<i class='uil uil-frown'></i> No Such User Found");
    }

    function serverSideVal(){
        $("#showMessage").css("display","block");
        $("#showMessage").html(" <i href='#' class='close' onclick='removeMsg()' aria-label='close'>&times;</i>"+
                                "<i class='uil uil-frown'></i> Enter all Data");
    }

    function userLogin(){
        var adno = $("#txtAdmissionNo").val();
        var role = $("#ddlUserRole").val();
        var pass = $("#txtUserPassword").val();
        var flag = "userLogin"

        // client side validation starts..
        if(adno.trim()==""){
            $("#txtAdmissionNo").css("border","1px solid red");      
            $("#txtAdmissionNo").attr("placeholder","* Enter Admission No");
            return false;
        }
        else if(role.trim()==""){
            $("#txtAdmissionNo").css("border","1px solid #ccc");  

            $("#ddlUserRole").css("border","1px solid red");      
            //$("#ddlUserRole").attr("placeholder","* Select Role");
            return false;
        }
        else if(pass.trim()==""){
            $("#ddlUserRole").css("border","1px solid #ccc"); 
            $("#txtAdmissionNo").css("border","1px solid #ccc"); 

            $("#txtUserPassword").css("border","1px solid red");      
            $("#txtUserPassword").attr("placeholder","* Enter Password");
            return false;
        }

        // client side validation ends..
        else{   // all clear so sending the data to server

            $.ajax({
            url:"call_service.php",
            method:"POST",
            dataType: "json",
            data:{action:flag, admissionNo:adno, userRole:role, userPass:pass},
            success:function(data){
                if(data=='11'){
                    serverSideVal();    // server side validation..
                }
                else if(data=='0'){
                    noUserFound();      // no user found..
                }
                else if(data=='1'){
                    incorrectPass();    // incorrect password..
                }
                else if(data=="student" && role=='1'){        // Student 
                    window.location.href = "student_dashboard.php";
                }
                else if(data=="teacher" && role=='2'){        // Teacher
                    window.location.href = "teacher_dashboard.php";
                }
                else if(data=="admin" && role=='3'){        // Admin
                    window.location.href = "admin_dashboard.php";
                }
            },
            error:function(xhr, status, error){
                console.log(xhr);
            }
        });

        }
    }
</script>

</html>
