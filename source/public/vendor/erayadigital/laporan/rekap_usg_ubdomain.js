$(document).ready(function() {
    const tanggalAwal = flatpickr("#tanggal_awal", {
        dateFormat: "d-m-Y",
        maxDate: 'today',
        onChange: function(selectedDates, dateStr) {
            tanggalAkhir.set('minDate', dateStr);
        }
    });
    $("#tanggal_awal").val(moment().startOf('month').format('DD-MM-YYYY'));
    const tanggalAkhir = flatpickr("#tanggal_akhir", {
        dateFormat: "d-m-Y",
        maxDate: 'today',
        minDate: $("#tanggal_awal").val()
    });
    $("#tanggal_akhir").val(moment().format('DD-MM-YYYY'));
    loadDataPasien();
    callselect2mcu();
});
function callselect2mcu(){
    $.get('/generate-csrf-token', function(response) {
        $('#select2_perusahaan').select2({ 
            placeholder: 'Pilih Perusahaan',
            ajax: {
                url: baseurlapi + '/masterdata/daftarperusahaan',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                method: 'GET',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        _token : response.csrf_token,
                        parameter_pencarian : (typeof params.term === "undefined" ? "" : params.term),
                        start : 0,
                        length : 1000,
                    }
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: `[${item.company_code}] - ${item.company_name}`,
                                id: item.id,
                            }
                        })
                    }
                    
                },
                error: function(xhr, status, error) {
                    return createToast('Kesalahan Penggunaan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                }
            },
        }); 
    });
}
$("#btn_baca_data_rekap").on('click', function() {
    $("#datatables_usg_ubdomain_perusahaan").DataTable().ajax.reload();
});
function loadDataPasien() {
    $.get('/generate-csrf-token', function(response) {
        if ($.fn.DataTable.isDataTable('#datatables_usg_ubdomain_perusahaan')) {
            $("#datatables_usg_ubdomain_perusahaan").DataTable().destroy();
            $("#datatables_usg_ubdomain_perusahaan").empty();
        }
        $("#datatables_usg_ubdomain_perusahaan").DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            bFilter: false,
            bProcessing: true,
            serverSide: true,
            scrollX: $(window).width() < 768 ? true : false,
            pagingType: "full_numbers",
            pageLength: 1000,
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            ajax: {
                "url": baseurlapi + '/laporan/rekap/usg_ubdomain',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.id_perusahaan = $('#select2_perusahaan').val();
                    d.tanggal_awal = $("#tanggal_awal").val().split('-').reverse().join('-'),
                    d.tanggal_akhir = $("#tanggal_akhir").val().split('-').reverse().join('-')
                },
                "dataSrc": function(json) {
                    let detailData = json.data;
                    let mergedData = detailData.map(item => {
                        return {
                            ...item,
                            recordsFiltered: json.recordsFiltered,
                        };
                    });
                    return mergedData;
                },
            },
            infoCallback: function(settings) {
                if (typeof settings.json !== "undefined") {
                    const currentPage = Math.floor(settings._iDisplayStart / settings._iDisplayLength) + 1;
                    const recordsFiltered = settings.json.recordsFiltered;
                    const infoString = 'Hal Ke: ' + currentPage + ' Ditampilkan: ' + 1000 + ' Dari Total : ' + recordsFiltered + ' Data';
                    return infoString;
                }
            },
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            rowGroup: {
                dataSrc:function(row) {
                    return row.company_name;
                },
                startRender: function (rows, group) {
                    let rowJudul = $('<tr class="group-label">')
                        .append(`<td colspan="3" style="text-align:center;background-color: #f2f2f2; font-weight: bold;">${rows.data()[0].company_name}</td>`);
                    let rowHeader = $('<tr class="group-header" style="background-color: #fafafa; font-weight: bold;">')
                        .append('<td style="width:5%">No</td>')
                        .append('<td style="width:40%">Kesimpulan</td>')
                        .append('<td style="width:10%">Jumlah</td>')
                    return rowJudul.add(rowHeader);
                }
            },
            columns: [
                {
                    title: "",
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: "",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.kesimpulan}`;
                        }
                        return data;
                    }
                },
                {
                    title: "",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.total_kesimpulan}`;
                        }
                        return data;
                    }
                }
            ]
        });
    });
}