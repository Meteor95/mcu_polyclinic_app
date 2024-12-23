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
$(document).ready(function(){
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onloadcropperjs();
    onloaddatatables();
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
                    aspectRatio: 3 / 4,
                    viewMode: 1,
                    background: false,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    webcamButton.on('click', () => {
        navigator.mediaDevices
            .getUserMedia({ video: true })
            .then((mediaStream) => {
                stream = mediaStream;
                webcamPreview[0].srcObject = stream;
                webcamPreview.css('display', 'block');
                citra_proses_crop.hide();
                tampil_canvas.show();
            })
            .catch((err) => {
                return createToast('Kesalahan Membaca Perangkat','top-right', err, 'error', 3000);
            });
    });

    tangkap_citra.on('click', () =>{
        if (stream) {
            const video = webcamPreview[0];
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageDataUrl = canvas.toDataURL('image/png');
            image.attr('src', imageDataUrl).css({ display: 'block', height: '100%' });
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(image[0], {
                aspectRatio: 3 / 4,
                viewMode: 1,
                background: false,
            });
            citra_proses_crop.show();
            tampil_canvas.hide();
        }
    })
    cropButton.on('click', () => {
        if (cropper) {
            previewCanvas.show();
            $("#preview_citra_pasien_canvas").show()
            const canvas = cropper.getCroppedCanvas();
            previewCanvas[0].width = canvas.width;
            previewCanvas[0].height = canvas.height;
            previewCanvas.css('display', 'block');
            const ctx = previewCanvas[0].getContext('2d');
            ctx.drawImage(canvas, 0, 0, canvas.width, canvas.height);
        }
    });
}
function isCanvasEmpty(canvas) {
    const ctx = canvas.getContext('2d');
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data; 
    for (let i = 0; i < data.length; i += 4) {
        if (data[i + 3] !== 0) {
            return false;
        }
    }
    return true;
}
$("#simpan_foto_perserta").on('click', function() {
    if ($("#pencarian_member_mcu").val() == null){
        return createToast('Kesalahan Unggahan', 'top-right', 'Silahkan tentukan peserta terlebih dahulu untuk dijadikan laporan MCU', 'error', 3000);
    }
    if (isCanvasEmpty(previewCanvas[0])){
        return createToast('Kesalahan Unggahan', 'top-right', 'Silahkan tentukan unggahan citra terlebih dahulu untuk dijadikan laporan MCU atas Nama Peserta : '+$("#nama_peserta_temp_1").text()+' dengan Nomor Identitas : '+$("#nomor_identitas_temp").text(), 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penyimpanan Data Member MCU</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi member MCU <strong>'+$("#nama_peserta_temp_1").text()+'</strong> ?. Jika sudah silahkan tentukan paket MCU',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            const croppedCanvas = cropper.getCroppedCanvas();
            if (croppedCanvas) {
                croppedCanvas.toBlob((blob) => {
                    const formData = new FormData();
                    const timestamp = Date.now();
                    const generatedName = `cropped_image_${timestamp}`;
                    formData.append('foto', blob, generatedName);
                    formData.append('nomor_identitas', $("#pencarian_member_mcu").val());
                    formData.append('informasimember', $("#pencarian_member_mcu").text());
                    formData.append('id_transaksi', $("#id_transaksi_mcu").text());
                    $.ajax({
                        url: baseurlapi + '/pendaftaran/upload_citra_peserta',
                        type: 'POST',
                        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (!response.success){
                                return createToast('Data Conflict '+response.rc, 'top-right', response.message, 'error', 3000);
                            }
                            clear_form();
                            $("#datatables_daftarpeserta_unggah_citra").DataTable().ajax.reload();
                            createToast('Sukses Unggah Citra', 'top-right', response.message, 'success', 3000);
                        },
                        error: function(xhr, status, error) {
                            createToast('Kesalahan Unggah Citra', 'top-right', xhr.responseJSON.message, 'error', 3000);
                        }
                    });
                }, 'image/png');  
            } else {
                createToast('Kesalahan', 'top-right', 'Tidak ada foto yang dipotong', 'error', 3000);
            }
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