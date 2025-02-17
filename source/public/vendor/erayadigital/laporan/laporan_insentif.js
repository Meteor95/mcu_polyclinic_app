let let_jenis_laporan, let_id_tombol, datatables_laporan;
$(document).ready(function(){
    const tanggalAwal = flatpickr("#tanggal_awal", {
        dateFormat: "d-m-Y",
        maxDate: 'today',
        onChange: function(selectedDates, dateStr) {
            tanggalAkhir.set('minDate', dateStr);
        }
    });
    $("#tanggal_awal").val(moment().format('DD-MM-YYYY'));
    const tanggalAkhir = flatpickr("#tanggal_akhir", {
        dateFormat: "d-m-Y",
        maxDate: 'today',
        minDate: $("#tanggal_awal").val()
    });
    $("#tanggal_akhir").val(moment().format('DD-MM-YYYY'));
});
function getRowGroupConfig(jenis_laporan) {
    let rowGroupConfig = {};
    if (jenis_laporan == "insentif_tindakan") {
        rowGroupConfig = false;
    } else {
        rowGroupConfig = false;
    }
    return rowGroupConfig;
}
function report_show_modal(jenis_laporan,id_tombol) {
    let dataParameter = {};
    $("#modal_proses_data_transaksi_tindakan").prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Proses Kalkulasi');
    let ditampilkan = $("#select_page_length_transaksi_tindakan").val();
    let pencarian = $("#searchInput_transaksi_tindakan").val();
    let status_pembayaran = $("#status_pembayaran").val();
    let jenis_layanan = $("#jenis_layanan").val();
    let jenis_transaksi = $("#jenis_transaksi").val();
    let tanggal_awal_nilai = $("#tanggal_awal").val();
    let tanggal_akhir_nilai = $("#tanggal_akhir").val();
    let_jenis_laporan = jenis_laporan;
    let_id_tombol = id_tombol;
    let jenis_transaksi_ket = "";
    switch (jenis_laporan) {
        case 'insentif_tindakan':
            $("#modal_title").html("Laporan Data Insentif Pegawai (IDR) Periode : "+tanggal_awal_nilai+" s.d "+tanggal_akhir_nilai);
            break;
        default:
            $("#modal_title").html("Laporan Penjualan");
            break;
    }
    let columnsConfig = [];
    $("#total_all").html("0".toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
    if (jenis_laporan === 'insentif_tindakan') {
        columnsConfig = [
            { title: "No", className: "text-center align-middle", render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { title: "Kode Petugas", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.id_petugas}`;
                }
                return data;
            }},
            { title: "Nama Petugas", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.nama_petugas}`;
                }
                return data;
            }},
            { title: "Besaran Insentif", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.total_insentif.toLocaleString('id-ID')}`;
                }
                return data;
            }}
        ];
    } else {
        columnsConfig = [
            { title: "No", className: "text-center align-middle", render: function(data, type, row, meta) {
                return meta.row + 1;
            }},
            { title: "Data Tidak Ditemukan", className: "align-middle" },
        ];
    }    
    const handleAjaxRequest = (dataParameter) => {
        if ($.fn.DataTable.isDataTable('#datatables_laporan_insentif')) {
            $("#datatables_laporan_insentif").DataTable().destroy();
            $("#datatables_laporan_insentif").empty();
        }
        $("#datatables_laporan_insentif").DataTable({
            destroy: true,
            searching: false,
            lengthChange: false,
            ordering: false,
            scrollCollapse: true,
            scrollX: true,
            bFilter: false,
            bProcessing: true,
            serverSide: true,
            pageLength: ditampilkan == 0 ? 0 : ditampilkan,
            pagingType: "full_numbers",
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            ajax: {
                "url": baseurlapi + '/laporan/insentif/' + jenis_laporan,
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function (d) {
                    d._token = dataParameter._token;
                    d.parameter_pencarian = pencarian;
                    d.status_pembayaran = status_pembayaran;
                    d.jenis_layanan = jenis_layanan;
                    d.jenis_transaksi = jenis_transaksi;
                    d.tanggal_awal = tanggal_awal_nilai;
                    d.tanggal_akhir = tanggal_akhir_nilai;
                },
                "dataSrc": function(json) {
                    return json.data;
                }
            },
            columns: columnsConfig,
            rowGroup: getRowGroupConfig(jenis_laporan),
            infoCallback: function(settings) {
                if (typeof settings.json !== "undefined") {
                    const currentPage = Math.floor(settings._iDisplayStart / settings._iDisplayLength) + 1;
                    const recordsFiltered = settings.json.recordsFiltered;
                    const infoString = 'Hal ke: ' + currentPage + ' Ditampilkan: ' + (ditampilkan == 0 ? 'Semua' : $("#select_page_length_transaksi_tindakan").val()) + ' Baris dari Total : ' + recordsFiltered + ' Data';
                    return infoString;
                }
            },
            drawCallback: function(settings) {
                $("#modal_proses_data_transaksi_tindakan").prop('disabled', false).html('<i class="fas fa-search"></i> Proses Saring');
                let dataTotal = settings.json.data_total;
                let totalAll = 0;
                if (jenis_laporan === 'insentif_tindakan') {
                    dataTotal.forEach(function (item) {
                        totalAll += totalAll + item.total_insentif;
                    });   
                }
                $("#total_all").html(totalAll.toLocaleString('id-ID'));
            }
        });
    };

    // Ambil CSRF token lalu jalankan request
    $("#"+id_tombol).prop('disabled', true).html('<i class="ri-loader-4-line"></i> Memproses Data....');
    $.get('/generate-csrf-token', function(response) {
        dataParameter._token = response.csrf_token;
        dataParameter.jenis_laporan = jenis_laporan;
        handleAjaxRequest(dataParameter);
        $("#"+id_tombol).prop('disabled', false).html('Lihat Laporan');
        $("#report_show_modal").modal('show');
    }).fail(function() {
        console.error("Gagal mendapatkan CSRF token.");
    });
}
$("#modal_proses_data_transaksi_tindakan").on('click', function() {
    report_show_modal(let_jenis_laporan,let_id_tombol);
})
$('#select_page_length_transaksi_tindakan').on('change', function() {
    $("#datatables_laporan_tindakan").DataTable().page.len($(this).val()).draw();
    $("#datatables_laporan_tindakan").DataTable().ajax.reload();
});