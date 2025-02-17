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
    if (jenis_laporan == "transaksi_tindakan" || jenis_laporan == "transaksi_tindakan_terbanyak") {
        rowGroupConfig = false;
    } else if (jenis_laporan == "transaksi_tindakan_detail") {
        rowGroupConfig = {
            dataSrc: 'no_mcu',
            startRender: function (rows, group) {
                return $('<tr style="color:red">')
                    .append("<td colspan='8'>No MCU : " + rows.data()[0].no_mcu + "<br>Tanggal Transaksi : " + moment(rows.data()[0].waktu_trx).format('DD-MM-YYYY HH:mm:ss') + "<br>Nilai Apotek : " + rows.data()[0].total_bayar_apotek.toLocaleString('id-ID') + "</td>")
                    .append('</tr>');
            },
            endRender: function (rows, group) {
                return $('<tr style="font-weight:bold; text-align:right">')
                    .append("<td colspan='3'>Sub Total Baris</td>")
                    .append("<td>" + rows.data().reduce((sum, row) => sum + row.harga, 0).toLocaleString('id-ID') + "</td>")
                    .append("<td>" + rows.data().reduce((sum, row) => sum + row.diskon, 0).toLocaleString('id-ID') + "</td>")
                    .append("<td>" + rows.data().reduce((sum, row) => sum + row.harga_setelah_diskon, 0).toLocaleString('id-ID') + "</td>")
                    .append("<td>" + rows.data().reduce((sum, row) => sum + row.jumlah, 0).toLocaleString('id-ID') + "</td>")
                    .append("<td>" + rows.data().reduce((sum, row) => sum + (row.harga_setelah_diskon * row.jumlah), 0).toLocaleString('id-ID') + "</td>")
                    .append('</tr>');
            },
        };
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
        case 'transaksi_tindakan':
            $("#modal_title").html("Laporan Tindakan Data Transaksi (IDR) Periode : "+tanggal_awal_nilai+" s.d "+tanggal_akhir_nilai);
            break;
        case 'transaksi_tindakan_detail':
            $("#modal_title").html("Laporan Tindakan Detail Per Nota Transaksi (IDR) Periode : "+tanggal_awal_nilai+" s.d "+tanggal_akhir_nilai);
            break;
        case 'transaksi_tindakan_terbanyak':
            $("#modal_title").html("Laporan Tindakan Terbanyak (IDR) Periode : "+tanggal_awal_nilai+" s.d "+tanggal_akhir_nilai);
            break;
        default:
            $("#modal_title").html("Laporan Penjualan");
            break;
    }
    let columnsConfig = [];
    $("#total_all").html("0".toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
    if (jenis_laporan === 'transaksi_tindakan') {
        columnsConfig = [
            { title: "No", className: "text-center align-middle", render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { title: "No MCU", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.nomor_mcu}`;
                }
                return data;
            }},
            { title: "Status Pembayaran", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.status_pembayaran.toUpperCase()}`;
                }
                return data;
            }},
            { title: "Jenis Layanan", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.jenis_layanan.replaceAll('_', ' ').toUpperCase()}`;
                }
                return data;
            }},
            { title: "Jenis Transaksi", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    switch (row.jenis_transaksi) {
                        case 0:
                            jenis_transaksi_ket = "INVOICE";
                            break;
                        case 1:
                            jenis_transaksi_ket = "TUNAI";
                            break;
                        case 2:
                            jenis_transaksi_ket = "NON TUNAI";
                            break;
                    }
                    return `${jenis_transaksi_ket}`;
                }
                return data;
            }},
            { title: "Waktu Trx", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.waktu_trx}`;
                }
                return data;
            }},
            { title: "Total Tindakan", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.total_bayar_tindakan.toLocaleString('id-ID')}`;
                }
                return data;
            }},
            { title: "Total Apotek", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.total_bayar_apotek.toLocaleString('id-ID')}`;
                }
                return data;
            }},
            { title: "Sub Total", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${(row.total_bayar_tindakan + row.total_bayar_apotek).toLocaleString('id-ID')}`;
                }
                return data;
            }},
        ];
    } else if (jenis_laporan === 'transaksi_tindakan_detail') {
        columnsConfig = [
            { title: "No", className: "text-center align-middle", render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { title: "Kode Item", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.kode_item}`;
                }
                return data;
            }},
            { title: "Nama Item", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.nama_item}`;
                }
                return data;
            }},
            { title: "Harga", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.harga.toLocaleString('id-ID')}`;
                }
                return data;
            }},
            { title: "Diskon", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.diskon.toLocaleString('id-ID')}`;
                }
                return data;
            }},
            { title: "Harga Setelah Diskon", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.harga_setelah_diskon.toLocaleString('id-ID')}`;
                }
                return data;
            }},
            { title: "Jumlah", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.jumlah}`;
                }
                return data;
            }},
            { title: "Sub Total", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${(row.jumlah * row.harga_setelah_diskon).toLocaleString('id-ID')}`;
                }
                return data;
            }},

        ];
    } else if (jenis_laporan === 'transaksi_tindakan_terbanyak') {
        columnsConfig = [
            { title: "No", className: "text-center align-middle", render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { title: "Kode Item", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.kode_item}`;
                }
                return data;
            }},
            { title: "Nama Tindakan", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.nama_item}`;
                }
                return data;
            }},
            { title: "Jumlah", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.jumlah}x`;
                }
                return data;
            }},
            { title: "Terakhir Tindakan", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${moment(row.waktu_trx).format('DD-MM-YYYY HH:mm:ss')}`;
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
        if ($.fn.DataTable.isDataTable('#datatables_laporan_tindakan')) {
            $("#datatables_laporan_tindakan").DataTable().destroy();
            $("#datatables_laporan_tindakan").empty();
        }
        $("#datatables_laporan_tindakan").DataTable({
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
                "url": baseurlapi + '/laporan/tindakan/' + jenis_laporan,
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
                let totalAll = 0, totalApotek = 0, totalTindakan = 0;
                if (jenis_laporan === 'transaksi_tindakan') {
                    dataTotal.forEach(function (item) {
                        totalApotek += item.total_bayar_apotek;
                        totalTindakan += item.total_bayar_tindakan;
                        totalAll += item.total_bayar_apotek + item.total_bayar_tindakan;
                    });   
                }else if (jenis_laporan === 'transaksi_tindakan_detail') {
                    const groupedData = dataTotal.reduce((acc, item) => {
                        if (!acc[item.id_transaksi]) {
                            acc[item.id_transaksi] = {
                                id_transaksi: item.id_transaksi,
                                nomor_nota: item.nomor_nota,
                                waktu_trx: item.waktu_trx,
                                total_bayar_apotek: 0,
                                total_bayar_tindakan: 0,
                                items: []
                            };
                        }
                        acc[item.id_transaksi].total_bayar_apotek = item.total_bayar_apotek;
                        acc[item.id_transaksi].total_bayar_tindakan = item.total_bayar_tindakan;
                        acc[item.id_transaksi].items.push(item);
                        return acc;
                    }, {});
                    const result = Object.values(groupedData);
                    totalApotek  = result.reduce((acc, item) => acc + item.total_bayar_apotek, 0); 
                    totalTindakan = result.reduce((acc, item) => acc + item.total_bayar_tindakan, 0);
                    totalAll = totalApotek + totalTindakan;
                }
                if (jenis_laporan === 'transaksi_tindakan_terbanyak') {
                    $("#footer_total").hide();
                    return false;
                }else{
                    $("#total_apotek").html(totalApotek.toLocaleString('id-ID'));
                    $("#total_tindakan").html(totalTindakan.toLocaleString('id-ID'));
                    $("#total_all").html(totalAll.toLocaleString('id-ID'));
                    $("#footer_total").show();
                }
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