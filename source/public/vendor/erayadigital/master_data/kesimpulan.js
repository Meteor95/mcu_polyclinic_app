let formValidasi = $("#formulir_tambah_bank_baru");let isedit = false; let idkesimpulan = "";
$(document).ready(function(){
   daftarkesimpulan();
});
function daftarkesimpulan(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_kesimpulan").DataTable({
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
                "url": baseurlapi + '/masterdata/daftarkesimpulan',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_kesimpulan").val();
                    d.jenis_kesimpulan = $("#jenis_pemeriksaan_pencarian").val();
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
                    const infoString = 'Hal Ke: ' + currentPage + ' Ditampilkan: ' + 1000 + ' Dari Total : ' + recordsFiltered + ' Data';
                    return infoString;
                }
            },
            columnDefs: [
                {
                    defaultContent: "-",
                    targets: "_all",
                }
            ],
            rowGroup: {
                dataSrc:function(row) {
                    return row.jenis_kesimpulan;
                },
                startRender: function (rows, group) {
                    let rowJudul = $('<tr class="group-label">')
                        .append(`<td colspan="3" style="text-align:center;background-color: #f2f2f2; font-weight: bold;">${rows.data()[0].jenis_kesimpulan.replace(/_/g, ' ').toUpperCase()}</td>`);
                    let rowHeader = $('<tr class="group-header" style="background-color: #fafafa; font-weight: bold;">')
                        .append('<td style="text-align;center;width:10%">No</td>')
                        .append('<td style="text-align:center;width:70%">Keterangan Kesimpulan</td>')
                        .append('<td style="text-align:center;width:20%">Aksi</td>')
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
                            return `${row.keterangan_kesimpulan}`;
                        }
                        return data;
                    }
                },
                {
                    title: "",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<div class=\"d-flex justify-content-between gap-2 background_fixed_right_row\"><button class=\"btn btn-primary w-100\" onclick=\"editdaftarkesimpulan('"+row.id+"','"+row.jenis_kesimpulan+"','"+row.keterangan_kesimpulan+"')\"><i class=\"fa fa-edit\"></i> Ubah</button><button class=\"btn btn-danger w-100\" onclick=\"hapusdaftarkesimpulan('"+row.id+"','"+row.jenis_kesimpulan+"','"+row.keterangan_kesimpulan+"')\"><i class=\"fa fa-trash-o\"></i> Hapus</button></div>";
                        }       
                        return data;
                    }
                }
            ]
        });
    }); 
}
function clearformeditdaftarkesimpulan(){
    isedit = false;
    idkesimpulan = "";
    $("#kesimpulantindakan").val("");
    $("#jenis_pemeriksaan").val("");
}
$("#tambah_kesimpulan_baru").on("click", function(){
    isedit = false;
    clearformeditdaftarkesimpulan();
    $("#formulir_tambah_kesimpulan").modal("show");
});
function editdaftarkesimpulan(id,jenis_pemeriksaan,kesimpulantindakan){
    isedit = true;
    idkesimpulan = id;
    $("#jenis_pemeriksaan").val(jenis_pemeriksaan);
    $("#kesimpulantindakan").val(kesimpulantindakan);
    $("#formulir_tambah_kesimpulan").modal("show");
}
$("#jenis_pemeriksaan_pencarian").on('change', function() {
    $("#datatables_kesimpulan").DataTable().ajax.reload();
});
$("#kotak_pencarian_kesimpulan").on("keyup", debounce(function(){
    $("#datatables_kesimpulan").DataTable().ajax.reload();
}, 300));
$("#simpan_kesimpulan").on("click", function(event){
    event.preventDefault();
    formValidasi.addClass("was-validated");
    if($("#kesimpulantindakan").val() == "" || $("#jenis_pemeriksaan").val() == "") return createToast('Kesalahan Formulir', 'top-right', 'Silahkan isi semua formulir terlebih dahulu sebelum anda menyimpan informasi tindakan', 'error', 3000);
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penyimpanan Kesimpulan Atas Tindakan</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin meyimpan piliha untuk kesimpulan tindakan pada kategori <strong>'+$("#jenis_pemeriksaan").val().replace(/_/g, ' ').toUpperCase()+'</strong></p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/masterdata/'+(isedit ? 'ubahkesimpulan' : 'simpankesimpulan'),
                    type: 'POST',
                    beforeSend: function(xhr){
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: {
                        _token: response.csrf_token,
                        idkesimpulan: idkesimpulan,
                        jenis_pemeriksaan: $("#jenis_pemeriksaan").val(),
                        kesimpulantindakan: $("#kesimpulantindakan").val(),
                    },
                    success: function(response){
                        if(response.success){
                            createToast('Berhasil', 'top-right', response.message, 'success', 3000);
                            clearformeditdaftarkesimpulan();
                            $("#formulir_tambah_kesimpulan").modal("hide");
                            $("#datatables_kesimpulan").DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr, status, error){
                        createToast('Kesalahan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            });
        }
    });
});
function hapusdaftarkesimpulan(id,jenis_kesimpulan,keterangan_kesimpulan){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://cdn.lordicon.com/gsqxdxog.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penghapusan Data Kesimpulan</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi kesimpulan pada jenis <strong>'+jenis_kesimpulan.replace(/_/g, ' ').toUpperCase()+'</strong> dengan keterangan <strong>'+keterangan_kesimpulan+'</strong> ?. Jika sudah silahkan klik tombol hapus data</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/masterdata/hapuskesimpulan',
                    type: 'GET',
                    beforeSend: function(xhr){
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: {
                        _token: response.csrf_token,
                        idkesimpulan: id,
                        jenis_kesimpulan: jenis_kesimpulan,
                        keterangan_kesimpulan: keterangan_kesimpulan,
                    },
                    success: function(response){
                        if(response.success){
                            createToast('Berhasil', 'top-right', response.message, 'success', 3000);
                            clearformeditdaftarkesimpulan()
                            $("#datatables_kesimpulan").DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr, status, error){
                        createToast('Kesalahan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            });
        }
    });
}