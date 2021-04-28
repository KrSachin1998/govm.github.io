<?php
session_start();
if(isset($_SESSION["adminId"]) || isset($_SESSION["teachId"]) ){
    if( ($_SESSION["userType"]="teacher" || $_SESSION["userType"]="admin") ){
?>    

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Student Details</title>
        <link href="assets/css/style-student-details.css" rel="stylesheet"/>
    </head>
    <body>
        <!--========= calling navbar and sidebar ==========-->
        <?php
            include_once "header.inc.php";
            include_once "sidebar.inc.php";
        ?>

        <section class="student-details" id="studentDetails">

            <!-- it contains modal for edit student details -->
            <div class="contains-modal">

                <!-- <button id="myBtn" onclick="openModal()">Open Modal</button> -->
            
                <!-- The Modal -->
                <div id="modalEditStuDetails" class="modal-edit-stu-details">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Details of <label id="lblToShowUserEdit" ></label></h2>
                            <span class="close" onclick="closeModal()">&times;</span>
                            
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="studentName">Name:</label>
                                    <input type="text" class="form-control" id="txtEditStuName" placeholder="Student Name"/>
                                </div>
                                <div class="col-sm-6">
                                    <label for="studentName">Email:</label>
                                    <input type="text" class="form-control" id="txtEditStuEmail" placeholder="Student Email"/>
                                </div>    
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label>Class:</label>
                                    <select class="form-control" id="ddlEditStuClass">
                                        <option value="0">Select Class</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Section:</label>
                                    <select class="form-control" id="ddlEditStuSection">
                                        <option value="0">Select Section</option>
                                    </select>
                                </div>  
                                   
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label>Admission no:</label>
                                    <input type="text" class="form-control" id="txtEditStuAddNo" placeholder="Admission No"/>
                                </div>
                                <div class="col-sm-6">
                                    <label>Class Roll No:</label>
                                    <input type="text" class="form-control" id="txtEditStuRollNo" placeholder="Class Roll No"/>
                                </div> 
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input type="submit" id="btnEditStuDetails" onclick="updateStuDetails()" class="btn btn-sm btn-warning" value="Update"/>
                                    <input type="submit" id="btnResendMail" class="btn btn-sm btn-primary" value="Resend Mail"/>
                                </div>
                            </div>

                        </div>
                        <!--<div class="modal-footer">
                            
                        </div>-->
                    </div>
                </div>
            </div>
            <!-- edit student details modal ends -->


            <h4>Dashboard/Student Details</h4>
            <h6 class="help-text">Filter</h6>
            <div class="form-group row">

                <div class="col-sm-4">
                    <label for="studentName">Name:</label>
                    <input type="text" class="form-control" name="txtStudentName" id="txtStudentName" placeholder="Student Name"/>
                </div>
                <div class="col-sm-4">
                    <label for="studentName">Email:</label>
                    <input type="text" class="form-control" name="txtStudentEmail" id="txtStudentEmail" placeholder="Student Email"/>
                </div>   
                <div class="col-sm-4">
                    <label>Admission no:</label>
                    <input type="text" class="form-control" name="txtAdmissionNo" id="txtAdmissionNo" placeholder="Admission No"/>
                </div> 

            </div>

            <div class="form-group row">

                <div class="col-sm-4">
                    <label>Class Roll No:</label>
                    <input type="text" class="form-control" name="txtClassRollNo" id="txtClassRollNo" placeholder="Class Roll No"/>
                </div>  
                <div class="col-sm-4">
                    <label>Class:</label>
                    <select class="form-control" name="ddlStudentClass" id="ddlStudentClass">
                        <option value="0">Select Class</option>
                    </select>
                </div>

                <div class="col-sm-4">
                    <label>Section:</label>
                    <select class="form-control" name="ddlStudentSection" id="ddlStudentSection">
                        <option value="0">Select Section</option>
                    </select>
                </div>   

            </div>

            <div class="form-group row">

                <div class="col-sm-5">
                    <label>Registered From:</label>
                    <input type="date" class="form-control" name="dtmCreatedDate" id="dtmCreatedDate"/>
                </div>
                
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <input type="submit" class="btn btn-primary btn-sm" id="searchStudents" onclick="bindStuDetails()" value="Search"/>
                    <input type="submit" class="btn btn-warning btn-sm" id="resetFields" onclick="resetFields()" value="Reset"/>
                </div>   
            </div>

            <!-- <hr> -->

            <!-- table to bind students details -->
            <table class="table table-striped" id="tblStudentDetails">
                <h6>Students Details: <label id="lblStudentAddedToday"></label> </h6>
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Admission No</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Roll No</th>
                        <th>Class</th>
                        <th>Section</th>   
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="tblStuDetails">
                    <tr>
                        <td>No data found..</td>
                    </tr>
                </tbody>
            </table>
        </section>

    <!-- Here are some hidden variables to use in the program -->
    <input type="text" id="txtHiddenUserId" style="display:none"/>  <!-- it is used for update used info through modal popup -->
    <input typr="text" id="txtHiddenStuQuery" style="display:none"/>
    </body>

    <script src="assets/js/jquery.js"></script>
    <script>

        $(document).ready(function(){
            toBindClass();
            toBindSection();
        });

        function toBindClass(){     // function to bind classes
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
                        $('#ddlStudentClass').append('<option value="' + data[i].classId +
                            '">' + data[i].className + '</option>');  // binding class for filter section

                        $('#ddlEditStuClass').append('<option value="' + data[i].classId +
                            '">' + data[i].className + '</option>');  // binding class to edit student modal

                    });

                },
                error:function(xhr, status, error){
                    console.log(error);
                    console.warn(xhr.responseText);
                }
            });
        }

        function toBindSection(){       // function to bind section
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
                        $('#ddlStudentSection').append('<option value="' + data[i].sectionId +
                        '">' + data[i].sectionName + '</option>');     // binding sections to filter section
                        
                        $('#ddlEditStuSection').append('<option value="' + data[i].sectionId +
                        '">' + data[i].sectionName + '</option>');     // binding section to edit details popup 

                    });
                },
                error:function(xhr, status, error){
                    console.log(error);
                    console.warn(xhr.responseText);
                }
            });
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modalEditStuDetails) {
                modalEditStuDetails.style.display = "none";
            }
        }

        // function to open modal
        function openModal(){
            var modal = document.getElementById("modalEditStuDetails");
            modal.style.display = "block";

        }

        // function to close modal
        function closeModal(){
            var modal = document.getElementById("modalEditStuDetails");
            modal.style.display = "none";
        }

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

        function resetFields(){     // function to reset all fields to null after insert or update..
            $("#txtStudentName").val("");
            $("#txtStudentEmail").val("");
            $("#txtAdmissionNo").val("");
            $("#txtClassRollNo").val("");
        }

        function editStuDetails(id){      //  bind student data to modal popup to edit it.
            // opening the modal
            var modal = document.getElementById("modalEditStuDetails");
            modal.style.display = "block";

            var stuId = id;
            $("#txtHiddenUserId").val(id);   // assigning id of student of which we want to edit info to hidden field to use it further
            var flag="getStuDetailToEdit";

            $.ajax({
                url:"call_service_loaddata.php",
                method:"POST",
                data:{
                    action:flag,
                    id:stuId
                },
                dataType:"json",
                success:function(data){
                    // resetting all the fields..
                    $("#txtEditStuName").val("");
                    $("#txtEditStuEmail").val("");
                    $("#txtEditStuAddNo").val("");
                    $("#txtEditStuRollNo").val("");
                    $("#lblToShowUserEdit").text("");

                    $("#txtEditStuName").val(data.sname);
                    $("#txtEditStuEmail").val(data.semail);
                    $("#txtEditStuAddNo").val(data.sadd);
                    $("#txtEditStuRollNo").val(data.sclassroll);
                    $("#lblToShowUserEdit").text(data.sname);

                    var stuClass = data.sclass;
                    var stuSection = data.ssection;
                    var select = document.getElementById("ddlEditStuClass");
                    for (var i = 0; i < select.length; i++) 
					{                     //iterating through each value of ddl and checking the matching value...
                        var option = select.options[i].value;
                        if (option == stuClass) {
                            var option = $('#ddlEditStuClass').children(
                                'option[value="' + option + '"]');
                            option.attr('selected', true);
                        }
                    }

                    var select = document.getElementById("ddlEditStuSection");
                    for (var i = 0; i < select.length; i++) 
					{                     //iterating through each value of ddl and checking the matching value...
                        var option = select.options[i].value;
                        if (option == stuSection) {
                            var option = $('#ddlEditStuSection').children(
                                'option[value="' + option + '"]');
                            option.attr('selected', true);
                        }
                    }

                }
            });
        }
        
        function bindStuDetails(){          // bind data on applying filter
            var flag = "bindStuDetails";
            var filStuName = $("#txtStudentName").val();
            var filStuEmail = $("#txtStudentEmail").val();
            var filAddNo = $("#txtAdmissionNo").val();
            var filClassRoll = $("#txtClassRollNo").val();
            //var filStuClass = $("#ddlStudentClass option:selected").text();
            //var filStuSection = $("#ddlStudentSection option:selected").text();

            var filClassVal = $("#ddlStudentClass").val();
            var filSecVal = $("#ddlStudentSection").val();

            var filCreatedDate = $("#dtmCreatedDate").val();

            var columns="";                            
            var builtQuery="SELECT INT_STU_ID,VCH_STU_NAME,VCH_STU_EMAIL,INT_ADMISSION_NO,INT_CLASS_ROLLNO,INT_CLASS_ID,INT_SECTION_ID FROM m_students WHERE VCH_USER_TYPE='student'";
            if(filStuName!=""){
                columns+=" AND VCH_STU_NAME='"+filStuName+"'";
            }
            if(filStuEmail!=''){
                columns+=" AND VCH_STU_EMAIL='"+filStuEmail+"'";
            }
            if(filAddNo!=0){
                columns+=" AND INT_ADMISSION_NO="+filAddNo+"";
            }
            if(filClassRoll!=0){
                columns+=" AND INT_CLASS_ROLLNO="+filClassRoll+"";
            }
            if(filClassVal!='0'){
                columns+=" AND INT_CLASS_ID='"+filClassVal+"'";
            }
            if(filSecVal!='0'){
                columns+=" AND INT_SECTION_ID='"+filSecVal+"'";
            }
            if(filCreatedDate!=''){
                columns+=" AND DATE(DTM_CREATED_DATE)='"+filCreatedDate+"'";
            }
            builtQuery+=columns+";";
            $("#txtHiddenStuQuery").val(builtQuery);         // assigning this search query to hidden textbox so that it can be used further.
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data: {
                    action:flag,
                    query:builtQuery
                },
                dataType:"json",
                success:function(data){
                    $("#tblStuDetails").empty();

                    $.each(data, function(i, item) {
                        $('#tblStuDetails').append('<tr> <td>'
                            +i+1 +'</td><td>'
                            +data[i].sadd+'</td><td>'
                            +data[i].sname +'</td><td>'
                            +data[i].semail +'</td><td>'
                            +data[i].sclassroll +'</td><td>'
                            +data[i].sclass +'</td><td>'
                            +data[i].ssection +'</td><td>'
                            +'<input type="submit" class="btn btn-sm btn-primary" id="'+data[i].sid+'" onclick="editStuDetails(this.id)" value="Edit"/>' 
                            +'<input type="submit" class="btn btn-sm btn-danger" id="'+data[i].sid+'" onclick="blockStudent(this.id)" value="Block"/> </td>'
                            +'</tr>');
                    });
                }
            });
        } 

        function duplBindDetails(searchquery){         // duplicate function to bind data after update when provide query.
            $("#txtHiddenStuQuery").val(searchquery);         // assigning this search query to hidden textbox so that it can be used further.
            var flag = "bindStuDetails";
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data: {
                    action:flag,
                    query:searchquery
                },
                dataType:"json",
                success:function(data){
                    $("#tblStuDetails").empty();

                    $.each(data, function(i, item) {
                        $('#tblStuDetails').append('<tr> <td>'
                            +i+1 +'</td><td>'
                            +data[i].sadd+'</td><td>'
                            +data[i].sname +'</td><td>'
                            +data[i].semail +'</td><td>'
                            +data[i].sclassroll +'</td><td>'
                            +data[i].sclass +'</td><td>'
                            +data[i].ssection +'</td><td>'
                            +'<input type="submit" class="btn btn-sm btn-primary" id="'+data[i].sid+'" onclick="editStuDetails(this.id)" value="Edit"/>' 
                            +'<input type="submit" class="btn btn-sm btn-danger" id="'+data[i].sid+'" onclick="blockStudent(this.id)" value="Block"/> </td>'
                            +'</tr>');
                    });
                }
            });
        }

        function allBoarderDefault(){   // will change the bordercolour to default of textbox and ddl
            $("#txtEditStuName").css("border", "1px solid #ccc");
            $("#txtEditStuEmail").css("border", "1px solid #ccc");
            $("#ddlEditStuClass").css("border", "1px solid #ccc");
            $("#ddlEditStuSection").css("border", "1px solid #ccc");
            $("#txtEditStuAddNo").css("border", "1px solid #ccc");
            $("#txtEditStuRollNo").css("border", "1px solid #ccc");
        }

        function updateStuDetails(){    // it will update the user detail in popup
            var sname = $("#txtEditStuName").val();
            var semail = $("#txtEditStuEmail").val();
            var sclass = $("#ddlEditStuClass").val();
            var ssection = $("#ddlEditStuSection").val();
            var sadno = $("#txtEditStuAddNo").val();
            var classroll = $("#txtEditStuRollNo").val();
            var creatorId = $("#lblUserUnid").text();     // receiving from header to find user name
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            var updateDate =  getdate();
            // client side validation
            if(sname.trim()==""){
                allBoarderDefault();
                $("#txtEditStuName").css("border", "1px solid red");
                $("#txtEditStuName").attr("placeholder", "* Enter Student name");
                return false;
            }
            else if(semail.trim()==""){
                allBoarderDefault();

                $("#txtEditStuEmail").css("border", "1px solid red");
                $("#txtEditStuEmail").attr("placeholder", "* Enter Student Email");
                return false;
            }
            else if(!regex.test(semail)){
                allBoarderDefault();

                $("#txtEditStuEmail").val("");
                $("#txtEditStuEmail").css("border", "1px solid red");
                $("#txtEditStuEmail").attr("placeholder", "* Enter Valid Email");
                return false;
            }
            else if(sclass==0){
                allBoarderDefault();

                $("#ddlEditStuClass").css("border", "1px solid red");
                return false;
            }
            else if(ssection==0){
                allBoarderDefault();

                $("#ddlEditStuSection").css("border", "1px solid red");
                return false;
            }
            else if(sadno.trim()==""){
                allBoarderDefault();

                $("#txtEditStuAddNo").css("border", "1px solid red");
                $("#txtEditStuAddNo").attr("placeholder", "* Enter Admission No");
                return false;
            }
            else if(classroll.trim()==""){   
                allBoarderDefault();

                $("#txtEditStuRollNo").css("border", "1px solid red");
                $("#txtEditStuRollNo").attr("placeholder", "* Enter Class Roll No");
                return false;
            }
            else{   // client side validation clear..
                allBoarderDefault();
                var stuId = $("#txtHiddenUserId").val();       // getting id from hidden field
                var flag="updateStudent";
                $.ajax({
                    url:"call_service.php",
                    method:"POST",
                    dataType:"json",
                    data:{action: flag,
                        stuname: sname,
                        stuemail: semail,
                        stuclass: sclass,
                        stusection: ssection,
                        stuadno: sadno,
                        classrollno: classroll,
                        upBy: creatorId,
                        upDate: updateDate,
                        sid:stuId},
                    success:function(data){
                        if(data=="11"){
                            alert("something went wrong, try again later")
                        }
                        if(data=="1"){
                            var hiddenQuery = $("#txtHiddenStuQuery").val();  // getting the query to bind data again after update
                            duplBindDetails(hiddenQuery);      // this function will again bind the updated data
                            alert("update successfully");
                        }
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