//    Funksioni per shfaqjen e butonit plus i cili hap nen tabelen
$('#paga').addClass('active');

function toggleTable(id) {
    if ($('#tabela_' + id).hasClass('hide_row')) {
        $('#tabela_' + id).removeClass('hide_row');
        $('#plusi_' + id).html('<i class="fa fa-minus" aria-hidden="true"></i>');

    } else {
        $('#tabela_' + id).addClass('hide_row');
        $('#plusi_' + id).html('<i class="fa fa-plus" aria-hidden="true"></i>');
    }
}
