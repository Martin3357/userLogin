$('#user').addClass('active');
//funksioni i datarange picker
$(function () {

    $('input[name="daterange"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="daterange"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + '   /   ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('input[name="daterange"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

});


//datatable
function showUserTable(ids) {


    window.dt = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        searchDelay: 1250,

        serverMethod: 'POST',
        ajax: {
            url: "user-list/ajax.php",

            data: function (data) {
                let emails = $('#email_search').val();

                let emailsstr = "'" + emails.join("','") + "'";
                if (emailsstr != "''") {
                    data.email_search = emailsstr;
                }

                data.data_flt = $('#datafilter').val();

                data.phone_search = $('#nr_Tel').val();
                data.action = 'list';
                data.name_serch_product = ids;

// console.log(data.name_serch_product);
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
            {data: "action", orderable: false}


        ],
        "drawCallback": function (data) {
            window.tableData = data.json;
        },
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
        }
    });

}

//funksioni per te bere aoutocomplete modalin pasi shtypim edit
function allowShowUser(userID) {


    $.ajax({

        url: 'user-list/ajax.php',
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
                $('#image').attr('src', "http://localhost/Projekti/" + response.message['images']);
                $('#dw').attr('href', response.message['images']);
                $('#userid').val(userID);

                $('#userModal').modal('toggle');


            } else {
                toastr.error(response.message)
                toastr.options.preventDuplicates = true;
            }
        }
    });

}

//funksioni per shtimin e nje useri te ri
function addUser() {

    let first_name = $('#addfirst_name').val();
    let last_name = $('#addlast_name').val();
    let atesia = $('#addatesia').val();
    let roli = $('#addroli').val();
    let phone = $('#addphone').val();
    let birthday = $('#addbirthday').val();
    let email = $('#addemail').val();
    let password = $('#addpassword').val();
    let cpassword = $('#caddpassword').val();
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
        if (!name_regex.test(first_name.trim())) {
            $('#addfirst_name').addClass('red_border');
//toastr.error("Emri jo i sakte");
        } else {
            $('#addfirst_name').removeClass('red_border');
        }
        $('#addlast_name').addClass('red_border');
        if (!last_regex.test(last_name.trim())) {
            $('#addlast_name').addClass('red_border');
//toastr.error("Mbiemri jo i sakte");
        } else {
            $('#addlast_name').removeClass('red_border');
        }
        $('#atesia').addClass('red_border');
        if (!atesia_regex.test(atesia.trim())) {
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
        if (!phone_regex.test(phone.trim())) {
            $('#addphone').addClass('red_border');
//toastr.error("Vendosni numrin e telefonit");

        } else {
            $('#addphone').removeClass('red_border');

        }
        if (!birthday_regex.test(birthday.trim())) {
            $('#addbirthday').addClass('red_border');
        } else {
            $('#addbirthday').removeClass('red_border');
        }
        if (!email_regex.test(email.trim())) {
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
    let src = $('#foto').attr('src');
    var formdata = new FormData();
    let foto = $('#fotoo')[0].files[0];
    formdata.append('action', 'register');
    formdata.append('foto', foto);
    formdata.append('oldPath', src);
    formdata.append('addfirst_name', first_name);
    formdata.append('addlast_name', last_name);
    formdata.append('addatesia', atesia);
    formdata.append('addroli', roli);
    formdata.append('addphone', phone);
    formdata.append('addbirthday', birthday);
    formdata.append('addemail', email);
    formdata.append('addpassword', password);
    formdata.append('caddpassword', cpassword);
    $.ajax({

        url: 'user-list/ajax.php',
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
                    $('#editModal').modal('hide');
                    $('#datatable').DataTable().ajax.reload();
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

//plotesimi automatik i username-it
function addfillUsername() {
    let first_name = $('#addfirst_name').val();
    let last_name = $('#addlast_name').val();
    let name_regex = /^[A-Za-z\s]+$/;
    if (name_regex.test(first_name) && name_regex.test(last_name)) {
        $('#addusername').val((first_name[0] + last_name).toLowerCase());
    }
}

//funksioni i data range picker kur shtojme nje user te ri
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

//funksioni i data range picker kur editojme nje user
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

//plotesimi automatik i username-it ne momentin e editimit
function fillUsername() {
    let first_name = $('#first_name').val();
    let last_name = $('#last_name').val();
    let name_regex = /^[A-Za-z\s]+$/;
    if (name_regex.test(first_name) && name_regex.test(last_name)) {
        $('#username').val(first_name[0] + last_name);
    }
}

//funksioni per editimin e nje perdoruesi
function updateUser() {
    let first_name = $('#first_name').val();
    let last_name = $('#last_name').val();
    let phone = $('#phone').val();
    let birthday = $('#birthday').val();
    let email = $('#email').val();
    let userID = $('#userid').val();
    let src = $('#image').attr('src');
    var formdata = new FormData();
    let imgs = $('#imgs')[0].files[0];
    let name_regex = /^[A-Za-z\s]+$/;
    let last_regex = /^[A-Za-z\s]+$/;
    let phone_regex = /^[0-9]+$/;
    let email_regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    let birthday_regex = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
    formdata.append('action', 'update');
    formdata.append('foto', imgs);
    formdata.append('oldPath', src);
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

    formdata.append('userupdateid', userID);
    formdata.append('first_name', first_name);
    formdata.append('last_name', last_name);
    formdata.append('phone', phone);
    formdata.append('birthday', birthday);
    formdata.append('email', email);
    $.ajax({

        url: 'user-list/ajax.php',
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
//console.log("martin");
//alert(data);
//$('#userModal')[0].reset();
                    $('#userModal').modal('hide');
                    $('#datatable').DataTable().ajax.reload();
//location.reload();
// dt.draw();
// $('#userModal').modal('toggle');
                });


//window.location.href = 'shto.php'

            } else {
                toastr.error(response.message)
                toastr.options.preventDuplicates = true;
            }
        }
    });
}

//Funksioni per fshirjen e nje useri
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

                    url: 'user-list/ajax.php',
                    type: 'POST',
                    data: {
                        'action': 'delete',
                        'userdeleteid': userID
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 200) {
                            swal("U fshi me sukses!").then(function () {

                                $('#datatable').DataTable().ajax.reload();
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

//funksioni per te boshatisur modalin pasi shtojme nje user
$('#editModal').on('hidden.bs.modal', function (e) {
    $(this)
        .find("input,textarea,select")
        .val('')
        .end()
        .find("input[type=checkbox], input[type=radio]")
        .prop("checked", "")
        .end();
})
//selec2 per emailin
$(document).ready(function () {
    $('.email_select2').select2({
        placeholder: "Email...",
        width: '100%',
        language: {
            noResults: function () {
                return "no results found"
            },
        },
        ajax: {
            url: 'user-list/ajax.php?action=get_all_emails',
            type: 'get',
            delay: 1000,
            data: function (params) {

                return {
                    search: params.term,

                };
            },
            processResults: function (data) {
                return {
                    results: JSON.parse(data),
                }
            },
        }
    });

});
//selec2 per nr e telefonit
$(document).ready(function () {

    $("#nr_Tel").select2({
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        placeholder: "Nr Tel...",
        language: {
            noResults: function () {
                return "no results found"
            },
        },


        ajax: {
            url: 'user-list/ajax.php?action=get_all_numbers',
            type: 'get',
            delay: 1000,
            data: function (params) {

                return {
                    search: params.term

                };
            },
            processResults: function (data) {

                return {
                    results: JSON.parse(data),

                }

            },

        }
    });
});

//funksioni per reloadin e datatables pasi bejme filtrimin ne baze dates emailit apo nr telefonit
$('#filter_button').click(function () {

    $('#datatable').DataTable().ajax.reload();
// $("#nr_Tel").empty().trigger('change')
});

