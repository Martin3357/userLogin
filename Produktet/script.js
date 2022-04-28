$('#produktet').addClass('active');

//funksionet per shfaqjen e nentabelave

function toggleTable(id) {

    if ($('#tabela_' + id).hasClass('hide_row')) {
        $('#tabela_' + id).removeClass('hide_row');
        $('#plusi_' + id).html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');

    } else {
        $('#tabela_' + id).addClass('hide_row');
        $('#plusi_' + id).html('<i class="fa fa-plus-circle" aria-hidden="true"></i>');
    }
}


function toggleTable_i(id) {
    if ($('#tabela_i' + id).hasClass('hide_row_i')) {
        $('#tabela_i' + id).removeClass('hide_row_i');
        $('#plusi_i' + id).html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');

    } else {
        $('#tabela_i' + id).addClass('hide_row_i');
        $('#plusi_i' + id).html('<i class="fa fa-plus-circle" aria-hidden="true"></i>');
    }
}

function toggleTable3(id) {
    if ($('#tabela_w' + id).hasClass('hide_row2')) {
        $('#tabela_w' + id).removeClass('hide_row2');
        $('#plusi_w' + id).html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');

    } else {
        $('#tabela_w' + id).addClass('hide_row2');
        $('#plusi_w' + id).html('<i class="fa fa-plus-circle" aria-hidden="true"></i>');
    }
}

function toggleTable4(id) {
    if ($('#tabela_a' + id).hasClass('hide_row3')) {
        $('#tabela_a' + id).removeClass('hide_row3');
        $('#plusi_a' + id).html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');

    } else {
        $('#tabela_a' + id).addClass('hide_row3');
        $('#plusi_a' + id).html('<i class="fa fa-plus-circle" aria-hidden="true"></i>');
    }
}

function toggleTable5(id) {
    if ($('#tabela_b' + id).hasClass('hide_row5')) {
        $('#tabela_b' + id).removeClass('hide_row5');
        $('#plusi_c' + id).html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');

    } else {
        $('#tabela_b' + id).addClass('hide_row5');
        $('#plusi_c' + id).html('<i class="fa fa-plus-circle" aria-hidden="true"></i>');

    }
}
