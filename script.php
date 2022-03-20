
<script>
    $('#enter').keypress(function (e) {

        if(e.keyCode=='13') //Keycode for "Return"

            $('#Sign_in').click();
    });
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
                    window.location.href = 'index2.php'
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