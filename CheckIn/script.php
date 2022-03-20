<script>
    $('#check_in').addClass('active');
//datatable
    $(document).ready(function () {
        window.dt = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 1000,
            paging: true,

            serverMethod: 'POST',

            ajax: {
                url: "CheckIn/checkinback.php",
                data: function (data) {

                    data.data_flt = $('#datafilter').val();
                    data.name_search = $('#emri').val();
                    data.action = 'check';
                }
            },
            columns: [
                {
                    "class": "details-control",
                    orderable: false,
                    "data": null,
                    "name": "first_name",
                    "defaultContent": ""
                },
                // {data: "action"},
                {data: "firstName", name: "first_name"},
                {data: "lastName", name: "last_name"},
                {data: "hoursin", name: "hoursin", orderable: false},
                {data: "hoursout", name: "hoursout", orderable: false},
                {data: "date", name: "check_in_date"}
                // {data: "checkouth", name: "check_out_hour"}
            ],
            "drawCallback": function (settings) {
                window.tableData = settings.json;
            },
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                'copy', 'pdf',
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
                    copyTitle: 'Copy to clipboard',
                    copySuccess: {
                        _: 'Copied %d rows to clipboard',
                        1: 'Copied 1 row'
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
        var detailRows = [];

// Manipulimi i te dhenave ne front end te cilat na vine nga backendi

        $('#datatable tbody').on('click', '.details-control', function () {
            var tr = $(this).parents('tr');

            var row = dt.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                tr.removeClass('details bg-light');
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                tr.addClass('details bg-light');
                row.child(render_row_details(row.data().details)).show();

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }

        });
        // On each draw, loop over the `detailRows` array and show any child rows
        window.dt.on('draw', function () {
            $.each(detailRows, function (i, id) {
                $('#' + id + ' td.details-control').trigger('click');
            });
        });
    });
//funksioni i nentabeles qe na shfaqet pasi shtypim plusin

    function render_row_details(row_details) {
        var table = "<table class='table table-hover' style='text-align: center'>" +
            "<thead>" +
            "<tr style='background-color: #033b9c;  color: white;'>" +
            "<th><center>Emri</center> </th>" +
            "<th><center> Check In hour</center></th>" +
            "<th><center> Check Out Hour</center></th>" +
            "</tr>" +
            "</thead>" +
            "<tbody>";
        $.each(row_details, function (index, row_data) {
            table +=
                "<tr style='background-color: #eb8334'>" +
                "<td class='innertable-border-right '><center>" + row_data.first_name + ' ' + row_data.last_name + "</center> </td>" +
                "<td class='innertable-border-right '><center>" + row_data.check_in_hour + "</center></td>" +
                "<td class='borderedright'><center>" + row_data.check_out_hour + "</center></td>" +
                "</tr>";

        });

        table += "</tbody></table>";
        return table;
    }

    //funksioni i datarangepickerit
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
//select2 per emrin
    $(document).ready(function () {
        $("#emri").select2({
            width: '100%',
            minimumInputLength: 3,
            allowClear: true,
            placeholder: "Emri...",
            language: {
                noResults: function () {
                    return "no results found"
                },
            },

            ajax: {
                url: 'CheckIn/checkinback.php?action=get_all_names',
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
                    //dt.clear().draw();   // You can remove this draw() as it is a wasted call since you use it in the next line


                },
            }
        });
    });
//reloadi i tabeles ne menyre dinamike
    $('#filter_button').click(function () {

        $('#datatable').DataTable().ajax.reload();
    });
</script>