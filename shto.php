<?php

//if (!$_SESSION['Roli'] == 'admin') {
//header('Location: welcome.php');}
require_once "top_menu.php";
//require_once "functions.php";

?>

<style>
    .content {
        max-width: 800px;
        margin: auto;

    }

    h1 {
        text-align: center;
        padding-bottom: 60px;
    }
</style>
<br><br>
<div class="content">
    <button type="button" id="add_button" data-toggle="modal" data-target="#editModal" class="btn btn-success"
            style="background: #0faaf7">Add User
    </button>
    <br><br>
    <?php
    $query = "SELECT *FROM adm";
    $query_run = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($query_run);
    $id = $row['ID'];
    ?>
    <table class="table table-striped table-hover dataTables " id="datatable">
        <thead bgcolor="pink">
        <tr class="table-primary">
            <!--            <th scope="col">ID</th>-->
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
                        <div class="col"><input type="text" class="form-control" name="first_name" id="first_name"
                                                placeholder="First Name" required="required" onchange="fillUsername()">
                        </div>
                        <div class="col"><input type="text" class="form-control" name="last_name" id="last_name"
                                                placeholder="Last Name" required="required" onchange="fillUsername()">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="userid" id="userid">
                <!--                <div class="form-group">-->
                <!--                    <input type="text" class="form-control" name="atesia" id="atesia" placeholder="Atesia"-->
                <!--                           required="required">-->
                <!--                </div>-->
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
                <!--                <div class="form-group">-->
                <!--                    <input type="password" class="form-control" name="password" id="password" placeholder="Password"-->
                <!--                           required="required">-->
                <!--                </div>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id='userupdate' type="button" onclick="updateUser()" class='btn btn-success'
                        data-toggle='modal'>Update
                </button>
            </div>
        </div>
    </div>
</div>

<!--Shtimi i nje useri te ri  -->

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sign Up</h5>
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
                        <div class="col"><input type="text" class="form-control" name="first_name" id="addfirst_name"
                                                placeholder="First Name" required="required"
                                                onchange="addfillUsername()">
                        </div>
                        <div class="col"><input type="text" class="form-control" name="last_name" id="addlast_name"
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
                    <input type="text" id="addbirthday" name="addbirthday" placeholder="birthday" class="form-control"
                           required="required" autocomplete="off">
                    <span class="error" id="lblError"></span>
                    <!--                    <input type="button" id="btnValidate" value="Validate" onclick="return ValidateDOB()" />-->
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" id="addemail" placeholder="Email"
                           required="required">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="username" id="addusername" placeholder="Username"
                           disabled>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="addpassword" placeholder="Password"
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

<script>
    $(document).ready(function () {
        window.dt = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,

            serverMethod: 'POST',
            ajax: {
                url: "ajax.php",
                data: {
                    action: "list"
                }
            },
            columns: [
                {data: "id", name: "ID"},
                {data: "firstname", name: "first_name"},
                {data: "lastname", name: "last_name"},
                {data: "phone", name: "phone"},
                {data: "email", name: "email"},
                {data: "birthday", name: "dob"},
                {data: "post", name: "Roli"},
                {data: "images", name: "images"},
                {data: "action"}


            ],
            // columnDefs: [
            //     {
            //         orderable: false,
            //         searchable: false,
            //         targets: 0
            //
            //     },
            //     {
            //         class: "calss-name",
            //         targets: [3, 5]
            //     },
            //     {
            //         visible: false
            //     }
            // ],
            // aaSorting: [[5, 'asc']],
            // order: [[ 1, "desc" ]],

            // l - length changing input control
            // f - filtering input
            // t - The table!
            // i - Table information summary
            // p - pagination control
            // r - processing display element
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {

                    order: [],
                    paging: true,
                    searching: true,
                    extend: 'excel',
                    columnDefs: [
                        {
                            orderable: false,
                            target: [0, 5]
                        }],
                    exportOptions: {
                        columns: ':visible:not(:eq(0))',
                    },
                    title: 'Data',
                    text: 'Excel',
                }
            ],
            oLanguage: {
                buttons: {
                    copyTitle: 'Copia to appunti',
                    copySuccess: {
                        _: 'Copiato %d rige to appunti',
                        1: 'Copiato 1 riga'
                    }
                },
                sLengthMenu: 'Show <select>' +
                    '<option value="10">10</option>' +
                    '<option value="30">30</option>' +
                    '<option value="50">50</option>' +
                    '<option value="-1">All</option>' +
                    '</select> records',

                oPaginate: {
                    sFirst: "<?=  "first" ?>",
                    sNext: "<?=  "next" ?>",
                    sLast: "<?=  "last" ?>",
                    sPrevious: "<?=  "previous" ?>"
                }
            }
        });
    });


    function userEdit(userID) {


        $.ajax({

            url: 'ajax.php',
            type: 'POST',
            data: {
                'action': 'edit',
                'usereditid': userID
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 200) {
                    $('#first_name').val(response.message['first_name']);
                    $('#last_name').val(response.message['last_name']);
                    // $('#atesia').val(response.message['atesia']);
                    $('#phone').val(response.message['phone']);
                    $('#birthday').val(response.message['dob']);
                    $('#email').val(response.message['email']);
                    $('#username').val(response.message['username']);
                    $('#userid').val(userID);


                } else {
                    toastr.error(response.message)
                }
            }
        });

    }

    function addUser() {
        let first_name = $('#addfirst_name').val();
        let last_name = $('#addlast_name').val();
        let atesia = $('#addatesia').val();
        let roli = $('#addroli').val();
        let phone = $('#addphone').val();
        let birthday = $('#addbirthday').val();
        let email = $('#addemail').val();
        let password = $('#addpassword').val();
        //let username=$('#addusername').val();
        let name_regex = /^[A-Za-z\s]+$/;
        let last_regex = /^[A-Za-z\s]+$/;
        let atesia_regex = /^[A-Za-z\s]+$/;

        let phone_regex = /^[0-9]+$/;
        let email_regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        let birthday_regex = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
        let password_regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,1000}$/;

        if (!name_regex.test(first_name) || !last_regex.test(last_name) || !atesia_regex.test(atesia) || !roli || !phone_regex.test(phone) || !email_regex.test(email) || !birthday_regex.test(birthday)) {
            $('#addfirst_name').addClass('red_border');
            if (!name_regex.test(first_name)) {
                $('#addfirst_name').addClass('red_border');
                //toastr.error("Emri jo i sakte");
            } else {
                $('#addfirst_name').removeClass('red_border');
            }
            $('#addlast_name').addClass('red_border');
            if (!last_regex.test(last_name)) {
                $('#addlast_name').addClass('red_border');
                //toastr.error("Mbiemri jo i sakte");
            } else {
                $('#addlast_name').removeClass('red_border');
            }
            $('#atesia').addClass('red_border');
            if (!atesia_regex.test(atesia)) {
                $('#addatesia').addClass('red_border');
                //toastr.error("Vendosni atesine");
            } else {
                $('#addatesia').removeClass('red_border');
            }
            $('#addroli').addClass('red_border');
            if (!roli) {
                $('#addroli').addClass('red_border');
                //toastr.error("Vendosni atesine");
            } else {
                $('#addroli').removeClass('red_border');
            }
            if (!phone_regex.test(phone)) {
                $('#addphone').addClass('red_border');
                //toastr.error("Vendosni numrin e telefonit");

            } else {
                $('#addphone').removeClass('red_border');

            }
            if (!birthday_regex.test(birthday)) {
                $('#addbirthday').addClass('red_border');
            } else {
                $('#addbirthday').removeClass('red_border');
            }
            if (!email_regex.test(email)) {
                $('#addemail').addClass('red_border');
                //toastr.error("Email jo i sakte");

            } else {
                $('#addemail').removeClass('red_border');
            }
            if (!password_regex.test(password)) {
                $('#addpassword').addClass('red_border');
                //toastr.error("Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character");
            } else {
                $('#addpassword').removeClass('red_border');
            }
            toastr.error("Te gjitha fushat duhen plotesuar");
            toastr.options.preventDuplicates = true;
            return;
        }


        $.ajax({

            url: 'registeruserback.php',
            type: 'POST',
            data: {
                'action': 'register',
                'addfirst_name': first_name,
                'addlast_name': last_name,
                'addatesia': atesia,
                'addroli': roli,
                'addphone': phone,
                'addbirthday': birthday,
                'addemail': email,
                'addpassword': password,
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
                    });


                } else {
                    toastr.error(response.message)
                    toastr.options.preventDuplicates = true;
                }
            }
        });
    }

    function addfillUsername() {
        let first_name = $('#addfirst_name').val();
        let last_name = $('#addlast_name').val();
        let name_regex = /^[A-Za-z\s]+$/;
        if (name_regex.test(first_name) && name_regex.test(last_name)) {
            $('#addusername').val((first_name[0] + last_name).toLowerCase());
        }
    }

    $(function () {


        $('input[name="addbirthday"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2000,
            maxYear: parseInt(moment().format('YYYY'), 10),
            locale: {
                format: 'YYYY-MM-DD'
            }
        }, function (start, end, label) {
            var years = moment().diff(start, 'years');

        });
        $('#addbirthday').val('')
    });

    $(function () {


        $('input[name="birthday"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2000,
            maxYear: parseInt(moment().format('YYYY'), 10),
            locale: {
                format: 'YYYY-MM-DD'
            }

        }, function (start, end, label) {
            var years = moment().diff(start, 'years');

        });
        $('#birthday').val('')
    });

    function fillUsername() {
        let first_name = $('#first_name').val();
        let last_name = $('#last_name').val();
        let name_regex = /^[A-Za-z\s]+$/;
        if (name_regex.test(first_name) && name_regex.test(last_name)) {
            $('#username').val(first_name[0] + last_name);
        }
    }

    function updateUser() {
        let first_name = $('#first_name').val();
        let last_name = $('#last_name').val();
        let phone = $('#phone').val();
        let birthday = $('#birthday').val();
        let email = $('#email').val();
        let userID = $('#userid').val();

        let name_regex = /^[A-Za-z\s]+$/;
        let last_regex = /^[A-Za-z\s]+$/;
        let phone_regex = /^[0-9]+$/;
        let email_regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        let birthday_regex = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
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
        if (!birthday_regex.test(birthday)) {
            $('#birthday').addClass('red_border');
        } else {
            $('#birthday').removeClass('red_border');
        }


        $.ajax({

            url: 'update.php',
            type: 'POST',
            data: {
                'action': 'update',
                'userupdateid': userID,
                'first_name': first_name,
                'last_name': last_name,
                'phone': phone,
                'birthday': birthday,
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

    function deleteusermodal(userID) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this user!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({

                        url: 'delete.php',
                        type: 'POST',
                        data: {
                            'action': 'delete',
                            'userdeleteid': userID
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status == 200) {
                                swal("U fshi me sukses!").then(function () {
                                    location.reload()
                                });
                            } else {
                                toastr.error(response.message)
                            }
                        }
                    });
                } else {
                    swal("Your user  is safe!");
                }
            });
    }

    $('#editModal').on('hidden.bs.modal', function (e) {
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end();
    })
</script>

