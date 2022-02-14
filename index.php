<?php require_once 'header.php ' ?>
<style>
    body, html {
        height: 100%;
        background-repeat: no-repeat;
        background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));
    }
</style>
<header>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
</header>
<body>
<!-- Login page  -->
<div class="container">
    <div class="card card-container">
        <span id="error"></span>
        <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
        <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png"/>
        <p id="profile-name" class="profile-name-card"></p>
        <span id="reauth-email" class="reauth-email"></span>
        <input type="email" name="uname" id="uname" class="form-control" placeholder="Email address">
        <input type="password" name="pass" id="pass" class="form-control" placeholder="Password">
        <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" onclick="login();" id="Sign_in">Sign
            in
        </button>
        <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" data-toggle="modal"
                data-target="#exampleModal" id="Sign_in">Sign up
        </button>
    </div>
    <!-- /card-container -->
</div><!-- /container -->


<!-- Register page  -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                <h2>Register</h2>
                <span id="error"></span>
                <p class="hint-text">Create your account. It's free and only takes a minute.</p>
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
                <div class="form-group">
                    <input type="text" class="form-control" name="atesia" id="atesia" placeholder="Atesia"
                           required="required">
                </div>
                <div class="form-group">
                    <input type="tel" id="phone" name="phone" placeholder="06..." pattern="[0-9]{10}"
                           required="required">
                </div>
                <div class="form-group" id="demo">
                    <input type="text" id="birthday" name="birthday" class="form-control" placeholder="ditelindja"
                           autocomplete="off" required="required">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                           required="required">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                           disabled>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                           required="required">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Verify"
                           required="required">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick="signup()" class="btn btn-primary" id="signup">Register</button>
            </div>
        </div>
    </div>
</div>
</body>

<script>
    function login() {
        let uname = $('#uname').val();
        let pass = $('#pass').val();

        $('#error').val('')

        $.ajax({

            url: 'ajax.php',
            type: 'POST',
            data: {
                'action': 'login',
                'uname': uname,
                'pass': pass,
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 200) {
                    window.location.href = 'welcome.php'
                } else {
                    toastr.error(response.message)
                    toastr.options.preventDuplicates = true;

                }
            }
        });
    }

    function signup() {
        let first_name = $('#first_name').val();
        let last_name = $('#last_name').val();
        let atesia = $('#atesia').val();
        let phone = $('#phone').val();
        let birthday = $('#birthday').val();
        let email = $('#email').val();
        let password = $('#password').val();
        let cpassword = $('#cpassword').val();
        let name_regex = /^[A-Za-z\s]+$/;
        let last_regex = /^[A-Za-z\s]+$/;
        let atesia_regex = /^[A-Za-z\s]+$/;
        let phone_regex = /^[0-9]+$/;
        let email_regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        let birthday_regex = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
        let password_regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,1000}$/;

        if (!name_regex.test(first_name) || !last_regex.test(last_name) || !atesia_regex.test(atesia) || !phone_regex.test(phone) || !email_regex.test(email) || !birthday_regex.test(birthday)) {
            $('#first_name').addClass('red_border');
            if (!name_regex.test(first_name)) {
                $('#first_name').addClass('red_border');
                //toastr.error("Emri jo i sakte");
            } else {
                $('#first_name').removeClass('red_border');
            }
            $('#last_name').addClass('red_border');
            if (!last_regex.test(last_name)) {
                $('#last_name').addClass('red_border');
                //toastr.error("Mbiemri jo i sakte");
            } else {
                $('#last_name').removeClass('red_border');
            }
            $('#atesia').addClass('red_border');
            if (!atesia_regex.test(atesia)) {
                $('#atesia').addClass('red_border');
                //toastr.error("Vendosni atesine");
            } else {
                $('#atesia').removeClass('red_border');
            }
            if (!phone_regex.test(phone)) {
                $('#phone').addClass('red_border');
                //toastr.error("Vendosni numrin e telefonit");

            } else {
                $('#phone').removeClass('red_border');

            }
            if (!birthday_regex.test(birthday)) {
                $('#birthday').addClass('red_border');
            } else {
                $('#birthday').removeClass('red_border');
            }
            if (!email_regex.test(email)) {
                $('#email').addClass('red_border');
                //toastr.error("Email jo i sakte");

            } else {
                $('#email').removeClass('red_border');
            }
            if (!password_regex.test(password)) {
                $('#password').addClass('red_border');
                //toastr.error("Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character");
            } else {
                $('#password').removeClass('red_border');
            }
            toastr.error("Te gjitha fushat duhen plotesuar");
            toastr.options.preventDuplicates = true;
            return;
        }


        $.ajax({

            url: 'ajax.php',
            type: 'POST',
            data: {
                'action': 'signup',
                'first_name': first_name,
                'last_name': last_name,
                'atesia': atesia,
                'phone': phone,
                'birthday': birthday,
                'email': email,
                'password': password,
                'cpassword':cpassword,
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 200) {
                    swal('Success', response.message);
                    window.location.href = 'index.php'
                } else {
                    toastr.error(response.message)
                }
            }
        });
    }

    //Plotesimi automatik i username-it
    function fillUsername() {
        let first_name = $('#first_name').val();
        let last_name = $('#last_name').val();
        let name_regex = /^[A-Za-z\s]+$/;
        if (name_regex.test(first_name) && name_regex.test(last_name)) {
            $('#username').val((first_name[0] + last_name).toLowerCase());
        }
    }
//datarange picker
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
</script>
</html>