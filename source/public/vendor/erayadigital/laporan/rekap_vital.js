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
});
$("#btn_baca_data_rekap").on('click', function() {
    $("#datatables_vital_perusahaan").DataTable().ajax.reload();
});
function loadDataPasien() {
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_vital_perusahaan").DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            bFilter: false,
            bProcessing: true,
            serverSide: true,
            scrollX: $(window).width() < 768 ? true : false,
            pagingType: "full_numbers",
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            ajax: {
                "url": baseurlapi + '/laporan/rekap/vital',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_daftarpasien").val();
                    d.dari_perusahaan = $("#filter_perusahaan").val();
                    d.tanggal_awal = $("#tanggal_awal").val().split('-').reverse().join('-');
                    d.tanggal_akhir = $("#tanggal_akhir").val().split('-').reverse().join('-');
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
                    const infoString = 'Hal Ke: ' + currentPage + ' Ditampilkan: ' + 10 + ' Dari Total : ' + recordsFiltered + ' Data';
                    return infoString;
                }
            },
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            columns: [
                {
                    title: "No",
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: "Nama Perusahaan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.company_name}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Jumlah Peserta",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.total_peserta} Orang`;
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-between gap-2"><button onclick="filter_vital_per_perusahaan('${row.id_perusahaan}','${row.company_name}')" class="btn btn-success w-100"><i class="fa fa-search"></i> Lihat Peserta</button></div>`;
                        }       
                        return data;
                    }
                }
            ]
        });
    });
}
function filter_vital_per_perusahaan(id_perusahaan,nama_perusahaan){
    $.get('/generate-csrf-token', function(response) {
        if ($.fn.DataTable.isDataTable('#datatables_vital_perusahaan_detail')) {
            $("#datatables_vital_perusahaan_detail").DataTable().destroy();
            $("#datatables_vital_perusahaan_detail").empty();
        }
        $("#datatables_vital_perusahaan_detail").DataTable({
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
                "url": baseurlapi + '/laporan/rekap/vital_detail',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.id_perusahaan = id_perusahaan,
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
                    const infoString = 'Hal Ke: ' + currentPage + ' Ditampilkan: ' + 10 + ' Dari Total : ' + recordsFiltered + ' Data';
                    return infoString;
                }
            },
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            rowGroup: {
                dataSrc:function(row) {
                    return row.id;
                },
                startRender: function (rows, group) {
                    let rowJudul = $('<tr class="group-label">')
                        .append(`<td colspan="4" style="text-align:center;background-color: #f2f2f2; font-weight: bold;">${rows.data()[0].nama_peserta}</td>`);
                    let rowHeader = $('<tr class="group-header" style="background-color: #fafafa; font-weight: bold;">')
                        .append('<td style="width:5%">No</td>')
                        .append('<td style="width:45%">Tanda Vital</td>')
                        .append('<td style="width:25%">Nilai</td>')
                        .append('<td style="width:25%">Keterangan</td>');
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
                            return `${row.nama_atribut_saat_ini}`;
                        }
                        return data;
                    }
                },
                {
                    title: "",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.nilai_tanda_vital} ${row.satuan_tanda_vital}`;
                        }
                        return data;
                    }
                },
                {
                    title: "",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.keterangan_tanda_vital == null ? "" : row.keterangan_tanda_vital}`;
                        }
                        return data;
                    }
                }
            ]
        });
    });
    setTimeout(function() {
        $("#modal_vital_detail_text").html("Detail Peserta Tanda Vital " + nama_perusahaan);
        $("#modal_vital_detail").modal("show");
    }, 300);
}
