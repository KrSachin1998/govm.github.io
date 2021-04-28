<?php
session_start();
if(isset($_SESSION["adminId"])){
    if($_SESSION["userType"]="admin"){


?>    

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add Teacher</title>
        <link href="assets/css/style-add-teacher.css" rel="stylesheet"/>

    </head>
    <body>
        <!--========= calling navbar and sidebar ==========-->
        <?php
            include_once "header.inc.php";
            include_once "sidebar.inc.php";
        ?>

        <section class="add-teacher" id="addTeacherForm">
            <h4>Dashboard/Add Teacher</h4>

            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="teacherName">Name:</label>
                    <input type="text" class="form-control" id="txtTeacherName" placeholder="Teacher Name"/>
                </div>
                <div class="col-sm-5">
                    <label for="teacherEmail">Email:</label>
                    <input type="text" class="form-control" id="txtTeacherEmail" placeholder="Teacher Email"/>
                </div>   
            </div>

            <div class="form-group row">

                <div class="col-sm-5">
                    <label for="authTeacher">Teacher Can Access</label>
                    <div class="contain-class">

                        <input type="checkbox" id="chkClass1">
                        <label for="vehicle1">Class 1</label>

                        <input type="checkbox" id="chkClass2">
                        <label for="vehicle2">Class 2</label>

                        <input type="checkbox" id="chkClass3">
                        <label for="vehicle3">Class 3</label>

                        <input type="checkbox" id="chkClass4" >
                        <label for="vehicle1">Class 4</label>

                    </div> 

                    <div class="contain-class">

                        <input type="checkbox" id="chkClass5">
                        <label for="vehicle2">Class 5</label>

                        <input type="checkbox" id="chkClass6">
                        <label for="vehicle3">Class 6</label>

                        <input type="checkbox" id="chkClass7">
                        <label for="vehicle1">Class 7</label>

                        <input type="checkbox" id="chkClass8">
                        <label for="vehicle2">Class 8</label>

                    </div>
                    
                    <div class="contain-class">
                    
                        <input type="checkbox" id="chkClass9">
                        <label for="vehicle3">Class 9</label>

                        <input type="checkbox" id="chkClass10">
                        <label for="vehicle1">Class 10</label>

                        <input type="checkbox" id="chkClass11">
                        <label for="vehicle2">Class 11</label>

                        <input type="checkbox" id="chkClass12">
                        <label for="vehicle3">Class 12</label>

                    </div>
                    
                </div>

                <div class="col-sm-5">
                    <label>Teacher Id:</label>
                    <input type="text" class="form-control" id="txtUid" placeholder="Unique Id"/>
                </div>    
            </div>

            <div class="form-group row">
                <div class="col-sm-5">
                    <input type="submit" class="btn btn-primary btn-sm" onclick="addNewTeacher()" value="Insert"/>
                    <input type="submit" class="btn btn-warning btn-sm" value="Reset"/>
                </div>   
            </div>

            <!-- <hr> -->

            <!-- table to bind registered students starts -->
            <table class="table table-striped" id="tblStuAddedToday">
                <h6>Teachers Added Today: <label id="lblStudentAddedToday"></label> </h6>
                <thead>
                    <tr>
                        <th>Unique Id</th>
                        <th>Teacher Name</th>
                        <th>Email Id</th>
                        <th>Password</th>
                        <th>Handle Classes</th>   
                    </tr>
                </thead>

                <tbody id="tblTodayAddBody">
                    <tr>
                        <td>No data found..</td>
                    </tr>
                </tbody>
            </table>   

        </section>

    <!-- Here are some hidden variables to use in the program -->
    

    </body>

    <script src="assets/js/jquery.js"></script>
    <script>
        
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

        $(document).ready(function(){
            bindTeacherToday();
        });

        function bindTeacherToday(){       // funtion to bind teachers added today start..(modifying this)
            var flag = "teaAddedToday";
            var creator = $("#lblUserUnid").text();     // hidden field value from header.inc.php
            var todayDate = getdate();              // returns date
            var tableStart = "<table>";
            var tableEnd = "</table>";
            var trStart = "<tr>";
            var trEnd = "</tr>";
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data: {
                    action:flag,
                    createdBy:creator,
                    tdate : todayDate
                },
                dataType:"json",
                success:function(data){
                    $("#tblTodayAddBody").empty();

                    console.log(data);

                    $.each(data, function(i, item) {
                        $('#tblTodayAddBody').append('<tr> <td>'
                            +data[i].unid +'</td><td>'
                            +data[i].teapass +'</td><td>'
                            +data[i].teaname +'</td><td>'
                            +data[i].teaemail +'</td><td>'
                            +'<div id="containSmallTbl"> <span id="'+data[i].unid+'" onclick="bindListAuthClass(this)">Get Class</span> <table class="ddlSmTbl" id="tbl'+data[i].unid+'"> </table> </div>'+
                        '</td></tr>');
                    });
                },
                error:function(xhr, status, error){
                    console.log(error);
                    console.warn(xhr.responseText);
                }
            });
        }       // binding teachers added today end..

        function bindListAuthClass(get){
            var x = get.id;     // original id of teacher
            var str = "tbl";    
            var tableId = str+x;        // forming the id of the table;
            var idForJquery = "#"+tableId;      // preparing the id for jquery..
            $(idForJquery).empty();  
            var makeAuthClass ="";   
            //$(idForJquery).append("<tr><td>Changed</td></tr>");

            var flag="teachAuthClass";
            $.ajax({
                url:"call_service_loaddata.php",
                method:"POST",
                dataType:"json",
                data:{
                    action:flag,
                    teaId:x
                },
                success:function(data){
                    if(data.c1=="1"){
                        makeAuthClass+="Class 1";
                    }
                    if(data.c2=="1"){
                        makeAuthClass+=", Class 2";
                    }
                    if(data.c3=="1"){
                        makeAuthClass+=", Class 3";
                    }
                    if(data.c4=="1"){
                        makeAuthClass+=", Class 4";
                    }
                    if(data.c5=="1"){
                        makeAuthClass+=", Class 5";
                    }
                    if(data.c6=="1"){
                        makeAuthClass+=", Class 6";
                    }
                    if(data.c7=="1"){
                        makeAuthClass+=", Class 7";
                    }
                    if(data.c8=="1"){
                        makeAuthClass+=", Class 8";
                    }
                    if(data.c9=="1"){
                        makeAuthClass+=", Class 9";
                    }
                    if(data.c10=="1"){
                        makeAuthClass+=", Class 10";
                    }
                    if(data.c11=="1"){
                        makeAuthClass+=", Class 11";
                    }
                    if(data.c12=="1"){
                        makeAuthClass+=", Class 12";
                    }

                    $(idForJquery).append("<tr><td>"+ makeAuthClass +"</td></tr>");
                }
            });
        }

        function resetall(){
            var t_name = $("#txtTeacherName").val("");
            var t_email = $("#txtTeacherEmail").val("");
            var t_unid = $("#txtUid").val("");
            document.getElementById("chkClass1").checked=false;
            document.getElementById("chkClass2").checked=false;
            document.getElementById("chkClass3").checked=false;
            document.getElementById("chkClass4").checked=false;
            document.getElementById("chkClass5").checked=false;
            document.getElementById("chkClass6").checked=false;
            document.getElementById("chkClass7").checked=false;
            document.getElementById("chkClass8").checked=false;
            document.getElementById("chkClass9").checked=false;
            document.getElementById("chkClass10").checked=false;
            document.getElementById("chkClass11").checked=false;
            document.getElementById("chkClass12").checked=false;
        }

        function addNewTeacher(){
            var t_name = $("#txtTeacherName").val();
            var t_email = $("#txtTeacherEmail").val();
            var t_unid = $("#txtUid").val();
            var c1=0,c2=0,c3=0,c4=0,c5=0,c6=0,c7=0,c8=0,c9=0,c10=0,c11=0,c12=0;
            var creatorId = $("#lblUserUnid").text();     // receiving from header to find user name
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            
            var chk1 = document.getElementById("chkClass1");
            var chk2 = document.getElementById("chkClass2");
            var chk3 = document.getElementById("chkClass3");
            var chk4 = document.getElementById("chkClass4");
            var chk5 = document.getElementById("chkClass5");
            var chk6 = document.getElementById("chkClass6");
            var chk7 = document.getElementById("chkClass7");
            var chk8 = document.getElementById("chkClass8");
            var chk9 = document.getElementById("chkClass9");
            var chk10 = document.getElementById("chkClass10");
            var chk11 = document.getElementById("chkClass11");
            var chk12 = document.getElementById("chkClass12");

            if(chk1.checked){
                c1=1;
            }
            if(chk2.checked){
                c2=1;
            }
            if(chk3.checked){
                c3=1;
            }
            if(chk4.checked){
                c4=1;
            }
            if(chk5.checked){
                c5=1;
            }
            if(chk6.checked){
                c6=1;
            }
            if(chk7.checked){
                c7=1;
            }
            if(chk8.checked){
                c8=1;
            }
            if(chk9.checked){
                c9=1;
            }
            if(chk10.checked){
                c10=1;
            }
            if(chk11.checked){
                c11=1;
            }
            if(chk12.checked){
                c12=1;
            }

            // client side validation
            if(t_name.trim()==""){
                $("#txtTeacherName").css("border", "1px solid red");
                $("#txtTeacherName").attr("placeholder", "* Enter Teacher name");
                return false;
            }
            else if(t_email.trim()==""){
                $("#txtTeacherName").css("border", "1px solid #ccc");

                $("#txtTeacherEmail").css("border", "1px solid red");
                $("#txtTeacherEmail").attr("placeholder", "* Enter Student Email");
                return false;
            }
            else if(!regex.test(t_email)){
                $("#txtTeacherName").css("border", "1px solid #ccc");

                $("#txtTeacherEmail").val("");
                $("#txtTeacherEmail").css("border", "1px solid red");
                $("#txtTeacherEmail").attr("placeholder", "* Enter Valid Email");
                return false;
            }
            else if(t_unid.trim()==""){
                $("#txtTeacherEmail").css("border", "1px solid #ccc");

                $("#txtUid").css("border", "1px solid red");
                $("#txtUid").attr("placeholder", "* Enter Admission No");
                return false;
            }
            
            else{   // client side validation clear..
               var flag="insertTeacher";
                $.ajax({
                    url:"call_service.php",
                    method:"POST",
                    dataType:"json",
                    data:{action: flag,
                        tname: t_name,
                        temail: t_email,
                        tunid: t_unid,
                        t_c1: c1,
                        t_c2: c2,
                        t_c3: c3,
                        t_c4: c4,
                        t_c5: c5,
                        t_c6: c6,
                        t_c7: c7,
                        t_c8: c8,
                        t_c9: c9,
                        t_c10: c10,
                        t_c11: c11,
                        t_c12: c12,
                        createdBy: creatorId},
                    success:function(data){
                        if(data=="0"){
                            alert("Teacher Already Registered");
                        }
                        else if(data=="1"){
                            alert("Email Already Registered");
                        }
                        else if(data=="2"){
                            alert("Teacher Registered Successfully");
                            resetall();
                        }
                    },
                    error:function(xhr, status, error){
                        console.log(error);
                        console.warn(xhr.responseText);
                    }
                });
            }


        }

        function closeSmTable(){
           
            //x.style.display="none";
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