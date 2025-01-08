let formValidasi = $("#formulir_tambah_jasa_pelayanan");let isedit = false;let id_jasa_pelayanan = "";
$(document).ready(function(){
    listJasaPelayanan();
});
const hargajasapelayanan = new AutoNumeric('#nominaljasa', {
    digitGroupSeparator: '.',
    decimalCharacter: ',',
    decimalPlaces: 2,
});
function listJasaPelayanan(){
    $.get('/generate-csrf-token', function(response){
        $("#datatable_jasapelayanan").DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            bFilter: false,
            bProcessing: true,
            serverSide: true,
            scrollX: $(window).width() < 768 ? true : false,
            pageLength: $('#data_ditampilkan').val(),
            pagingType: "full_numbers",
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            ajax: {
                "url": baseurlapi + '/masterdata/daftarjasa',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian').val();
                    d.kategori_layanan = $('#kategori_layanan').val();
                    d.length = $('#data_ditampilkan').val();
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
                    const infoString = 'Hal ke: ' + currentPage + ' Ditampilkan: ' + ($('#data_ditampilkan').val() < 0 ? 'Semua' : $('#data_ditampilkan').val()) + ' Baris dari Total : ' + recordsFiltered + ' Data';
                    return infoString;
                }
            },
            columns: [
                {
                    title: "No",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: "Kode Jasa Pelayanan",
                    data: 'kode_jasa_pelayanan'
                },
                {
                    title: "Nama Jasa Pelayanan",
                    data: 'nama_jasa_pelayanan'
                },
                {
                    title: "Kategori Jasa Pelayanan",
                    render: function(data, type, row, meta) {
                        return capitalizeFirstLetter(row.kategori_layanan);
                    }
                },
                {
                    title: "Nominal Jasa Pelayanan",
                    data: "nominal_layanan",
                    render: function(data) {
                        return '<div style="text-align: right;">' + new Intl.NumberFormat('id-ID', { 
                            style: 'currency', 
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(data) + '</div>';
                    }
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<div class=\"d-flex justify-content-between gap-2\"><button class=\"btn btn-primary w-100\" onclick=\"editjasa('" + row.id + "','" + row.kode_jasa_pelayanan + "','" + row.nama_jasa_pelayanan + "','" + row.nominal_layanan + "','" + row.kategori_layanan + "')\"><i class=\"fa fa-edit\"></i> Edit Jasa Pelayanan</button><button class=\"btn btn-danger w-100\" onclick=\"hapusjasa('" + row.id + "','" + row.kode_jasa_pelayanan + "','" + row.nama_jasa_pelayanan + "')\"><i class=\"fa fa-trash-o\"></i> Hapus Jasa Pelayanan</button></div>";
                        }
                        return data;
                    }
                }
            ],
        });
    });
}
$("#data_ditampilkan, #kategori_layanan").change(function() {
    $("#datatable_jasapelayanan").DataTable().ajax.reload();
});
$("#kotak_pencarian").keyup( debounce(function() {
    $("#datatable_jasapelayanan").DataTable().ajax.reload();
}, 300) );
$("#tambah_jasa_pelayanan_baru").click(function() {
    isedit = false;
    $("#formulir_tambah_jasa_pelayanan").modal("show");
});
$("#simpan_jasa_pelayanan").click(function(event) {
    event.preventDefault();
    formValidasi.addClass('was-validated');
    if ($("#kodejasa").val() == "" || $("#namajasa").val() == "" || $("#nominaljasa").val() == "") {
        return createToast('Kesalahan Formulir', 'top-right', 'Silahkan isi semua formulir terlebih dahulu sebelum anda menyimpan data jasa pelayanan secara teliti.', 'error', 3000);
    }
    if (hargajasapelayanan.get() == 0) {
        return createToast('Kesalahan Formulir', 'top-right', 'Harga jasa pelayanan tidak boleh 0.', 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penyimpanan Data Jasa Pelayanan</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi jasa pelayanan <strong>'+$("#namajasa").val()+'</strong> ?. Jika sudah silahkan tentukan jasa pelayanan yang akan digunakan untuk fee dari masing-masing pelaku',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/masterdata/' + (isedit ? 'ubahjasa' : 'simpanjasa'),
                    type: 'POST',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: {
                        _token: response.csrf_token,
                        id: id_jasa_pelayanan,
                        kode_jasa_pelayanan: $("#kodejasa").val(),
                        nama_jasa_pelayanan: $("#namajasa").val(),
                        nominal_layanan: hargajasapelayanan.get(),
                        kategori_layanan: $("#jenisjasa").val(),
                    },
                    success: function(response) {
                        clearFormulirTambahJasaPelayanan();
                        $("#datatable_jasapelayanan").DataTable().ajax.reload();
                        $("#formulir_tambah_jasa_pelayanan").modal("hide");
                        return createToast('Berhasil', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr) {
                        return createToast('Gagal', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            });
        }
    });
});
function clearFormulirTambahJasaPelayanan(){
    isedit = false;
    id_jasa_pelayanan = "";
    formValidasi.removeClass('was-validated');
    hargajasapelayanan.set(0);
    $("#kodejasa").val("");
    $("#namajasa").val("");
    $("#nominaljasa").val("");
    $("#jenisjasa").val("").trigger("change");
}
function hapusjasa(id, kode, nama,){
    if (id == "") { return createToast('Kesalahan', 'top-right', 'Silahkan id data jasa pelayanan yang akan dihapus terlebih dahulu.', 'error', 3000); }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penghapusan Data Jasa Pelayanan <strong>'+nama+'</strong></h4><p class="text-muted mx-4 mb-0">Informasi yang terkait dengan jasa pelayanan <strong>'+nama+'</strong> dengan kode <strong>'+kode+'</strong> tidak akan hilang tetapi tidak ditampilkan di sistem. Apakah anda yakin ingin melanjutkan proses penghapusan ini ?</p></div>',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/masterdata/hapusjasa',
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: { _token: response.csrf_token, id: id },
                    success: function(response) {
                        clearFormulirTambahJasaPelayanan();
                        $("#datatable_jasapelayanan").DataTable().ajax.reload();
                        return createToast('Berhasil', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr) {
                        return createToast('Gagal', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            });
        }
    });
}
function editjasa(id, kode, nama, nominal, kategori){
    isedit = true;
    id_jasa_pelayanan = id;
    hargajasapelayanan.set(nominal);
    $("#kodejasa").val(kode);
    $("#namajasa").val(nama);
    $("#jenisjasa").val(kategori).trigger("change");
    $("#formulir_tambah_jasa_pelayanan").modal("show");
}