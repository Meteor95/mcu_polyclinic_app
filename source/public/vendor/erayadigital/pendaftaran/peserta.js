$(document).ready(function() {
    loadDataPeserta();
});

function loadDataPeserta() {
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_daftarpeserta").DataTable({
            ordering: false,
            lengthChange: false,
            searching: false,
            bProcessing: true,
            serverSide: true,
            pagingType: "full_numbers",
            fixedColumns: true,
            scrollCollapse: true,
            fixedColumns: {
                right: 1,
                left: 0
            },
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            ajax: {
                "url": baseurlapi + '/pendaftaran/daftarpeserta',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_daftarpeserta").val();
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
                    const infoString = 'Halaman ke: ' + currentPage + ' Ditampilkan: ' + 10 + ' Jumlah Data: ' + recordsFiltered + ' data';
                    return infoString;
                }
            },
            pagingType: "full_numbers",
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
                    title: "Nomor Pemesanan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.no_pemesanan
                        }
                        return data;
                    }
                },
                {
                    title: "Nomor Identifikasi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nomor_identifikasi
                        }
                        return data;
                    }
                },
                {
                    title: "Nama Peserta",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nama_peserta
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<div class=\"d-flex justify-content-between gap-2 background_fixed_right_row\"><button class=\"btn btn-success w-100\" id=\"btn_validasi_peserta_"+row.nomor_identifikasi+"\" onclick=\"validasi_peserta('" + row.nomor_identifikasi + "',this.id)\"><i class=\"fa fa-check\"></i> Validasi Peserta</button><button class=\"btn btn-danger w-100\" id=\"btn_hapus_peserta_"+row.nomor_identifikasi+"\" onclick=\"hapus_peserta('" + row.nomor_identifikasi + "','"+row.nama_peserta+"',this.id)\"><i class=\"fa fa-trash-o\"></i> Hapus Peserta</button></div>";
                        }       
                        return data;
                    }
                }
            ]
        });
    }); 
}
$("#kotak_pencarian_daftarpeserta").on('keyup', debounce(function() {
    $("#datatables_daftarpeserta").DataTable().ajax.reload();
}, 300));
let json_data_diri = {};
let json_lingkungan_kerja = {};
let json_kecelakaan_kerja = {};
let json_kebiasaan_hidup = {};
let json_penyakit_terdahulu = {};
let json_penyakit_keluarga = {};
let json_imunisasi = {};
function validasi_peserta(nomor_identitas, idButton){
    $("#"+idButton).prop('disabled', true);
    $("#"+idButton).html('<i class="fa fa-spinner fa-spin"></i> Sedang Membaca Data');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pendaftaran/validasi_peserta',
            type: 'GET',
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
            },
            data: {
                _token: response.csrf_token,
                nomor_identitas: nomor_identitas
            },
            success: function(response) {
                json_data_diri = JSON.parse(response.json_data_diri);
                $("#modal_nomor_identitas").text(json_data_diri.nomor_identitas_temp);
                $("#modal_nama_peserta").text(json_data_diri.nama_peserta_temp);
                $("#modal_tempat_lahir").text(json_data_diri.tempat_lahir_temp);
                $("#modal_tanggal_lahir").text(json_data_diri.tanggal_lahir_peserta_temp);
                $("#modal_tipe_identitas").text(json_data_diri.tipe_identitas_temp);
                $("#modal_jenis_kelamin").text(json_data_diri.jenis_kelamin_temp);
                $("#modal_status_perkawinan").text(json_data_diri.status_perkawinan_temp);
                $("#modal_no_telepon").text(json_data_diri.no_telepon_temp);
                $("#modal_alamat_surel").text(json_data_diri.alamat_surel_temp);
                $("#modal_alamat_tempat_tinggal").text(json_data_diri.alamat_tempat_tinggal_temp);
                $("#modal_proses_kerja").text(json_data_diri.proses_kerja_temp);
                /*LINGKUNGAN KERJA*/
                $("#tabel_ini_lingkungan_kerja").html('');
                let hasilLingkunganKerja = '';
                json_lingkungan_kerja = JSON.parse(response.json_lingkungan_kerja);

                // Asumsikan json_lingkungan_kerja.lingkungan_kerja adalah array
                json_lingkungan_kerja.lingkungan_kerja.forEach(function(item) {
                    let namaAtribut = item.nama_atribut_lk;
                    let status = item.status;
                    let jamPerHari = item.jam_per_hari;
                    let selamaXTahun = item.selama_x_tahun;
                    hasilLingkunganKerja += '<tr>' +
                        '<td>' + namaAtribut + '</td>' +
                        '<td>' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
                        '<td>' + (jamPerHari == "" ? 0 : jamPerHari) + ' Jam Per Hari</td>' +
                        '<td> Selama ' + (selamaXTahun == "" ? 0 : selamaXTahun) + ' Tahun</td>' +
                    '</tr>';
                });
                $("#tabel_ini_lingkungan_kerja").append(hasilLingkunganKerja);
                /*KECELAKAAN KERJA*/
                json_kecelakaan_kerja = JSON.parse(response.json_kecelakaan_kerja);
                $("#modal_informasi_kecelakaan_kerja").text(json_kecelakaan_kerja.informasi_kecelakaan_kerja == "" ? "Tidak Ada Riwayat Kecelakaan Kerja" : json_kecelakaan_kerja.informasi_kecelakaan_kerja);
                /*KEBIASAAN HIDUP*/
                $("#tabel_ini_kebiasaan_hidup").html('');
                let hasilKebiasaanHidup = '';
                json_kebiasaan_hidup = JSON.parse(response.json_kebiasaan_hidup);
                json_kebiasaan_hidup.kebiasaan_hidup.forEach(function(item) {
                    let namaAtribut = item.nama_atribut_kb;
                    let status = item.status;
                    let nilai = item.nilai;
                    if (item.info.toLowerCase().trim() !== "waktu") {
                        nilai += " " + item.info;
                    }
                    hasilKebiasaanHidup += '<tr>' +
                        '<td style="text-align:left;width:50%;">' + namaAtribut + '</td>' +
                        '<td style="width:25%;">' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
                        '<td style="width:25%;">' + nilai + '</td>' +
                    '</tr>';
                });
                $("#tabel_ini_kebiasaan_hidup").append(hasilKebiasaanHidup);
                /*PENYAKIT TERDAHULU*/
                $("#tabel_ini_penyakit_terdahulu").html("");
                let hasilPenyakitTerdahulu = '';
                json_penyakit_terdahulu = JSON.parse(response.json_penyakit_terdahulu);
                json_penyakit_terdahulu.penyakit_terdahulu.forEach(function(item) {
                    let namaAtribut = item.info;
                    let status = item.status;
                    let nilai = item.keterangan;
                    hasilPenyakitTerdahulu += '<tr>' +
                        '<td style="text-align:left;width:50%;">' + namaAtribut + '</td>' +
                        '<td style="width:25%;">' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
                        '<td style="width:25%;">' + nilai + '</td>' +
                    '</tr>';
                });
                $("#tabel_ini_penyakit_terdahulu").append(hasilPenyakitTerdahulu);
                /*PENYAKIT KELUARGA*/
                $("#tabel_ini_penyakit_keluarga").html("");
                let hasilPenyakitKeluarga = '';
                json_penyakit_keluarga = JSON.parse(response.json_penyakit_keluarga);
                json_penyakit_keluarga.penyakit_keluarga.forEach(function(item) {
                    let namaAtribut = item.info;
                    let status = item.status;
                    let nilai = item.keterangan;
                    hasilPenyakitKeluarga += '<tr>' +
                        '<td style="text-align:left;width:50%;">' + namaAtribut + '</td>' +
                        '<td style="width:25%;">' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
                        '<td style="width:25%;">' + nilai + '</td>' +
                    '</tr>';
                });
                $("#tabel_ini_penyakit_keluarga").append(hasilPenyakitKeluarga);
                /*IMUNISASI*/
                $("#tabel_ini_imunisasi").html("");
                let hasilImunisasi = '';
                json_imunisasi = JSON.parse(response.json_imunisasi);
                json_imunisasi.imunisasi.forEach(function(item) {
                    let namaAtribut = item.info;
                    let status = item.status;
                    let nilai = item.keterangan;
                    hasilImunisasi += '<tr>' +
                        '<td style="text-align:left;width:50%;">' + namaAtribut + '</td>' +
                        '<td style="width:25%;">' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
                        '<td style="width:25%;">' + nilai + '</td>' +
                    '</tr>';
                });
                $("#tabel_ini_imunisasi").append(hasilImunisasi);
                $("#"+idButton).prop('disabled', false);
                $("#"+idButton).html('<i class="fa fa-check"></i> Validasi Peserta');
                $("#modalValidasiPeserta").modal('show');         
            },
            error: function(xhr, status, error) {
                $("#"+idButton).prop('disabled', false);
                $("#"+idButton).html('<i class="fa fa-check"></i> Validasi Peserta');
                return createToast('Kesalahan Validasi Data', 'top-right', error, 'error', 3000);
            }
        });
    });
}
function hapus_peserta(nomor_identifikasi, nama_peserta, idButton) {
    $("#"+idButton).prop('disabled', true);
    $("#"+idButton).html('<i class="fa fa-spinner fa-spin"></i> Akan Menghapus Data');
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penghapusan Data Member '+nama_peserta+'</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi member MCU <strong>'+nama_peserta+'</strong> dengan ID <strong>'+nomor_identifikasi+'</strong> ? Peserta yang dihapus harus mendaftar ulang pada website jikalau ingin melanjutkan pendaftaran menjadi pasien MCU',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/pendaftaran/hapuspeserta',
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: {
                        _token: response.csrf_token,
                        nomor_identifikasi: nomor_identifikasi,
                        nama_peserta: nama_peserta,
                    },
                    success: function(response) {
                        $("#datatables_daftarpeserta").DataTable().ajax.reload();
                        $("#"+idButton).prop('disabled', false);
                        $("#"+idButton).html('<i class="fa fa-trash-o"></i> Hapus Peserta');
                        return createToast('Informasi Peserta', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        $("#"+idButton).prop('disabled', false);
                        $("#"+idButton).html('<i class="fa fa-trash-o"></i> Hapus Peserta');
                        return createToast('Kesalahan Penghapusan Data', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }else{
            $("#"+idButton).prop('disabled', false);
            $("#"+idButton).html('<i class="fa fa-trash-o"></i> Hapus Peserta');
        }
    });
}
function jadikan_peserta() {
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Jadikan Peserta</h4><p class="text-muted mx-4 mb-0">Pastikan anda telah memverifikasi data dari calon peserta. Jikalau terdapat kesalahan dapat diubah pada masing masing menu yang terdapat kesalahan. Apakah anda ingin melanjukan untuk pemilihan jenis peserta MCU ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Jadikan Peserta',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/pendaftaran/formulir_tambah_peserta/'+$("#modal_nomor_identitas").text();
        }
    });
}