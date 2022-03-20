<script>

    $('#profili').addClass('active');
//Funksioni per mbushjen automatike e profilit
function userFill(userID) {
    $.ajax({

        url: 'profile/backend.php',
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
                $('#img').attr('src',"http://localhost/Projekti/"+ response.message['images']);
                // console.log(response.message['images']);
                //$('#image').val(response.message['images']);


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

    let src = $('#img').attr('src');
    //console.log(src);
    var formdata = new FormData();
    let imgs = $('#imgs')[0].files[0];
    formdata.append('action', 'updateprofile');
    formdata.append('foto', imgs);
    formdata.append('oldPath', src);
    //let old_foto = $('#img').attr('src');
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
    formdata.append('userupdateid', userID);
    formdata.append('first_name', first_name);
    formdata.append('last_name', last_name);
    formdata.append('phone', phone);
    formdata.append('email', email);
    $.ajax({

        url: 'profile/backend.php',
        type: 'POST',
        data: formdata,
        contentType: false,
        processData: false,
        cache: false,
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
//funksioni per ndryshimin e fjalkalimit
function updatepassword() {
    //let userID = $('#userid').val();
    let oldpass = $('#oldpass').val();
    let newpass = $('#newpass').val();
    let verifypass = $('#verifypass').val();
    let password_regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,1000}$/;

    let flag = false;

    if (!password_regex.test(newpass)) {
        $('#newpass').addClass('red_border');
        flag = true;
        //toastr.error("Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character");
    } else {
        $('#newpass').removeClass('red_border');
    }
    if (!password_regex.test(oldpass)) {
        $('#oldpass').addClass('red_border');
        flag = true;
        //toastr.error("Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character");
    } else {
        $('#oldpass').removeClass('red_border');
    }
    if (!password_regex.test(verifypass)) {
        $('#verifypass').addClass('red_border');
        flag = true;

        //toastr.error("Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character");
    } else {
        $('#verifypass').removeClass('red_border');
    }
    if (flag) {
        return;
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

                    url: 'profile/backend.php',
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