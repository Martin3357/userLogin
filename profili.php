<?php

//if (!$_SESSION['Roli'] == 'admin') {
//header('Location: welcome.php');}
require_once "top_menu.php";
require_once "functions.php";
$query_profile = "SELECT * FROM adm WHERE ID=" . $_SESSION['id'] . "";
$user_result = mysqli_query($conn, $query_profile);
$row = mysqli_fetch_assoc($user_result);
?>
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">

                <img id="img"
                     src="<?= $row['images'] ?>" class="avatar img-circle img-thumbnail"
                     alt="avatar"><span class="font-weight-bold"></span><span
                        class="text-black-50"></span><span> </span></div>
            <!--            <input type="file" class="text-center center-block file-upload" id="img" value="">-->
            <input class="text-center center-block file-upload" name="photo" type="file" id="imgs" accept="image/*"
                   onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])">
            <div class="mt-5 text-center">
                <button class="btn btn-primary profile-button" onclick="updatePhoto(src)" type="button">Save Image
                </button>
            </div>
        </div>

        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">Name</label><input type="text" id="first_name"
                                                                                   name="first_name"
                                                                                   class="form-control"
                                                                                   placeholder="first name" value="">
                    </div>
                    <div class="col-md-6"><label class="labels">Surname</label><input type="text" id="last_name"
                                                                                      name="last_name"
                                                                                      class="form-control"
                                                                                      value="" placeholder="surname">
                    </div>
                </div>
                <input type="hidden" name="userid" id="userid" value="<?= $_SESSION['id'] ?>">
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text" id="phone"
                                                                                             name="phone"
                                                                                             class="form-control"
                                                                                             placeholder="enter phone number"
                                                                                             value=""></div>
                    <!--                    <div class="col-md-12"><label class="labels">Address Line 1</label><input type="text" class="form-control" placeholder="enter address line 1" value=""></div>-->
                    <!--                    <div class="col-md-12"><label class="labels">Address Line 2</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div>-->
                    <!--                    <div class="col-md-12"><label class="labels">Postcode</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div>-->
                    <!--                    <div class="col-md-12"><label class="labels">State</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div>-->
                    <!--                    <div class="col-md-12"><label class="labels">Area</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div>-->
                    <div class="col-md-12"><label class="labels">Email</label><input type="text" id="email" name="email"
                                                                                     class="form-control"
                                                                                     placeholder="enter email id"
                                                                                     value=""></div>
                    <!--                    <div class="col-md-12"><label class="labels">Education</label><input type="text" class="form-control" placeholder="education" value=""></div>-->
                </div>
                <div class="row mt-3">
                    <!--                    <div class="col-md-6"><label class="labels">Country</label><input type="text" class="form-control" placeholder="country" value=""></div>-->
                    <!--                    <div class="col-md-6"><label class="labels">State/Region</label><input type="text" class="form-control" value="" placeholder="state"></div>-->
                </div>
                <div class="mt-5 text-center">
                    <button class="btn btn-primary profile-button" onclick="updateProfile()" type="button">Save
                        Profile
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience"><span>Edit Password</span>
                </div>
                <br>
                <div class="col-md-12"><label class="labels">Old Password</label><input type="password"
                                                                                        class="form-control"
                                                                                        placeholder="old password"
                                                                                        id="oldpass" name="oldpass"
                                                                                        value="">
                </div>
                <br>
                <div class="col-md-12"><label class="labels">New Password</label><input type="password" id="newpass"
                                                                                        name="newpass"
                                                                                        class="form-control"
                                                                                        placeholder="password" value="">
                </div>
                <br>
                <div class="col-md-12"><label class="labels">Verify</label><input type="password" id="verifypass"
                                                                                  name="verifypass" class="form-control"
                                                                                  placeholder="Verify Password"
                                                                                  value=""></div>
                <br>
                <button class="btn btn-primary profile-button" onclick="updatepassword()" type="button">Update
                    Password
                </button>
            </div>
        </div>
    </div>
</div>

<script>

    //mbushja automatike e profilit
    function userFill(userID) {


        $.ajax({

            url: 'ajax.php',
            type: 'POST',
            data: {
                'action': 'profile',
                'usereditid': userID
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 200) {
                    $('#first_name').val(response.message['first_name']);
                    $('#last_name').val(response.message['last_name']);
                    $('#phone').val(response.message['phone']);
                    $('#email').val(response.message['email']);
                    $('#userid').val(userID);
                    $('#image').val(response.message['images']);


                } else {
                    toastr.error(response.message)
                }
            }
        });

    }

    // A $( document ).ready() block.
    $(document).ready(userFill(<?=$_SESSION['id']?>))

    //Modifikimi i te dhenave te profilit
    function updateProfile() {
        let first_name = $('#first_name').val();
        let last_name = $('#last_name').val();
        let phone = $('#phone').val();
        let email = $('#email').val();
        let userID = $('#userid').val();

        let name_regex = /^[A-Za-z\s]+$/;
        let last_regex = /^[A-Za-z\s]+$/;
        let phone_regex = /^[0-9]+$/;
        let email_regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        if (!name_regex.test(first_name)) {
            $('#first_name').addClass('red_border');
        } else {
            $('#first_name').removeClass('red_border');
        }
        if (!last_regex.test(last_name)) {
            $('#last_name').addClass('red_border');
        } else {
            $('#last_name').removeClass('red_border');
        }
        if (!phone_regex.test(phone)) {
            $('#phone').addClass('red_border');
        } else {
            $('#phone').removeClass('red_border');
        }
        if (!email_regex.test(email)) {
            $('#email').addClass('red_border');
        } else {
            $('#email').removeClass('red_border');
        }
        $.ajax({

            url: 'update.php',
            type: 'POST',
            data: {
                'action': 'updateprofile',
                'userupdateid': userID,
                'first_name': first_name,
                'last_name': last_name,
                'phone': phone,
                'email': email
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 200) {
                    swal({
                        title: "Good job!",
                        text: response.message,
                        icon: "success",
                        button: "Okay",
                    }).then(function () {
                        location.reload()
                        // dt.draw();
                        // $('#editModal').modal('hide');
                    });


                    //window.location.href = 'shto.php'

                } else {
                    toastr.error(response.message)
                    toastr.options.preventDuplicates = true;
                }
            }
        });
    }

    src = $('#img').attr('src');

    function updatePhoto(src) {

        var formdata = new FormData();
        let imgs = $('#imgs')[0].files[0];

        // let splits = imgs.split('fakepath\\')
        // let spl=splits[1];
        // let userID = $('#userid').val();
        formdata.append('action', 'updateprofilephoto');
        formdata.append('foto', imgs);
        formdata.append('oldPath', src);
        $.ajax({
            url: 'update.php',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            cache: false,
            success: function (response) {


                response = JSON.parse(response)


                if (response.status == 200) {

                    swal({
                        title: "Good job!",
                        text: response.message,
                        icon: "success",
                        button: "Okay",
                    }).then(function () {
                        location.reload()
                        // dt.draw();
                        // $('#editModal').modal('hide');
                    });


                    //window.location.href = 'shto.php'

                } else {
                    // toastr.error(response.message)
                    // toastr.options.preventDuplicates = true;
                }
            },
            dataType: 'text'
        });
    }

    function updatepassword() {
        //let userID = $('#userid').val();
        let oldpass = $('#oldpass').val();
        let newpass = $('#newpass').val();
        let verifypass = $('#verifypass').val();
        let password_regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,1000}$/;

        if (!password_regex.test(newpass)) {
            $('#newpass').addClass('red_border');
            //toastr.error("Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character");
        } else {
            $('#newpass').removeClass('red_border');
        }
        if (!password_regex.test(oldpass)) {
            $('#oldpass').addClass('red_border');
            //toastr.error("Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character");
        } else {
            $('#oldpass').removeClass('red_border');
        }
        if (!password_regex.test(verifypass)) {
            $('#verifypass').addClass('red_border');
            //toastr.error("Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character");
        } else {
            $('#verifypass').removeClass('red_border');
        }

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this password!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({

                        url: 'update.php',
                        type: 'POST',
                        data: {
                            'action': 'updatepassword',
                            'oldpass': oldpass,
                            'newpass': newpass,
                            'verifypass': verifypass
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status == 200) {
                                swal("Passwordi u ndryshua me sukses!").then(function () {
                                    location.reload()
                                });

                            } else {
                                toastr.error(response.message)
                                toastr.options.preventDuplicates = true;
                            }
                        }
                    });
                } else {
                    swal("Mbeti passwordi i vjeter!").then(function () {
                        location.reload()
                    });
                }
            });
    }
</script>
