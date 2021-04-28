<?php
session_start();
if(isset($_SESSION["adminId"]) || isset($_SESSION["teachId"]) ){
    if( ($_SESSION["userType"]="teacher" || $_SESSION["userType"]="admin") ){
?>    

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Teacher Details</title>
        <link href="assets/css/style-teacher-details.css" rel="stylesheet"/>
    </head>
    <body>
        <!--========= calling navbar and sidebar ==========-->
        <?php
            include_once "header.inc.php";
            include_once "sidebar.inc.php";
        ?>

        <section class="teacher-details" id="teacherDetails">

            <!-- it contains modal for edit student details -->
            <div class="contains-modal">

                <!-- <button id="myBtn" onclick="openModal()">Open Modal</button> -->
            
                <!-- The Modal -->
                <div id="modalEditTeaDetails" class="modal-edit-tea-details">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Details of <label id="lblToShowUserEdit" ></label></h2>
                            <span class="close" onclick="closeModal()">&times;</span>
                            
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="teacherName">Name:</label>
                                    <input type="text" class="form-control" id="txtEditTeaName" placeholder="Teacher Name"/>
                                </div>
                                <div class="col-sm-6">
                                    <label for="teacherEmail">Email:</label>
                                    <input type="text" class="form-control" id="txtEditTeaEmail" placeholder="Teacher Email"/>
                                </div>    
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="teacherUnid">Unique Id:</label>
                                    <input type="text" class="form-control" id="txtEditTeaUnid" placeholder="Teacher Unique Id"/>
                                </div>   
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10">

                                    <label for="authTeacher">Handles:</label>
                                    <div class="contain-class">

                                        <input type="checkbox" id="chkEditClass1">
                                        <label >Class 1</label>

                                        <input type="checkbox" id="chkEditClass2">
                                        <label >Class 2</label>

                                        <input type="checkbox" id="chkEditClass3">
                                        <label >Class 3</label>

                                        <input type="checkbox" id="chkEditClass4" >
                                        <label >Class 4</label>

                                    </div> 

                                    <div class="contain-class">

                                        <input type="checkbox" id="chkEditClass5">
                                        <label >Class 5</label>

                                        <input type="checkbox" id="chkEditClass6">
                                        <label >Class 6</label>

                                        <input type="checkbox" id="chkEditClass7">
                                        <label >Class 7</label>

                                        <input type="checkbox" id="chkEditClass8">
                                        <label >Class 8</label>

                                    </div>
                    
                                    <div class="contain-class">
                                    
                                        <input type="checkbox" id="chkEditClass9">
                                        <label >Class 9</label>

                                        <input type="checkbox" id="chkEditClass10">
                                        <label >Class 10</label>

                                        <input type="checkbox" id="chkEditClass11">
                                        <label >Class 11</label>

                                        <input type="checkbox" id="chkEditClass12">
                                        <label >Class 12</label>

                                    </div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input type="submit" id="btnEditTeaDetails" onclick="updateTeaDetails()" class="btn btn-sm btn-warning" value="Update"/>
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


            <h4>Dashboard/Teacher Details</h4>
            <h6 class="help-text">Filter</h6>
            <div class="form-group row">

                <div class="col-sm-4">
                    <label>Name:</label>
                    <input type="text" class="form-control" id="txtTeacherName" placeholder="Teacher Name"/>
                </div>
                <div class="col-sm-4">
                    <label>Email:</label>
                    <input type="text" class="form-control" id="txtTeacherEmail" placeholder="Teacher Email"/>
                </div>   
                <div class="col-sm-4">
                    <label>Teacher Id:</label>
                    <input type="text" class="form-control" id="txtTeacherId" placeholder="Teacher Id"/>
                </div> 

            </div>

            <div class="form-group row">

                <div class="col-sm-5">
                    <label>Registered From:</label>
                    <input type="date" class="form-control" name="dtmCreatedDate" id="dtmCreatedDate"/>
                </div>
                
                <div class="col-sm-6">
                    <label for="authTeacher">Handles</label>
                    <div class="contain-class">

                        <input type="checkbox" id="chkClass1">
                        <label >Class 1</label>

                        <input type="checkbox" id="chkClass2">
                        <label >Class 2</label>

                        <input type="checkbox" id="chkClass3">
                        <label >Class 3</label>

                        <input type="checkbox" id="chkClass4" >
                        <label >Class 4</label>

                    </div> 

                    <div class="contain-class">

                        <input type="checkbox" id="chkClass5">
                        <label >Class 5</label>

                        <input type="checkbox" id="chkClass6">
                        <label >Class 6</label>

                        <input type="checkbox" id="chkClass7">
                        <label >Class 7</label>

                        <input type="checkbox" id="chkClass8">
                        <label >Class 8</label>

                    </div>
                    
                    <div class="contain-class">
                    
                        <input type="checkbox" id="chkClass9">
                        <label >Class 9</label>

                        <input type="checkbox" id="chkClass10">
                        <label >Class 10</label>

                        <input type="checkbox" id="chkClass11">
                        <label >Class 11</label>

                        <input type="checkbox" id="chkClass12">
                        <label >Class 12</label>

                    </div>

                </div>

            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <input type="submit" class="btn btn-primary btn-sm" id="searchTeacher" onclick="bindTeaDetails()" value="Search"/>
                    <input type="submit" class="btn btn-warning btn-sm" id="resetFields" onclick="resetFields()" value="Reset"/>
                </div>   
            </div>

            <!-- <hr> -->

            <!-- table to bind students details -->
            <table class="table table-striped" id="tblStudentDetails">
                <h6>Teacher Details: <label id="lblTeacherAddedToday"></label> </h6>
                <thead>
                    <tr>
                        <th>Unique Id</th>
                        <th>Teacher Name</th>
                        <th>Email</th>
                        <th>Handle Class</th> 
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="tblTeaDetails">
                    <tr>
                        <td>No data found..</td>
                    </tr>
                </tbody>
            </table>
        </section>

    <!-- Here are some hidden variables to use in the program -->
    <input type="text" id="txtHiddenUserId" style="display:none"/>  <!-- it is used for update used info through modal popup -->
    <input type="text" id="txtHiddenTeaQuery" style="display:none"/>
    </body>

    <script src="assets/js/jquery.js"></script>
    <script>

        $(document).ready(function(){

        });

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modalEditTeaDetails) {
                modalEditTeaDetails.style.display = "none";
            }
        }

        // function to open modal
        function openModal(){
            var modal = document.getElementById("modalEditTeaDetails");
            modal.style.display = "block";

        }

        // function to close modal
        function closeModal(){
            var modal = document.getElementById("modalEditTeaDetails");
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

        function editTeaDetails(id){      //  bind teacher data to modal popup to edit it.
            // opening the modal
            var modal = document.getElementById("modalEditTeaDetails");
            modal.style.display = "block";

            var stuId = id;
            $("#txtHiddenUserId").val(id);   // assigning id of student of which we want to edit info to hidden field to use it further
            var flag="getTeaDetailToEdit";

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
                    $("#txtEditTeaName").val("");
                    $("#txtEditTeaEmail").val("");
                    $("#txtEditTeaUnid").val("");
                    $("#lblToShowUserEdit").text("");

                    $("#txtEditTeaName").val(data.tname);
                    $("#txtEditTeaEmail").val(data.temail);
                    $("#txtEditTeaUnid").val(data.tUnId);
                    $("#lblToShowUserEdit").text(data.tname);

                    // now checking the checkboxes based on the class teacher can handle.
                    var chk1 = document.getElementById("chkEditClass1");
                    var chk2 = document.getElementById("chkEditClass2");
                    var chk3 = document.getElementById("chkEditClass3");
                    var chk4 = document.getElementById("chkEditClass4");
                    var chk5 = document.getElementById("chkEditClass5");
                    var chk6 = document.getElementById("chkEditClass6");
                    var chk7 = document.getElementById("chkEditClass7");
                    var chk8 = document.getElementById("chkEditClass8");
                    var chk9 = document.getElementById("chkEditClass9");
                    var chk10 = document.getElementById("chkEditClass10");
                    var chk11 = document.getElementById("chkEditClass11");
                    var chk12 = document.getElementById("chkEditClass12");

                    if(data.class1==1){
                        chk1.checked=true;
                    }
                    if(data.class2==1){
                        chk2.checked=true;
                    }
                    if(data.class3==1){
                        chk3.checked=true;
                    }
                    if(data.class4==1){
                        chk4.checked=true;
                    }
                    if(data.class5==1){
                        chk5.checked=true;
                    }
                    if(data.class6==1){
                        chk6.checked=true;
                    }
                    if(data.class7==1){
                        chk7.checked=true;
                    }
                    if(data.class8==1){
                        chk8.checked=true;
                    }
                    if(data.class9==1){
                        chk9.checked=true;
                    }
                    if(data.class10==1){
                        chk10.checked=true;
                    }
                    if(data.class11==1){
                        chk11.checked=true;
                    }
                    if(data.class12==1){
                        chk12.checked=true;
                    }


                }
            });
        }
        
        function bindTeaDetails(){          // bind data on applying filter
            var flag = "bindTeaDetails";
            var filTeaName = $("#txtTeacherName").val();
            var filTeaEmail = $("#txtTeacherEmail").val();
            var filTeaUnId = $("#txtTeacherId").val();

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

            var filCreatedDate = $("#dtmCreatedDate").val();

            var columns="";                            
            var builtQuery="SELECT m.VCH_TEACH_NAME,m.VCH_TEACH_EMAIL,m.VCH_UNI_ID,t.CLASS_1,t.CLASS_2,t.CLASS_3,t.CLASS_4,t.CLASS_5,t.CLASS_6,t.CLASS_7,t.CLASS_8,t.CLASS_9,t.CLASS_10,t.CLASS_11,t.CLASS_12 FROM m_teachers m inner join t_teacher_auth t on m.VCH_UNI_ID=t.VCH_UNI_ID WHERE m.VCH_USER_TYPE='teacher'";
            if(filTeaName!=""){
                columns+=" AND m.VCH_TEACH_NAME='"+filTeaName+"'";
            }
            if(filTeaEmail!=''){
                columns+=" AND m.VCH_TEACH_EMAIL='"+filTeaEmail+"'";
            }
            if(filTeaUnId!=0 && filTeaUnId!=""){
                columns+=" AND m.VCH_UNI_ID='"+filTeaUnId+"'";
            }
            if(chk1.checked){
                columns+=" AND t.CLASS_1=1";
            }
            if(chk2.checked){
                columns+=" AND t.CLASS_2=1";
            }
            if(chk3.checked){
                columns+=" AND t.CLASS_3=1";
            }
            if(chk4.checked){
                columns+=" AND t.CLASS_4=1";
            }
            if(chk5.checked){
                columns+=" AND t.CLASS_5=1";
            }
            if(chk6.checked){
                columns+=" AND t.CLASS_6=1";
            }
            if(chk7.checked){
                columns+=" AND t.CLASS_7=1";
            }
            if(chk8.checked){
                columns+=" AND t.CLASS_8=1";
            }
            if(chk9.checked){
                columns+=" AND t.CLASS_9=1";
            }
            if(chk10.checked){
                columns+=" AND t.CLASS_10=1";
            }
            if(chk11.checked){
                columns+=" AND t.CLASS_11=1";
            }
            if(chk12.checked){
                columns+=" AND t.CLASS_12=1";
            }
        
            if(filCreatedDate!=''){
                columns+=" AND DATE(m.DTM_CREATED_DATE)='"+filCreatedDate+"'";
            }
            builtQuery+=columns+";";
            $("#txtHiddenTeaQuery").val(builtQuery);         // assigning this search query to hidden textbox so that it can be used further.
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data: {
                    action:flag,
                    query:builtQuery
                },
                dataType:"json",
                success:function(data){
                    if(data!=0){
                        $("#tblTeaDetails").empty();
                        $.each(data, function(i, item) {
                        $('#tblTeaDetails').append('<tr> <td>'
                            +data[i].tUnId+'</td><td>'
                            +data[i].tname +'</td><td>'
                            +data[i].temail +'</td><td>'

                            +'<div id="containSmallTbl"> <span id="'+data[i].tUnId+'" onclick="bindListAuthClass(this)">Get Class</span> <table class="ddlSmTbl" id="tbl'+data[i].tUnId+'"> </table> </div></td><td>'

                            +'<input type="submit" class="btn btn-sm btn-primary" id="'+data[i].tUnId+'" onclick="editTeaDetails(this.id)" value="Edit"/>' 
                            +'<input type="submit" class="btn btn-sm btn-danger" id="'+data[i].tUnId+'" onclick="blockTeadent(this.id)" value="Block"/> </td>'
                            +'</tr>');
                        });
                    }
                },
                error:function(xhr, status, error){
                    console.log(error);
                    console.warn(xhr.responseText);
                }
            });
        }

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

        function duplBindDetails(searchquery){         // duplicate function to bind data after update when provide query.
            $("#txtHiddenTeaQuery").val(searchquery);         // assigning this search query to hidden textbox so that it can be used further.
            var flag = "bindTeaDetails";
            $.ajax({
                url:"call_service_loaddata.php",
                method: "POST",
                data: {
                    action:flag,
                    query:searchquery
                },
                dataType:"json",
                success:function(data){
                    $("#tblTeaDetails").empty();
                    $.each(data, function(i, item) {
                        $('#tblTeaDetails').append('<tr> <td>'
                            +data[i].tUnId+'</td><td>'
                            +data[i].tname +'</td><td>'
                            +data[i].temail +'</td><td>'

                            +'<div id="containSmallTbl"> <span id="'+data[i].tUnId+'" onclick="bindListAuthClass(this)">Get Class</span> <table class="ddlSmTbl" id="tbl'+data[i].tUnId+'"> </table> </div></td><td>'

                            +'<input type="submit" class="btn btn-sm btn-primary" id="'+data[i].tUnId+'" onclick="editTeaDetails(this.id)" value="Edit"/>' 
                            +'<input type="submit" class="btn btn-sm btn-danger" id="'+data[i].tUnId+'" onclick="blockTeadent(this.id)" value="Block"/> </td>'
                            +'</tr>');
                    });
                }
            });
        }

        function allBoarderDefault(){   // will change the bordercolour to default of textbox and ddl
            $("#txtEditTeaName").css("border", "1px solid #ccc");
            $("#txtEditTeaEmail").css("border", "1px solid #ccc");
            $("#txtEditTeaUnid").css("border", "1px solid #ccc");
        }

        function updateTeaDetails(){    // it will update the user detail in popup
            var teaname = $("#txtEditTeaName").val();
            var teaemail = $("#txtEditTeaEmail").val();
            var teaunid = $("#txtEditTeaUnid").val();
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            var updateDate =  getdate();
            var c1=0,c2=0,c3=0,c4=0,c5=0,c6=0,c7=0,c8=0,c9=0,c10=0,c11=0,c12=0;

            // getting the classes for handle..
            var chk1 = document.getElementById("chkEditClass1");
            var chk2 = document.getElementById("chkEditClass2");
            var chk3 = document.getElementById("chkEditClass3");
            var chk4 = document.getElementById("chkEditClass4");
            var chk5 = document.getElementById("chkEditClass5");
            var chk6 = document.getElementById("chkEditClass6");
            var chk7 = document.getElementById("chkEditClass7");
            var chk8 = document.getElementById("chkEditClass8");
            var chk9 = document.getElementById("chkEditClass9");
            var chk10 = document.getElementById("chkEditClass10");
            var chk11 = document.getElementById("chkEditClass11");
            var chk12 = document.getElementById("chkEditClass12");

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

            // client side validation start
            if(teaname.trim()==""){
                allBoarderDefault();
                $("#txtEditTeaName").css("border", "1px solid red");
                $("#txtEditTeaName").attr("placeholder", "* Enter Teacher name");
                return false;
            }
            else if(teaemail.trim()==""){
                allBoarderDefault();

                $("#txtEditTeaEmail").css("border", "1px solid red");
                $("#txtEditTeaEmail").attr("placeholder", "* Enter Teacher Email");
                return false;
            }
            else if(!regex.test(teaemail)){
                allBoarderDefault();

                $("#txtEditTeaEmail").val("");
                $("#txtEditTeaEmail").css("border", "1px solid red");
                $("#txtEditTeaEmail").attr("placeholder", "* Enter Valid Email");
                return false;
            }
            else if(teaunid==""){
                allBoarderDefault();

                $("#txtEditTeaUnid").css("border", "1px solid red");
                $("#txtEditTeaUnid").attr("placeholder", "* Enter Teacher Email");
                return false;
            }
            else{   // client side validation clear..
                allBoarderDefault();
                var stuId = $("#txtHiddenUserId").val();       // getting id from hidden field
                var flag="updateTeacher";
                $.ajax({
                    url:"call_service.php",
                    method:"POST",
                    dataType:"json",
                    data:{action: flag,
                        tname: teaname,
                        temail: teaemail,
                        tunid: teaunid,
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
                        upDate: updateDate},
                    success:function(data){
                        if(data=="1"){
                            var hiddenQuery = $("#txtHiddenTeaQuery").val();  // getting the query to bind data again after update
                            duplBindDetails(hiddenQuery);      // this function will again bind the updated data
                            alert("Update Successfully");
                        }
                        else if(data=="11"){
                            alert("Server side validation failed");
                        }
                        else{
                            alert("Something went wrong, try again later");
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