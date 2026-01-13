let let_jenis_laporan, let_id_tombol, datatables_laporan;
$(document).ready(function(){
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
});
function getRowGroupConfig(jenis_laporan) {
    let rowGroupConfig = {};
    if (jenis_laporan == "kuitansi_personal" || jenis_laporan == "kuitansi_perusahaan" || jenis_laporan == "tagihan_perusahaan") {
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
        case 'kuitansi_personal':
            $("#modal_title").html("Cetak Kuitansi Personal Periode : "+tanggal_awal_nilai+" s.d "+tanggal_akhir_nilai);
            break;
        case 'kuitansi_perusahaan':
            $("#modal_title").html("Cetak Kuitansi Perusahaan Periode : "+tanggal_awal_nilai+" s.d "+tanggal_akhir_nilai);
            break;
        case 'tagihan_perusahaan':
            $("#modal_title").html("Cetak Kuitansi Tagihan Periode : "+tanggal_awal_nilai+" s.d "+tanggal_akhir_nilai);
            break;
        default:
            $("#modal_title").html("Laporan Penjualan");
            break;
    }
    let columnsConfig = [];
    $("#total_all").html("0".toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
    if (jenis_laporan === 'kuitansi_personal') {
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
            { title: "Nama Peserta", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.nama_peserta}`;
                }
                return data;
            }},
            { title: "Jenis Tindakan", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.jenis_tindakan.replaceAll("_", " ").toUpperCase()}`;
                }
                return data;
            }},
            { title: "Nama Perusahaan", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.nama_perusahaan}`;
                }
                return data;
            }},
            { title: "Nama Departemen", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.nama_departemen}`;
                }
                return data;
            }},
            { title: "Total Transaksi", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${(row.total_transaksi).toLocaleString('id-ID')}`;
                }
                return data;
            }},
            { title: "Aksi", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `<button class="btn btn-primary btn-sm" onclick="cetak_kuitansi('kuitansi_personal','${row.id_mcu}','${row.nomor_mcu}','${row.nik_peserta}')"><i class="fa fa-print"></i> Cetak Kuitansi</button>`;
                }
                return data;
            }}

        ];
    } else if (jenis_laporan == "kuitansi_perusahaan" || jenis_laporan == "tagihan_perusahaan") {
        columnsConfig = [
            { title: "No", className: "text-center align-middle", render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { title: "Nomor Tagihan", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.nomor_tagihan}`;
                }
                return data;
            }},
            { title: "Nomor Surat", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.nomor_surat}`;
                }
                return data;
            }},
            { title: "Kode Perusahaan", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.company_code}`;
                }
                return data;
            }},
            { title: "Nama Perusahaan", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.company_name} ${row.company_alias_name ? `(${row.company_alias_name})` : ''}`;
                }
                return data;
            }},
            { title: "Alamat Perusahaan", className: "align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${row.alamat}`;
                }
                return data;
            }},
            { title: "Total Tagihan", className: "text-end align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                    return `${(row.total_transaksi).toLocaleString('id-ID')} (${row.total_transaksi_count} Peserta)`;
                }
                return data;
            }},
            { title: "Aksi", className: "text-center align-middle", render: function(data, type, row, meta) {
                if (type === 'display') {
                   return `<div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-primary btn-sm" onclick="cetak_kuitansi('tagihan_perusahaan','${row.id_perusahaan}','${row.company_code}','${row.company_name}','${row.company_alias_name}','${row.nomor_tagihan}','${row.nomor_surat}')">
                            <i class="fa fa-print"></i> Tagihan
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="cetak_kuitansi('kuitansi_perusahaan','${row.id_perusahaan}','${row.company_code}','${row.company_name}','${row.company_alias_name}','${row.nomor_tagihan}','${row.nomor_surat}')">
                            <i class="fa fa-print"></i> Kuitansi
                        </button>
                        <button class="btn btn-success btn-sm" onclick="ubah_status_pembayaran('selesai','${row.id_perusahaan}','${row.company_code}','${row.company_name}','${row.company_alias_name}','${row.nomor_tagihan}','${row.nomor_surat}')">
                            <i class="fa fa-check"></i> Status Lunas
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="ubah_status_pembayaran('proses','${row.id_perusahaan}','${row.company_code}','${row.company_name}','${row.company_alias_name}','${row.nomor_tagihan}','${row.nomor_surat}')">
                            <i class="fa fa-spinner"></i> Status Proses
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="hapus_status_pembayaran('hapus','${row.id_perusahaan}','${row.company_code}','${row.company_name}','${row.company_alias_name}','${row.nomor_tagihan}','${row.nomor_surat}')">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </div>`;
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
        if ($.fn.DataTable.isDataTable('#datatables_laporan_kuitansi')) {
            $("#datatables_laporan_kuitansi").DataTable().destroy();
            $("#datatables_laporan_kuitansi").empty();
        }
        $("#datatables_laporan_kuitansi").DataTable({
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
                "url": baseurlapi + '/laporan/kuitansi/' + jenis_laporan,
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
                if (jenis_laporan === 'kuitansi_personal') {
                    dataTotal.forEach(function (item) {
                        totalAll += item.total_transaksi;
                    });   
                }else if (jenis_laporan === 'kuitansi_perusahaan') {
                    dataTotal.forEach(function (item) {
                        totalAll += item.total_transaksi;
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
function cetak_kuitansi(jenis_kuitansi, param1 = null, param2 = null, param3 = null, param4 = null, param5 = null, param6 = null) {
    let nomortagihan = ""
    if (param5 !== "") nomortagihan = " dengan Nomor Tagihan <strong>"+param5+"</strong>"; 
    let html_view = `
    <div class="mt-3 text-center">
        <dotlottie-player
            src="https://lottie.host/bd547524-05f2-4284-8014-58bc610eff0a/s2OEsEAHMn.lottie"
            background="transparent"
            speed="1"
            style="width:150px;height:150px;margin:0 auto"
            direction="1"
            playMode="normal"
            loop
            autoplay>
        </dotlottie-player>

        <div>
            <h4>Konfirmasi Cetak Kuitansi</h4>
            <p class="text-muted mx-4 mb-0">
                Apakah anda yakin ingin meninjau kuitansi <strong>${param2}</strong> ${nomortagihan}?
                Silahkan isi keterangan dan pilih penanggung jawab kuitansi ini
            </p>
            <br>
            <select class="form-control" id="direktur_keuangan">
                ${options}
            </select>
        </div>
    </div>`;
    Swal.fire({
        html: html_view,
        target: document.getElementById('report_show_modal'),
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Cetak PDF',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            let dataparameter = "";
            if (jenis_kuitansi == "kuitansi_personal") {
                dataparameter = btoa(JSON.stringify({
                    id_mcu: param1,
                    nomor_mcu: param2,
                    nik_peserta: param3,
                    jenis_kuitansi:
                    jenis_kuitansi,
                    keterangan: param6,
                    id_direktur_keuangan: $('#direktur_keuangan').val(),
                    nama_direktur_keuangan: $('#direktur_keuangan').find('option:selected').text(),
                    nomor_surat: $('#nomor_surat').val()
                }));
                window.open(baseurl + '/laporan/kuitansi/personal/cetak?data='+dataparameter,'_blank');
            }else if (jenis_kuitansi == "kuitansi_perusahaan" || jenis_kuitansi == "tagihan_perusahaan") {
                dataparameter = btoa(JSON.stringify({
                    id_perusahaan: param1, 
                    kode_perusahaan: param2, 
                    nama_perusahaan: param3, 
                    alias_nama_perusahaan: param4,
                    jenis_kuitansi: jenis_kuitansi, 
                    jenis_transaksi: $("#jenis_transaksi").val(),
                    jenis_layanan: $("#jenis_layanan").val(),
                    status_pembayaran: $("#status_pembayaran").val(),
                    nomor_surat: param6,
                    id_direktur_keuangan: $('#direktur_keuangan').val(),
                    nama_direktur_keuangan: $('#direktur_keuangan').find('option:selected').text()
                }));
                if (jenis_kuitansi == "kuitansi_perusahaan") {
                    window.open(baseurl + '/laporan/kuitansi/perusahaan/cetak?data='+dataparameter,'_blank');  
                }else if (jenis_kuitansi == "tagihan_perusahaan") {
                    window.open(baseurl + '/laporan/kuitansi/tagihan_perusahaan/cetak?data='+dataparameter,'_blank');  
                }
            }
        }
    })
    
}
function ubah_status_pembayaran(status_pembayaran, param1 = null, param2 = null, param3 = null, param4 = null, param5 = null, param6 = null){
    let html_view = `
    <div class="mt-3 text-center">
        <dotlottie-player
            src="https://lottie.host/bd547524-05f2-4284-8014-58bc610eff0a/s2OEsEAHMn.lottie"
            background="transparent"
            speed="1"
            style="width:150px;height:150px;margin:0 auto"
            direction="1"
            playMode="normal"
            loop
            autoplay>
        </dotlottie-player>

        <div>
            <h4>Ubah Status Transaksi</h4>
            <p class="text-muted mx-4 mb-0">
                Apakah anda ingin mengubah transaksi menjadi <strong>${status_pembayaran.toUpperCase()}</strong><br>
                Pada Nomor tagihan: <strong>${param5}</strong> dengan Nomor Surat <strong>${param6}</strong>
            </p>
        </div>
    </div>`;
    Swal.fire({
        html: html_view,
        target: document.getElementById('report_show_modal'),
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'TRANSAKSI '+status_pembayaran.toUpperCase(),
        cancelButtonText: 'NANTI DULU!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laporan/rekap/status_transaksi',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token:response.csrf_token,
                        status_pembayaran:status_pembayaran,
                        nomor_tagihan:param5,
                        nomor_surat:param6,
                        id_perusahaan:param1,
                    },
                    success: function(response) {
                        return createToast("Ubah Status Tagihan Berhasil", 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    })
}
function hapus_status_pembayaran(status_pembayaran, param1 = null, param2 = null, param3 = null, param4 = null, param5 = null, param6 = null){
    let html_view = `
    <div class="mt-3 text-center">
        <dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player>
        </dotlottie-player>
        <div>
            <h4>Hapus Status Transaksi</h4>
            <p class="text-muted mx-4 mb-0">
                Apakah anda ingin menghapus transaksi <strong>${status_pembayaran.toUpperCase()}</strong><br>
                Pada Nomor tagihan: <strong>${param5}</strong> dengan Nomor Surat <strong>${param6}</strong>
            </p>
        </div>
    </div>`;
    Swal.fire({
        html: html_view,
        target: document.getElementById('report_show_modal'),
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'HAPUS TRANSAKSI',
        cancelButtonText: 'NANTI DULU!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laporan/rekap/hapus_transaksi',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token:response.csrf_token,
                        status_pembayaran:status_pembayaran,
                        nomor_tagihan:param5,
                        nomor_surat:param6,
                        id_perusahaan:param1,
                    },
                    success: function(response) {
                        $("#datatables_laporan_tindakan").DataTable().ajax.reload();
                        return createToast("Hapus Status Tagihan Berhasil", 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    })
}