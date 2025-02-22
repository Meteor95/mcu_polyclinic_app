let cari_berdasarkan_id,cari_berdasarkan_non_id;
$(document).ready(function() {
    daftar_peserta();
    onload_tabel_antrian();
});
function onload_tabel_antrian(){
    $.get('/generate-csrf-token', function(response) {
        $("#tabel_antrian_data").DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            bFilter: false,
            bProcessing: true,
            serverSide: true,
            scrollX: $(window).width() < 768 ? true : false,
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
                "url": baseurlapi + '/komponen/daftarantrian',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#pencarian_tabel_antrian").val();
                    d.kategori_antrian = $("#kategori_antrian").val();
                    d.status_antrian_sekarang = $("#status_antrian_sekarang").val()
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
                    title: "ID",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.id_antrian_peserta}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Nama Peserta",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.nama_peserta}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Waktu Masuk",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.waktu_masuk ?? "Belum Diset"}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Waktu Selesai",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.waktu_selesai ?? "Belum Diset"}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Status",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<strong>${row.jenis_kategori.toUpperCase().replace(/_/g, " ")}</strong><br>${row.status == 0 ? "Mengantri" : (row.status == 1 ? "Selesai" : "Proses")}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-center gap-2 background_fixed_right_row">
                                <button onclick="status_antrian('${row.id}','${row.nama_peserta}',0,'${row.jenis_kategori}','${row.status}')" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                                <button onclick="status_antrian('${row.id}','${row.nama_peserta}',1,'${row.jenis_kategori}','${row.status}')" class="btn btn-success"><i class="fa fa-check"></i> Selesai</button>
                                <button onclick="status_antrian('${row.id}','${row.nama_peserta}',2,'${row.jenis_kategori}','${row.status}')" class="btn btn-warning"><i class="fa fa-play"></i> Proses</button>
                            </div>`;
                        }       
                        return data;
                    }
                }
            ]
        });
    }); 
}
function daftar_peserta() {
    $.get('/generate-csrf-token', function(response) {
        $("#table_peserta_antrian").DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            bFilter: false,
            bProcessing: true,
            serverSide: true,
            scrollX: $(window).width() < 768 ? true : false,
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
                "url": baseurlapi + '/pendaftaran/daftarpasien',
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
                    title: "ID",
                    data: "id",
                },
                {
                    title: "Nama Peserta",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.nama_peserta} (${row.umur}Th)</span>`;
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-center gap-2 background_fixed_right_row">
                                <button onclick="pilih_pasien_antrian('${row.id}','${row.nama_peserta}')" class="btn btn-success"><i class="fa fa-list"></i> Pilih</button>
                            </div>`;
                        }       
                        return data;
                    }
                }
            ]
        });
    }); 
}
$("#tombol_tantukan_antrian").click(function() {   
    setTimeout(function() {
        $("#table_peserta_antrian").DataTable().ajax.reload();
    }, 1000);
    $("#modal_pilih_pasien_antrian_text").text("Poliklinik "+$("#kategori_antrian option:selected").text()); 
    $("#modal_pilih_pasien_antrian").modal('show');
});
$("#kategori_antrian ,#status_antrian_sekarang").on('change', function() {
    $("#tabel_antrian_data").DataTable().ajax.reload();
})
$("#segarkan_tantukan_antrian, #cari_antrian_tersedia").on('click', function() {
    $("#tabel_antrian_data").DataTable().ajax.reload();
})
function pilih_pasien_antrian(id, nama_peserta) {
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Tambah Antrian</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin menambah antrian atas nama : <strong>' + nama_peserta + '</strong> ? Peserta ini tidak bisa dimasukkan ke antrian kategori lain</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Oke. Tambahkan',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/komponen/daftarantrian',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        nama_peserta: nama_peserta,
                        id_pendaftaran: id,
                        jenis_kategori: $("#kategori_antrian").val(),
                        status:0,
                        keterangan:'',
                    },
                    success: function(response) {
                        if (response.success){
                            $("#modal_pilih_pasien_antrian").modal('hide');
                            $("#tabel_antrian_data").DataTable().ajax.reload();
                            return createToast('Berhasil Menambahkan Antrian', 'top-right', response.message, 'success', 3000);
                        }
                        return createToast('Kesalahan Penambahan Antrian', 'top-right', response.message, 'error', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
                    },
                });
            })
        }
    })
}
function status_antrian(id, nama_peserta, status, jenis_kategori_sekarang, status_saat_ini){
    let status_text = "", tulisan_btn_ok = "";
    switch(status){
        case 0:
            status_text = 'Menghapus';
            tulisan_btn_ok = "Hapus Antrian";
            break;
        case 1:
            status_text = 'Selesai Tindakan';
            tulisan_btn_ok = "Tindakan Selesai";
            break;
        case 2:
            status_text = 'Proses Tindakan';
            tulisan_btn_ok = "Tindakan Diproses";
            break;
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Ubah Status Antrain</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin merubah status antraian menjadi '+status_text+' atas nama : <strong>' + nama_peserta + '</strong>?</p><br><input type="text" class="form-control" placeholder="Berikan keterangan untuk perubahan status ini,jika ada" id="keterangan_antrian"></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: tulisan_btn_ok,
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/komponen/statusantrian',
                    type: 'GET',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        nama_peserta: nama_peserta,
                        id_pendaftaran: id,
                        jenis_kategori: $("#kategori_antrian").val(),
                        status:status,
                        jenis_kategori_sekarang:jenis_kategori_sekarang,
                        status_saat_ini:status_saat_ini,
                        keterangan:$("#keterangan_antrian").val(),
                    },
                    success: function(response) {
                        if (response.success){
                            $("#tabel_antrian_data").DataTable().ajax.reload();
                            return createToast('Perubahan Status Antrian', 'top-right', response.message, 'success', 3000);
                        }
                        return createToast('Kesalahan Penambahan Antrian', 'top-right', response.message, 'error', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
                    },
                });
            })
        }
    })
}