<?php
session_start();
if(isset($_SESSION["adminId"]) || isset($_SESSION["teachId"]) ){
    if( ($_SESSION["userType"]="teacher" || $_SESSION["userType"]="admin") ){


?>    

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add student</title>
        <link href="assets/css/style-add-student.css" rel="stylesheet"/>
    </head>
    <body>
        <!--========= calling navbar and sidebar ==========-->
        <?php
            include_once "header.inc.php";
            include_once "sidebar.inc.php";
        ?>

        <section class="add-student" id="addStudentForm">
            <h4>Dashboard/Add Student</h4>

            <div class="form-group row">
                <div class="col-sm-5">
                    <label for="studentName">Name:</label>
                    <input type="text" class="form-control" id="txtStudentName" placeholder="Student Name"/>
                </div>
                <div class="col-sm-5">
                    <label for="studentName">Email:</label>
                    <input type="text" class="form-control" id="txtStudentEmail" placeholder="Student Email"/>
                </div>   
            </div>

            <div class="form-group row">
                <div class="col-sm-5">
                    <label>Class:</label>
                    <select class="form-control" id="ddStudentClass">
                        <option value="0">Select Class</option>
                    </select>
                </div>
                <div class="col-sm-5">
                    <label>Section:</label>
                    <select class="form-control" id="ddlStudentSection">
                        <option value="0">Select Section</option>
                    </select>
                </div>   
            </div>

            <div class="form-group row">
                <div class="col-sm-5">
                    <label>Admission no:</label>
                    <input type="text" class="form-control" id="txtAdmissionNo" placeholder="Admission No"/>
                </div>   
                <div class="col-sm-5">
                    <label>Class Roll No:</label>
                    <input type="text" class="form-control" id="txtClassRollNo" placeholder="Class Roll No"/>
                </div>  
            </div>

            <div class="form-group row">
                <div class="col-sm-5">
                    <input type="submit" class="btn btn-primary btn-sm" onclick="addNewStudent()" value="Insert"/>
                    <input type="submit" class="btn btn-warning btn-sm" value="Reset"/>
                </div>   
            </div>

            <!-- <hr> -->

            <!-- table to bind registered students starts -->
            <table class="table table-striped" id="tblStuAddedToday">
                <h6>Students Added Today: <label id="lblStudentAddedToday"></label> </h6>
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Admission No</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Roll No</th>
                        <th>Class</th>
                        <th>Section</th>   
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
                // binding classes start
                var flag = "loadClass";
                $.ajax({
                    url:"call_service_loaddata.php",
                    method: "POST",
                    data: {
                        action:flag
                    },
                    dataType:"json",
                    success:function(data){
                        //$("#ddStudentClass").empty();

                        $.each(data, function(i, item) {
                            $('#ddStudentClass').append('<option value="' + data[i].classId +
                                '">' + data[i].className + '</option>');
                        });

                    }
                });
                // binding classes end

                // binding section start
                var flag = "loadSection";
                $.ajax({
                    url:"call_service_loaddata.php",
                    method: "POST",
                    data: {
                        action:flag
                    },
                    dataType:"json",
                    success:function(data){
                        //$("#ddlStudentSection").empty();

                        $.each(data, function(i, item) {
                            $('#ddlStudentSection').append('<option value="' + data[i].sectionId +
                                '">' + data[i].sectionName + '</option>');
                        });

                    }
                });
                // binding section end

                bindStuAddedToday();    // binding student added today

        });

        function bindStuAddedToday(){       // funtion to bind students added today start..
            var flag = "stuAddedToday";
            var creator = $("#lblUserUnid").text();     // hidden filed value from header.inc.php
            var todayDate = getdate();              // returns date
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

                    $.each(data, function(i, item) {
                        $('#tblTodayAddBody').append('<tr> <td>'
                            + i+1 +'</td><td>'
                            +data[i].sadd +'</td><td>'
                            +data[i].sname +'</td><td>'
                            +data[i].semail +'</td><td>'
                            +data[i].sclassroll +'</td><td>'
                            +data[i].sclass +'</td><td>'
                            +data[i].ssection +'</td><td>'+
                        '</tr>');
                    });

                }
            });
        }// binding students added today end..

        function addNewStudent(){
            var sname = $("#txtStudentName").val();
            var semail = $("#txtStudentEmail").val();
            //var sclass = $("#ddStudentClass option:selected").text();
            //var ssection = $("#ddlStudentSection option:selected").text();

            var sclassval = $("#ddStudentClass").val();         // it will be used for validation
            var ssecval = $("#ddlStudentSection").val();        // it will be used for validation

            var sadno = $("#txtAdmissionNo").val();
            var classroll = $("#txtClassRollNo").val();
            var creatorId = $("#lblUserUnid").text();     // receiving from header to find user name
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            
            // client side validation
            if(sname.trim()==""){
                $("#txtStudentName").css("border", "1px solid red");
                $("#txtStudentName").attr("placeholder", "* Enter Student name");
                return false;
            }
            else if(semail.trim()==""){
                $("#txtStudentName").css("border", "1px solid #ccc");

                $("#txtStudentEmail").css("border", "1px solid red");
                $("#txtStudentEmail").attr("placeholder", "* Enter Student Email");
                return false;
            }
            else if(!regex.test(semail)){
                $("#txtStudentName").css("border", "1px solid #ccc");

                $("#txtStudentEmail").val("");
                $("#txtStudentEmail").css("border", "1px solid red");
                $("#txtStudentEmail").attr("placeholder", "* Enter Valid Email");
                return false;
            }
            else if(sclassval==0){
                $("#txtStudentEmail").css("border", "1px solid #ccc");

                $("#ddStudentClass").css("border", "1px solid red");
                return false;
            }
            else if(ssecval==0){
                $("#ddStudentClass").css("border", "1px solid #ccc");

                $("#ddlStudentSection").css("border", "1px solid red");
                return false;
            }
            else if(sadno.trim()==""){
                $("#ddlStudentSection").css("border", "1px solid #ccc");

                $("#txtAdmissionNo").css("border", "1px solid red");
                $("#txtAdmissionNo").attr("placeholder", "* Enter Admission No");
                return false;
            }
            else if(classroll.trim()==""){   
                $("#txtAdmissionNo").css("border", "1px solid #ccc");

                $("#txtClassRollNo").css("border", "1px solid red");
                $("#txtClassRollNo").attr("placeholder", "* Enter Class Roll No");
                return false;
            }
            else{   // client side validation clear..
               var flag="insertStudent";
                $.ajax({
                    url:"call_service.php",
                    method:"POST",
                    dataType:"json",
                    data:{action: flag,
                        stuname: sname,
                        stuemail: semail,
                        stuclass: sclassval,
                        stusection: ssecval,
                        stuadno: sadno,
                        classrollno: classroll,
                        createdBy: creatorId},
                    success:function(data){
                        if(data=="0"){
                            alert("Student Already Registered");
                        }
                        else if(data=="1"){
                            alert("Email Already Registered");
                        }
                        else if(data=="2"){
                            alert("Registered Successfully");
                            bindStuAddedToday();    // binding new data to table..
                        }
                    },
                    error:function(xhr, status, error){
                        console.log(error);
                        console.warn(xhr.responseText);
                    }
                });
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