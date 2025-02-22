let isedit,jenis_poli_kolom = true;
const webcamButton = $('#ambil_dari_webcame');
const webcamPreview = $('#webcam-preview');
const tampil_canvas = $('#panggil_webcame');
const tangkap_citra = $('#tangkap_citra_cropper_js');
const fileInput = $('#citra_pasien');
const citra_proses_crop = $('#citra_proses_crop');
const image = $('#tampilan_citra_unggahan');
const cropButton = $('#crop-btn');
const previewCanvas = $('#preview_citra_pasien');
let cropper;
const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],     
    ['blockquote', 'code-block'],
    ['link', 'formula'],          
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
let quill,quill_informasi;
let croppedImages = []; 
let originalFileNames = [];
let kesimpulan_citra_unggah_poli,detail_penjelasan_citra_unggah_poli,dokter_citra_unggah_poli;
let dokter_citra_unggah_poliElement = document.getElementById('dokter_citra_unggah_poli');
$(document).ready(function(){
    isedit = false;
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onloadcropperjs();
    onloaddatatables();
    kesimpulan_citra_unggah_poli = new Choices('#kesimpulan_citra_unggah_poli',{
        placeholder: true,
        placeholderValue: 'Pilih kesimpulan yang sesuai dengan kondisi pasien',
    });
    dokter_citra_unggah_poli = new Choices(dokter_citra_unggah_poliElement,{
        searchEnabled: true,
        shouldSort: false,
        placeholder: true,
        placeholderValue: 'Pilih dokter yang bertugas',
    });
    detail_penjelasan_citra_unggah_poli = new Choices('#detail_penjelasan_citra_unggah_poli',{
        placeholder: true,
        placeholderValue: 'Pilih detail penjelasan yang sesuai dengan kondisi pasien',
    });
    quill = new Quill('#editor_poliklinik', {
        placeholder: 'Berikan keterangan secara jelas mengenai hasil scan citra pada poliklinik ini',
        modules: {
            toolbar: {
              container: toolbarOptions,
            }
        },
        theme: 'snow'
    });
    quill_informasi = new Quill('#detail_kesimpulan_informasi', {
        placeholder: 'Ketikkan Kesimpulan atas tindakan pada poliklinik ini',
        modules: {
            "toolbar": false
        },
        theme: 'snow'
    });
});
$('#detail_penjelasan_citra_unggah_poli').on('change', function () {
    const selectedText = $(this).find('option:selected').text();
    quill.insertText(quill.getLength(), `${selectedText}\n`, 'list', 'ordered');
});
function onloaddatatables(){
    $.get('/generate-csrf-token', function(response) {
        switch(jenis_poli){
            case 'farmingham_score':
                jenis_poli_kolom = false;
                break;
        }
        $("#datatables_daftarpeserta_unggah_citra").DataTable({
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
                "url": baseurlapi + '/poliklinik/daftar_citra_unggahan_poliklinik',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_daftarpasien").val();
                    d.jenis_poli = jenis_poli;
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
                    className: "text-center align-middle",
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: "Foto "+title_poliklinik,
                    className: "text-center align-middle",
                    visible: jenis_poli_kolom,
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<img onclick="lihatFoto('${row.id_trx_poli}','${row.nama_peserta}','${row.data_foto}')" class="rounded img-thumbnail" src="${row.data_foto}" style="width: 100px; height: auto; aspect-ratio: 3 / 4; object-fit: cover;border-radius: 25%;cursor: pointer;"><br><button onclick="lihatFotoDetail('${row.id_trx_poli}','${row.nama_peserta}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> ${row.jumlah_citra} Foto 
                                </button>`;
                        }
                        return data;
                    }
                },
                {
                    title: "Informasi Peserta MCU",
                    className: "align-middle",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Nomor MCU : ${row.no_transaksi}<br>
                            Nama Peserta : ${row.nama_peserta} (${row.umur}Th)<br>
                            Tanggal Transaksi MCU : ${row.tanggal_transaksi_mcu}<br>
                            Tanggal Transaksi Poliklinik : ${row.tanggal_transaksi_poliklinik}<br>
                            Kesimpulan : ${row.kesimpulan}<br>
                            `;
                        }
                        return data;
                    }
                },
                {
                    title: "Informasi Pekerjaan",
                    className: "align-middle",
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
                    className: "dtfc-fixed-right_header align-middle",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            id = row.id_trx_poli ?? row.id;
                            return `<div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                <button class="btn btn-success w-100" onclick="lihatInformasi('${id}','${row.nama_peserta}')">
                                    <i class="fa fa-file"></i> Lihat Informasi 
                                </button>
                                <button class="btn btn-success w-100" onclick="ubah_informasi('${id}','${row.nomor_identitas}','${row.nama_peserta}')">
                                    <i class="fa fa-edit"></i> Ubah Informasi 
                                </button>
                                <button onclick="hapusunduhanFoto('${id}','${row.nama_peserta}','${row.transaksi_id}','${row.no_transaksi}')" class="btn btn-danger w-100">
                                    <i class="fa fa-trash-o"></i> Hapus Foto
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
    $("#datatables_daftarpeserta_unggah_citra").DataTable().ajax.reload();
}, 300));
function onloadcropperjs(){
    fileInput.on('change', (event) => {
        const file = event.target.files[0];
        citra_proses_crop.show();
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                image.attr('src', e.target.result).css({ display: 'block', height: '100%' });
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(image[0], {
                    viewMode: 1,
                    background: false,
                });
            };
            reader.readAsDataURL(file);
        }
    });
    cropButton.on('click', () => {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas();
            if (canvas) {
                const croppedImageDataURL = canvas.toDataURL('image/png');
                $("#preview_citra_pasien_img").attr('src', croppedImageDataURL).show();
                $("#preview_citra_pasien_canvas").hide();
                previewCanvas.hide();
            }
        }
    });
}
$("#tambah_foto_poliklinik").on('click', function() {
    const fileInput = document.getElementById('citra_pasien');
    if (fileInput.files.length === 0) {
        return createToast('Kesalahan Unggahan', 'top-right', `Silahkan tentukan foto dari ${title_poliklinik} terlebih dahulu untuk dijadikan laporan MCU`, 'error', 3000);
    }
    const originalFileName = fileInput.files[0].name;
    if (cropper) {
        const canvas = cropper.getCroppedCanvas();
        if (canvas) {
            const croppedImageDataURL = canvas.toDataURL('image/png');
            croppedImages.push(croppedImageDataURL);
            originalFileNames.push(originalFileName);
            const imgElement = `
                <div class="col-md-4 preview-item text-center mb-3">
                    <span class="nama_file_asli">${originalFileName}</span> 
                    <img src="${croppedImageDataURL}" class="img-fluid mb-1" style="max-width: 100%;">
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-danger delete-preview w-100">Hapus</button>
                        <button class="btn btn-primary btn-lihat w-100">Lihat</button>
                    </div>
                </div>`;
            $("#preview-list").append(imgElement);
        }
    }
})
$(document).on("click", ".delete-preview", function () {
    $(this).closest(".preview-item").remove();
});
$(document).on('click', '.btn-lihat', function () {
    const imgSrc = $(this).closest('.preview-item').find('img').attr('src');
    $('#modalImage').attr('src', imgSrc);
    $('#imageModal').modal('show');
});
$("#simpan_foto_perserta").on('click', function() {

    if ($("#dokter_citra_unggah_poli").val() == null){
        return createToast('Kesalahan Unggahan', 'top-right', 'Silahkan tentukan dokter yang bertugas terlebih dahulu untuk dijadikan laporan MCU', 'error', 3000);
    }
    if ($("#pencarian_member_mcu").val() == null){
        return createToast('Kesalahan Unggahan', 'top-right', 'Silahkan tentukan peserta terlebih dahulu untuk dijadikan laporan MCU', 'error', 3000);
    }
    if ($("#pdf_file")[0].files[0] == null){
        if (croppedImages.length == 0 && (title_poliklinik.toLowerCase().replace(/ /g, "") !== "poliklinikfarminghamscore")){
            return createToast('Kesalahan Unggahan', 'top-right', 'Silahkan tentukan foto dari '+title_poliklinik+' minimal 1 Foto terlebih dahulu untuk dijadikan laporan MCU', 'error', 3000);
        }
    }
    if ($("#judul_citra_unggah_poli").val() == ""){
        return createToast('Kesalahan Unggahan', 'top-right', 'Silahkan tentukan kesimpulan dan judul dari '+title_poliklinik+' terlebih dahulu untuk dijadikan laporan MCU', 'error', 3000);
    }
    $("#simpan_foto_perserta").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Proses Simpan Data');
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penyimpanan Data '+title_poliklinik+'</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi member MCU <strong>'+$("#nama_peserta_temp_1").text()+'</strong> ?. Jika sudah silahkan tentukan paket MCU. OH ya citra unggahan akan disimpan dalam bentuk PNG dengan TRANSPARANSI AKTIF',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            const quillContent = quill.getContents();
            let detail_kesimpulan;
            if (quillContent.ops.length === 1 && quillContent.ops[0].insert === '\n') {
                detail_kesimpulan = JSON.stringify([{ insert: "Tidak Ada Keterangan Riwayat Kecelakaan Kerja" }]);
            } else {
                detail_kesimpulan = JSON.stringify(quillContent.ops);
            }
            formData.append('isedit', isedit);
            formData.append('pegawai_id', $("#dokter_citra_unggah_poli").val());
            formData.append('user_id', $("#user_id_temp").text());
            formData.append('transaksi_id', $("#id_transaksi_mcu").text());
            formData.append('judul_laporan', $("#judul_citra_unggah_poli").val());
            formData.append('id_kesimpulan', $('#kesimpulan_citra_unggah_poli').val());
            formData.append('kesimpulan', $('#kesimpulan_citra_unggah_poli option:selected').text());
            formData.append('detail_kesimpulan', detail_kesimpulan);
            formData.append('catatan_kaki', $("#catatan_kaki_citra_unggah_poli").val());
            formData.append('citra_unggahan_poliklinik_pdf', $("#pdf_file")[0].files[0]);
            croppedImages.forEach((base64Image, index) => {
                const isURL = (str) => {
                    const urlPattern = /^(http|https):\/\/[^\s$.?#].[^\s]*$/;
                    return urlPattern.test(str);
                };
                if (!isURL(base64Image)) {
                    const blob = dataURLToBlob(base64Image);
                    const fileName = originalFileNames[index];
                    const file = new File([blob], fileName, { type: 'image/png' });
                    formData.append('citra_unggahan_poliklinik[]', file);
                }   
            });
            $.ajax({
                url: baseurlapi + '/poliklinik/simpan/'+jenis_poli,
                type: 'POST',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#simpan_foto_perserta").prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Data');
                    if (!response.success){
                        return createToast('Data Conflict '+response.rc, 'top-right', response.message, 'error', 3000);
                    }
                    createToast('Unggahan Citra Berhasil', 'top-right', response.message, 'success', 3000);
                    clear_form();
                    $("#datatables_daftarpeserta_unggah_citra").DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    $("#simpan_foto_perserta").prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Data');
                    createToast('Kesalahan Unggah Citra', 'top-right', xhr.responseJSON.message, 'error', 3000);
                }
            });
        }else{
            $("#simpan_foto_perserta").prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Data');
        }
    });
});
$("#bersihkan_formulir_unggah_citra").on('click', function(){
    clear_form();
});
function clear_form(){
    isedit = false;
    $("#simpan_foto_perserta").prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Data');
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
    $("#judul_citra_unggah_poli").val("");
    $("#kesimpulan_citra_unggah_poli").val(null).trigger('change');
    $("#detail_penjelasan_citra_unggah_poli").val(null).trigger('change');
    quill.setContents([]);
    $("#preview-list").empty();
    $("#pdf_file").val('');
    croppedImages = []; 
    originalFileNames = [];
    citra_proses_crop.hide();
    tampil_canvas.hide();
    previewCanvas.hide();
    $("#citra_pasien").val(null);
    $("#preview_citra_pasien_img").hide();
    dokter_citra_unggah_poli.setChoiceByValue(dokter_citra_unggah_poliElement.options[0].value);
}
function unduhFoto(lokasi_gambar){
    window.open(lokasi_gambar, '_blank');
}
function hapusunduhanFoto(id_trx_poli,nama_peserta,transaksi_id, nomor_mcu){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penghapusan Citra Unggahan MCU</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi ungahan citra MCU <strong>'+nama_peserta+'</strong> dengan Nomor MCU <strong>'+nomor_mcu+'</strong> ? Silahkan unggah citra kembali jika ingin menampilkan citra tersebut jika tidak foto akan ditampilkan dengan citra dasar dari sistem MCU',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/poliklinik/hapus_citra_unggahan_poliklinik',
                    type: 'GET',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token : response.csrf_token,
                        id_trx_poli : id_trx_poli,
                        transaksi_id : transaksi_id,
                        nomor_mcu : nomor_mcu,
                        nama_peserta : nama_peserta,
                        jenis_poli : jenis_poli
                    },
                    success: function(response) {
                        clear_form();
                        $("#datatables_daftarpeserta_unggah_citra").DataTable().ajax.reload();
                        createToast('Sukses Hapus Foto', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Hapus Foto', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            })
        }
    });
}
function lihatFoto(id_trx_poli,nama_peserta,lokasi_gambar){
    $("#foto_lihat").attr('src', lokasi_gambar);
    $("#nama_peserta_foto").text(nama_peserta);
    $("#modalLihatFoto").modal('show');
}
function lihatFotoDetail(id_trx_poli,nama_peserta){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/poliklinik/detail_citra_unggahan_poliklinik',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token : response.csrf_token,
                id_trx_poli : id_trx_poli,
                jenis_modal: "lihat_foto_detail",
                jenis_poli: jenis_poli
            },
            success: function(response) {
                // Reset carousel content
                let carouselIndicators = '';
                let carouselInner = '';

                response.foto.forEach(function(item, index) {
                    // Tambahkan indikator
                    carouselIndicators += `
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="${index}" 
                                class="${index === 0 ? 'active' : ''}" 
                                aria-current="${index === 0 ? 'true' : 'false'}" 
                                aria-label="Slide ${index + 1}">
                        </button>`;

                    // Tambahkan item carousel
                    carouselInner += `
                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                            <img class="d-block w-100" src="${item.data_foto}">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>${item.nama_file_asli}</h5>
                                <p>${moment(item.created_at).format('DD-MM-YYYY HH:mm:ss')}</p>
                            </div>
                        </div>`;
                });

                // Masukkan indikator dan item ke dalam carousel
                $('.carousel-indicators').html(carouselIndicators);
                $('.carousel-inner').html(carouselInner);
                $("#modalLihatFotoDetailLabel").text('Foto Pasien '+nama_peserta+' '+title_poliklinik);
                $("#modalLihatFotoDetail").modal('show');
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Lihat Foto Detail', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        })
    })
}
function lihatInformasi(id_trx_poli,nama_peserta){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/poliklinik/detail_citra_unggahan_poliklinik',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token : response.csrf_token,
                id_trx_poli : id_trx_poli,
                jenis_modal: "lihat_informasi",
                jenis_poli: jenis_poli
            },
            success: function(response) {
                $("#judul_laporan_informasi").html(response.data.judul_laporan);
                $("#kesimpulan_informasi").html(response.data.kesimpulan);
                quill_informasi.setContents(JSON.parse(response.data.detail_kesimpulan));
                $("#modalLihatInformasiLabel").text('Informasi Pasien '+nama_peserta+' '+title_poliklinik);
                $("#modalLihatInformasi").modal('show');
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Lihat Informasi', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        })
    })
}
function ubah_informasi(id_trx_poli,nomor_identitas,nama_peserta){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/poliklinik/detail_citra_unggahan_poliklinik',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token : response.csrf_token,
                id_trx_poli : id_trx_poli,
                jenis_modal: "ubah_informasi",
                jenis_poli: jenis_poli
            },
            success: function(response) {
                isedit = true;
                let newOption = new Option('['+nomor_identitas+'] - '+nama_peserta, nomor_identitas, true, false);
                $("#pencarian_member_mcu").append(newOption).trigger('change');
                $("#pencarian_member_mcu").val(nomor_identitas).trigger('change');
                $("#judul_citra_unggah_poli").val(response.data[0].judul_laporan);
                kesimpulan_citra_unggah_poli.setChoiceByValue(response.data[0].id_kesimpulan.toString());
                $("#kesimpulan_citra_unggah_poli").val(response.data[0].id_kesimpulan.toString());
                dokter_citra_unggah_poli.setChoiceByValue(response.data[0].pegawai_id.toString());
                $("#dokter_citra_unggah_poli").val(response.data[0].pegawai_id.toString());
                quill.setContents(JSON.parse(response.data[0].detail_kesimpulan));
                $("#catatan_kaki_citra_unggah_poli").val(response.data[0].catatan_kaki);
                /* load gallery images */
                $("#preview-list").empty();
                response.data.forEach(function(item, index) {
                    croppedImages.push(item.data_foto);
                    originalFileNames.push(item.nama_file_asli);
                    const imgElement = `
                        <div class="col-md-4 preview-item text-center mb-3">
                            <span class="nama_file_asli">${item.nama_file_asli}</span> 
                            <img src="${item.data_foto}" class="img-fluid mb-1" style="max-width: 100%;">
                            <div class="d-flex justify-content-center gap-2">
                            <button onclick="hapus_foto_unggahan('${item.id_each_citra}','${item.nama_file_asli}', ${id_trx_poli})" class="btn btn-danger w-100">Hapus File</button>
                            <button class="btn btn-primary btn-lihat w-100">Lihat</button>
                            </div>
                        </div>`;
                    $("#preview-list").append(imgElement);
                });
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Lihat Informasi', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        })
    })
}
function hapus_foto_unggahan(id_each_citra,nama_file_asli,id_trx_poli){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penghapusan Citra Unggahan MCU</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus citra unggahan <strong>'+nama_file_asli+'</strong> dari database. Jika terhapus maka unggahan citra akan hilang dari database dan tidak dapat dikembalikan lagi dan anda harus menambahkan lagi citra unggahan tersebut',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/poliklinik/hapus_foto_unggahan_satuan',
                    type: 'GET',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token : response.csrf_token,
                        id_each_citra : id_each_citra,
                        nama_file_asli : nama_file_asli,
                        jenis_poli : jenis_poli,
                        id_trx_poli : id_trx_poli
                    },
                    success: function(response) {
                        if (response.success){
                            const fileNameToDelete = response.data.nama_file_asli;
                            $(".preview-item").each(function () {
                                const fileName = $(this).find(".nama_file_asli").text();
                                if (fileName === fileNameToDelete) {
                                    $(this).remove();
                                }
                            });
                            if (response.refresh){
                                $("#datatables_daftarpeserta_unggah_citra").DataTable().ajax.reload();
                            }
                            return createToast('Sukses Hapus Foto', 'top-right', response.message, 'success', 3000);
                        }
                        return createToast('Gagal Hapus Foto', 'top-right', response.message, 'error', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Hapus Foto', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            })
        }
    });
}