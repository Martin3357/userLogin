<?php
require_once "top_menu.php";
//require_once "ajax.php";
?>
<script>
    function register_user() {
        let first_name = $('#addfirst_name').val();
        let last_name = $('#addlast_name').val();
        let atesia = $('#addatesia').val();
        let phone = $('#addphone').val();
        let birthday = $('#addbirthday').val();
        let email = $('#addemail').val();
        let password = $('#addpassword').val();
        let name_regex = /^[A-Za-z\s]+$/;
        let last_regex = /^[A-Za-z\s]+$/;
        let atesia_regex = /^[A-Za-z\s]+$/;
        let phone_regex = /^[0-9]+$/;
        let email_regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        let password_regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,1000}$/;

        if (!name_regex.test(first_name) || !last_regex.test(last_name) || !atesia_regex.test(atesia) || !phone_regex.test(phone) || !email_regex.test(email)) {
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
            $('#addatesia').addClass('red_border');
            if (!atesia_regex.test(atesia)) {
                $('#addatesia').addClass('red_border');
                //toastr.error("Vendosni atesine");
            } else {
                $('#addatesia').removeClass('red_border');
            }
            if (!phone_regex.test(phone)) {
                $('#addphone').addClass('red_border');
                //toastr.error("Vendosni numrin e telefonit");

            } else {
                $('#addphone').removeClass('red_border');
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
                'addphone': phone,
                'addbirthday': birthday,
                'addemail': email,
                'addpassword': password,
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 200) {
                    swal('Success', response.message);
                    window.location.href = 'shto.php'
                } else {
                    toastr.error(response.message)
                    toastr.options.preventDuplicates = true;
                }
            }
        });
    }
    //Plotesimi automatik i username-it
    function fillUsername(){
        let first_name = $('#addfirst_name').val();
        let last_name = $('#addlast_name').val();
        let name_regex = /^[A-Za-z\s]+$/;
        if (name_regex.test(first_name)&&name_regex.test(last_name)){
            $('#addusername').val(first_name[0]+last_name);
        }
    }
    $(function() {


        $('input[name="birthday"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2000,
            maxYear: parseInt(moment().format('YYYY'),10)
        }, function(start, end, label) {
            var years = moment().diff(start, 'years');

        });
        $('#birthday').val('')
    });
</script>
