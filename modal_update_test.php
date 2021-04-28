<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="assets/css/style-modal-update-test.css" />
    </head>
    <body>
        
        <section class="edit-test-details" >
            <!-- it contains modal for edit test details -->
            <div class="contains-modal">

                <!-- <button id="myBtn" onclick="openModal()">Open Modal</button> -->
            
                <!-- The Modal -->
                <div id="modalEditTestDetails" class="modal-edit-test-details">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Edit Details of Test ID: <label id="lblToShowTestId" ></label></h2>
                            <span class="close" onclick="closeModal()">&times;</span>
                            
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="txtEditTestTitle">Test Title:</label>
                                    <input type="text" class="form-control" id="txtEditTestTitle" placeholder="Test Title"/>
                                </div>
                                <div class="col-sm-6">
                                    <label for="txtEditTestDate">Test Date:</label>
                                    <input type="Date" class="form-control" id="txtEditTestDate" placeholder="Test Date"/>
                                </div>    
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label>For Class:</label>
                                    <select class="form-control" id="ddlEditTestClass" onchange="loadSubjectToEdit()" >
                                        <option value="0">Select Class</option>
                                    </select>
                                </div>    
                                <div class="col-sm-6">
                                    <label>For Section:</label>
                                    <select class="form-control" id="ddlEditTestSection">
                                        <option value="0">Select Section</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                  
                                <div class="col-sm-6">
                                    <label for="txtEditTestStartTime">Start Time:</label>
                                    <input type="time" class="form-control" id="txtEditTestStartTime" />
                                </div>

                                <div class="col-sm-6">
                                    <label for="txtEditTestEndTime">End Time:</label>
                                    <input type="time" class="form-control" id="txtEditTestEndTime" />
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label>For Stream:</label>
                                    <select class="form-control" id="ddlEditTestStream">
                                        <option value="0">Select Stream</option>
                                    </select>
                                </div> 
                                <div class="col-sm-6">
                                    <label>For Subject:</label>
                                    <select class="form-control" id="ddlEditTestSubject">
                                        <option value="0">Select Class First</option>
                                    </select>
                                </div> 
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input type="submit" id="btnEditTestDetails" onclick="updateTestDetails()" class="btn btn-sm btn-warning" value="Update"/>
                                    <input type="submit" id="" class="btn btn-sm btn-primary" value="Reset"/>
                                </div>
                            </div>

                        </div>
                        <!--<div class="modal-footer">
                            
                        </div>-->
                    </div>
                </div>
            </div>
            <!-- edit test details modal ends -->
        </section>

    </body>
    <script>
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modalEditTestDetails) {
                modalEditTestDetails.style.display = "none";
            }
        }

        // function to open modal
        function openModal(){
            var modal = document.getElementById("modalEditTestDetails");
            modal.style.display = "block";
        }

        // function to close modal
        function closeModal(){
            var modal = document.getElementById("modalEditTestDetails");
            modal.style.display = "none";
        }
    </script>
</html>