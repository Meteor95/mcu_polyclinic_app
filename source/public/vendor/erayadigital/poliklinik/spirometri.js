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
let quill;
let croppedImages = []; 
$(document).ready(function(){
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onloadcropperjs();
    onloaddatatables();
    const kesimpulan_citra_unggah_poli = new Choices('#kesimpulan_citra_unggah_poli',{
        placeholder: true,
        placeholderValue: 'Pilih kesimpulan yang sesuai dengan kondisi pasien',
    });
    const detail_penjelasan_citra_unggah_poli = new Choices('#detail_penjelasan_citra_unggah_poli',{
        placeholder: true,
        placeholderValue: 'Pilih detail penjelasan yang sesuai dengan kondisi pasien',
    });
    quill = new Quill('#editor_poliklinik', {
        placeholder: 'Tuliskan Riwayat Kecelakaan Kerja',
        modules: {
            toolbar: {
              container: toolbarOptions,
            }
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
                "url": baseurlapi + '/pendaftaran/daftarpasien_unggah_citra',
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
                    title: "Foto Peserta MCU",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<img onclick="lihatFoto('${row.data_foto}','${row.nama_peserta}')" class="rounded img-thumbnail" src="${row.data_foto}" style="width: 100px; height: auto; aspect-ratio: 3 / 4; object-fit: cover;border-radius: 25%;cursor: pointer;">`;
                        }
                        return data;
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
                            return `<div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                <button onclick="unduhFoto('${row.data_foto}')" class="btn btn-success w-100">
                                    <i class="fa fa-download"></i> Unduh Foto 
                                </button>
                                <button onclick="hapusunduhanFoto('${row.id_peserta_gambar}','${row.nama_peserta}','${row.no_transaksi}')" class="btn btn-danger w-100">
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
$("#kotak_pencarian_daftarpeserta").on('keyup', debounce(function() {
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
    if (cropper) {
        const canvas = cropper.getCroppedCanvas();
        if (canvas) {
            const croppedImageDataURL = canvas.toDataURL('image/png');
            croppedImages.push(croppedImageDataURL);
            const imgElement = `
                <div class="col-md-4 preview-item text-center mb-3">
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
    if ($("#pencarian_member_mcu").val() == null){
        return createToast('Kesalahan Unggahan', 'top-right', 'Silahkan tentukan peserta terlebih dahulu untuk dijadikan laporan MCU', 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penyimpanan Data '+title_poliklinik+'</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi member MCU <strong>'+$("#nama_peserta_temp_1").text()+'</strong> ?. Jika sudah silahkan tentukan paket MCU',
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
            croppedImages.forEach((base64Image, index) => {
                const blob = dataURLToBlob(base64Image);
                const timestamp = Date.now();
                const fileName = `unggahan_${$("#nomor_transaksi_temp").text()}_${jenis_poli}_${timestamp}_${index + 1}.png`;
                const file = new File([blob], fileName, { type: 'image/png' });
                formData.append('citra_unggahan_poliklinik[]', file);
            });
            formData.append('user_id', $("#user_id_temp").text());
            formData.append('transaksi_id', $("#id_transaksi_mcu").text());
            formData.append('judul_laporan', $("#judul_citra_unggah_poli").val());
            formData.append('kesimpulan', $('#kesimpulan_citra_unggah_poli option:selected').text());
            formData.append('detail_kesimpulan', detail_kesimpulan);
            formData.append('catatan_kaki', $("#catatan_kaki_citra_unggah_poli").val());
            $.ajax({
                url: baseurlapi + '/poliklinik/simpan/'+jenis_poli,
                type: 'POST',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (!response.success){
                        return createToast('Data Conflict '+response.rc, 'top-right', response.message, 'error', 3000);
                    }
                    createToast('Unggahan Citra Berhasil', 'top-right', response.message, 'success', 3000);
                },
                error: function(xhr, status, error) {
                    createToast('Kesalahan Unggah Citra', 'top-right', xhr.responseJSON.message, 'error', 3000);
                }
            });
        }
    });
});
function clear_form(){
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
    citra_proses_crop.hide();
    tampil_canvas.hide();
    webcamPreview.hide();
    previewCanvas.hide();
    $("#citra_pasien").val(null);
    $("#preview_citra_pasien_canvas").hide();
    
}
function unduhFoto(lokasi_gambar){
    window.open(lokasi_gambar, '_blank');
}
function hapusunduhanFoto(id,nama_peserta,nomor_mcu){
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
                    url: baseurlapi + '/pendaftaran/hapusunduhan_citra_peserta',
                    type: 'GET',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token : response.csrf_token,
                        id : id,
                        nomor_mcu : nomor_mcu,
                        nama_peserta : nama_peserta
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
function lihatFoto(lokasi_gambar,nama_peserta){
    $("#foto_lihat").attr('src', lokasi_gambar);
    $("#nama_peserta_foto").text(nama_peserta);
    $("#modalLihatFoto").modal('show');
}