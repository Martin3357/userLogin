<?php

if ($_SESSION['Roli'] != 'admin') {
    header('Location:' . 'http://localhost/Projekti/' . 'index.php');
}
include "user-list/header.php";
?>
    <div class="container">
        <br><br>
        <div class="content">
            <button type="button" id="add_button" data-toggle="modal" data-target="#editModal" class="btn btn-success">
                Add
                User
            </button>
            <br><br>
            <div class="row">
                <div class="col-md-4">
                    <label><b>Date</b></label>
                    <input type="text" name="daterange" id="datafilter" autocomplete="off"
                           class="form-control" placeholder="From to..."/>
                </div>
                <div class="col-md-4">
                    <label><b>Email</b></label><br>
                    <select class="email_select2" multiple id="email_search"></select>
                </div>

                <div class="col-md-4">
                    <label><b>Nr Tel</b></label><br>
                    <select class="form-control" name="nr_Tel" id="nr_Tel"></select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3">
                    <button type="button" id="filter_button" class="btn btn-info"><i class="fa fa-search"
                                                                                     aria-hidden="true"></i> Search
                    </button>
                </div>
            </div>
            <br><br>
            <table class="table table-striped table-bordered dt-responsive table-hover" id="datatable">
                <thead bgcolor="pink">
                <tr class="table-primary">
                    <th scope="col">ID</th>
                    <th scope="col">Emer</th>
                    <th scope="col">Mbiemer</th>
                    <th scope="col">Nr_Tel</th>
                    <th scope="col">Email</th>
                    <th scope="col">Datelindja</th>
                    <th scope="col">Roli</th>
                    <th scope="col">Images</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h2>Edit User</h2>
                        <span id="error"></span>
                        <p class="hint-text"></p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col"><input type="text" class="form-control" name="first_name"
                                                        id="first_name"
                                                        placeholder="First Name" required="required"
                                                        onchange="fillUsername()">
                                </div>
                                <div class="col"><input type="text" class="form-control" name="last_name" id="last_name"
                                                        placeholder="Last Name" required="required"
                                                        onchange="fillUsername()">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="userid" id="userid">

                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" placeholder="06..." pattern="[0-9]{10}"
                                   required="required">
                        </div>
                        <div class="form-group">
                            <input type="text" id="birthday" name="birthday" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                   required="required">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                                   disabled>
                        </div>
                        <div class="d-flex flex-column align-items-center text-center p-3 py-5">

                            <img id="image" width="300px" height="300px"

                                 class="avatar img-circle img-thumbnail"
                                 alt="avatar"><span class="font-weight-bold"></span><span
                                    class="text-black-50"></span><span> </span></div>
                        <input class="text-center center-block file-upload" name="photo" type="file" id="imgs"
                               accept="image/*"
                               onchange="document.getElementById('image').src = window.URL.createObjectURL(this.files[0])">
                        <a class="btn btn-outline-info ml-3" id="dw" href="" download="image.png">Download
                            image</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Close
                        </button>
                        <button id='userupdate' type="button" onclick="updateUser()" class='btn btn-success'
                                data-toggle='modal'>Update
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!--Modali per shtimin e nje useri te ri  -->

        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabe  l">Sign Up</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h2>Add User</h2>
                        <span id="error"></span>
                        <p class="hint-text">Shto nje user te ri</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col"><input type="text" class="form-control" name="first_name"
                                                        id="addfirst_name"
                                                        placeholder="First Name" required="required"
                                                        onchange="addfillUsername()">
                                </div>
                                <div class="col"><input type="text" class="form-control" name="last_name"
                                                        id="addlast_name"
                                                        placeholder="Last Name" required="required"
                                                        onchange="addfillUsername()">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="atesia" id="addatesia" placeholder="Atesia"
                                   required="required">
                        </div>
                        <div class="form-group">
                            <label for="cars">Zgjidh rolin:</label>
                            <select name="Roli" id="addroli" required>
                                <option value=""></option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="tel" id="addphone" name="phone" placeholder="06..." pattern="[0-9]{10}"
                                   required="required">
                        </div>
                        <div class="form-group">
                            <input type="text" id="addbirthday" name="addbirthday" placeholder="birthday"
                                   class="form-control"
                                   required="required" autocomplete="off">
                            <span class="error" id="lblError"></span>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" id="addemail" placeholder="Email"
                                   required="required">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" id="addusername"
                                   placeholder="Username"
                                   disabled>
                        </div>
                        <div class="form-group">
                            <img id="foto" src="http://localhost/Projekti/images/default.jpg" width="200px"
                                 height="200px"
                                 class="avatar img-circle img-thumbnail" alt="avatar"><span
                                    class="font-weight-bold"></span><span class="text-black-50"></span><span> </span>
                            <input class="text-center center-block file-upload" name="photo" type="file" id="fotoo"
                                   accept="image/*"
                                   onchange="document.getElementById('foto').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" id="addpassword"
                                   placeholder="Password"
                                   required="required">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" name="cpassword" id="caddpassword"
                                   placeholder="Verify Password"
                                   required="required">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="addUser()" class="btn btn-primary" id="addUser">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include_once "user-list/footer.php";
?>

<script>
    $(document).ready(function (){
        let ids = '<?= $_GET['ids'] ?>';
        showUserTable(ids);
    });
</script>

