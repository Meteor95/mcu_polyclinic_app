let is_edit = false;
const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],     
    ['blockquote', 'code-block'],
    ['link', 'image', 'video', 'formula'],          
    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],     
    [{ 'indent': '-1'}, { 'indent': '+1' }],          
    [{ 'direction': 'rtl' }],                         
    [{ 'size': ['small', false, 'large', 'huge'] }], 
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
  
    [{ 'color': [] }, { 'background': [] }],
    [{ 'font': [] }],
    [{ 'align': [] }],
  
    ['clean']
  ];
const quill = new Quill('#editor_kecelakaan_kerja', {
    placeholder: 'Tuliskan Riwayat Kecelakaan Kerja',
    modules: {
        toolbar: {
          container: toolbarOptions,
        }
    },
    theme: 'snow'
});
const quill_detail = new Quill('#editor_riwayat_kecelakaan_kerja', {
    theme: 'snow'
});
$(document).ready(function(){
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onload_datatables_daftar_kecelakaan_kerja();
});
function onload_datatables_daftar_kecelakaan_kerja(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_daftar_kecelakaan_kerja").DataTable({
            searching: false,
            bProcessing: true,
            serverSide: true,
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
                "url": baseurlapi + '/pendaftaran/daftarpasien_riwayatkecelakaankerja',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_daftarpasien").val();
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
                    title: "Informasi Peserta MCU",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Nomor MCU : ${row.no_transaksi}<br>
                            Nama Peserta : ${row.nama_peserta} (${row.umur}Th)<br>
                            Tanggal Transaksi : ${row.tanggal_transaksi}<br>
                            `;
                        }
                        return data;
                    }
                },
                {
                    title: "Informasi Pekerjaan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Nama Perusahaan : ${row.company_name}<br>
                            Departemen : ${row.nama_departemen}<br>
                            Alamat Perusahaan: ${row.alamat}<br>
                            `;
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: "dtfc-fixed-right_header",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-between gap-2">
                                <button onclick="detail_data_riwayat_kecelakaan_kerja('${row.user_id}','${row.transaksi_id}','${row.nama_peserta}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> Lihat Riwayat
                                </button>
                                <button onclick="detail_data_riwayat_kecelakaan_kerja('${row.user_id}','${row.transaksi_id}','${row.nama_peserta}', '${row.nomor_identitas}', true)" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah Data
                                </button>
                                <button onclick="hapus_data_riwayat_kecelakaan_kerja('${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}','${row.user_id}')" class="btn btn-danger w-100">
                                    <i class="fa fa-trash-o"></i> Hapus Data
                                </button>
                            </div>`;
                        }       
                        return data;
                    }
                }
            ]
        });
    });
}
$("#kotak_pencarian_daftarpasien").on('keyup', debounce(function() {
    $("#datatables_daftar_kecelakaan_kerja").DataTable().ajax.reload();
}, 300));
$("#btnSimpanRiwayatKecelakaanKerja").on('click', function() {
    let pesan = "";
    if ($("#user_id_temp").text() == ""){
        return createToast('Kesalahan Penyimpanan', 'top-right', 'Silahkan tentukan peserta MCU terlebih dahulu sebelum menyimpan data atas formulir bahaya riwayat lingkungan kerja diatas', 'error', 3000);
    }
    if (quill.getLength() <= 1) {
        pesan = "<span class='text-danger'>Sistem mendeteksi tidak ada riwayat kecelakaan kerja, silahkan isi formulir riwayat kecelakaan kerja terlebih dahulu jikalau ada. Jika tidak ada maka sistem akan dituliskan Tidak Ada Riwayat Kecelakaan Kerja</span>";
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Formulir Kecelakaan Kerja</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Lingkungan Kerja Peserta MCU dengan atas nama : <strong>'+$("#nama_peserta_temp_1").text()+'</strong>? '+pesan,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                const quillContent = quill.getContents();
                let informasiRiwayatKecelakaanKerja;
                if (quillContent.ops.length === 1 && quillContent.ops[0].insert === '\n') {
                    informasiRiwayatKecelakaanKerja = JSON.stringify([{ insert: "Tidak Ada Keterangan Riwayat Kecelakaan Kerja" }]);
                } else {
                    informasiRiwayatKecelakaanKerja = JSON.stringify(quillContent.ops);
                }
                $.ajax({
                    url: baseurlapi + '/pendaftaran/simpanriwayatkecelakaankerja',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token : response.csrf_token,
                        user_id : $("#user_id_temp").text(),
                        id_transaksi : $("#id_transaksi_mcu").text(),
                        informasi_riwayat_kecelakaan_kerja : informasiRiwayatKecelakaanKerja,
                        is_edit : is_edit
                    },
                    success: function(response) {
                        is_edit = false;
                        clear_form();
                        $("#datatables_daftar_kecelakaan_kerja").DataTable().ajax.reload();
                        return createToast('Berhasil', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(response) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', response.message, 'error', 3000);
                    }
                });
            });
        }
    });
});
function clear_form(){
    $("#user_id_temp").text("");
    $("#nomor_identitas_temp").text("");
    $("#id_transaksi_mcu").text("");
    $("#nama_peserta_temp_1").text("");
    $("#nama_peserta_temp").text("");
    $("#no_telepon_temp").text("");
    $("#jenis_kelamin_temp").text("");
    $("#nomor_transaksi_temp").text("");
    $("#email_temp").text("");
    $("#tempat_lahir_temp").text("");
    $("#status_kawin_temp").text("");
    $("#pencarian_member_mcu").val(null).trigger('change'); 
    quill.setContents("");
    is_edit = false;
}
$("#bersihkan_data_riwayat_kecelakaan_kerja").on('click', function(){
    clear_form();
});
function detail_data_riwayat_kecelakaan_kerja(user_id,transaksi_id,nama_peserta, nomor_identitas = null, edit = false){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pendaftaran/riwayatkecelakaankerja',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                user_id: user_id,
                transaksi_id: transaksi_id
            },
            success: function(response) {
                if (edit){
                    let newOption = new Option('['+nomor_identitas+'] - '+nama_peserta, nomor_identitas, true, false);
                    $("#pencarian_member_mcu").append(newOption).trigger('change');
                    $("#pencarian_member_mcu").val(nomor_identitas).trigger('change');
                    quill.setContents(JSON.parse(response.data[0].riwayat_kecelakaan_kerja));
                    is_edit = true;
                }else{
                    quill_detail.setContents(JSON.parse(response.data[0].riwayat_kecelakaan_kerja));
                    $("#modal_nama_peserta_parameter").text(nama_peserta);
                    $("#modalLihatRiwayatKecelakaanKerja").modal('show');
                    is_edit = false;
                }
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
}
function hapus_data_riwayat_kecelakaan_kerja(transaksi_id, nomor_identitas, nama_peserta, user_id){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Data Kecelakaan Kerja</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus data kecelakaan kerja dengan atas nama : <strong>'+nama_peserta+'</strong> dengan nomor identitas : <strong>'+nomor_identitas+'</strong>?. Formulir ini bersifat wajib diisi oleh peserta MCU. Jadi silahkan isi kembali formulir tersebut jikalau dibutuhkan pada laporan MCU</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/pendaftaran/hapusriwayatkecelakaankerja',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token : response.csrf_token,
                        transaksi_id : transaksi_id,
                        user_id : user_id,
                        nomor_identitas : nomor_identitas,
                        nama_peserta : nama_peserta
                    },
                    success: function(response) {
                        clear_form();
                        is_edit = false;
                        $("#datatables_daftar_kecelakaan_kerja").DataTable().ajax.reload();
                        return createToast('Data Dihapus', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            });
        }
    });
}