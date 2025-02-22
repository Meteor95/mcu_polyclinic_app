let isedit,id_rentang_kenormalan;
$(document).ready(function(){
    onload_rentang_kenormalan();
});
function onload_rentang_kenormalan(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_rentang_kenormalan").DataTable({
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
                "url": baseurlapi + '/atribut/daftar_rentang_kenormalan',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian').val();
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
                    title: "Nama Rentang Kenormalan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nama_rentang_kenormalan;
                        }
                        return data;
                    }
                },
                {
                    title: "Nilai Rentang",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            if (row.umur != -1){
                                let value_umur = row.umur.split('-');
                                return `Umur ${value_umur[0]} s.d ${value_umur[1]} Tahun`;
                            }else{
                                return `Semua Umur`;
                            }
                        }
                        return data;
                    }
                },
                {
                    title: "Jenis Kelamin",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            if (row.jenis_kelamin == 'L') {
                                return 'Laki-laki';
                            } else if (row.jenis_kelamin == 'P') {
                                return 'Perempuan';
                            } else {
                                return 'Semua Jenis Kelamin';
                            }
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-center",
                    width: "180px",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-between gap-2">
                                <button onclick="detail_informasi_rentang_kenormalan('${row.id}','${row.umur}','${row.nama_rentang_kenormalan}','${row.jenis_kelamin}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah
                                </button>
                                <button onclick="hapus_informasi_rentang_kenormalan('${row.id}','${row.nama_rentang_kenormalan}')" class="btn btn-danger w-100">
                                    <i class="fa fa-trash-o"></i> Hapus
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
$("#btn_refresh_rentang_kenormalan").click(function(){
    clear_rentang_kenormalan();
});
function clear_rentang_kenormalan(){
    isedit = false;
    $('#umur_awal').val('');
    $('#umur_akhir').val('');
    $('#nama_rentang_kenormalan').val('');
    $('#jenis_kelamin').val('LP').trigger('change');
}
$("#btn_simpan_rentang_kenormalan").click(function(){
    if ($("#umur_awal").val() == '' || $("#umur_akhir").val() == ''){
        return createToast('Kesalahan Formulir', 'top-right', "Silahkan isi rentang umur terlebih dahulu jikalau ingin menambahkan nilai rentang.", 'error', 3000);
    }
    if ($("#nama_rentang_kenormalan").val() == ''){
        return createToast('Kesalahan Formulir', 'top-right', "Silahkan isi nama rentang kenormalan terlebih dahulu jikalau ingin menambahkan nilai rentang.", 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Formulir Nilai Rentang Kenormalan</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Nilai Rentang Kenormalan dengan atas nama : <strong>' + $("#nama_rentang_kenormalan").val() + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/atribut/simpan_rentang_kenormalan',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        isedit: isedit,
                        id: id_rentang_kenormalan,
                        nama_rentang_kenormalan: $("#nama_rentang_kenormalan").val(),
                        umur_awal: $("#umur_awal").val(),
                        umur_akhir: $("#umur_akhir").val(),
                        jenis_kelamin: $("#jenis_kelamin").val(),
                    },
                    success: function(response) {
                        clear_rentang_kenormalan();
                        $("#datatables_rentang_kenormalan").DataTable().ajax.reload();
                        createToast('Informasi Nilai Rentang Kenormalan', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
});
function detail_informasi_rentang_kenormalan(id,umur,nama_rentang_kenormalan,jenis_kelamin){
    isedit = true;
    id_rentang_kenormalan = id;
    $('#umur_awal').val((umur == '-1' ? '0' : umur.split('-')[0]));
    $('#umur_akhir').val((umur == '-1' ? '0' : umur.split('-')[1]));
    $('#nama_rentang_kenormalan').val(nama_rentang_kenormalan);
    if (jenis_kelamin == 'L') {
        jenis_kelamin = 'L';
    } else if (jenis_kelamin == 'P') {
        jenis_kelamin = 'P';
    } else {
        jenis_kelamin = 'LP';
    }
    $('#jenis_kelamin').val(jenis_kelamin).trigger('change');

}
function hapus_informasi_rentang_kenormalan(id, nama_rentang_kenormalan){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Informasi Nilai Rentang Kenormalan</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi nilai rentang kenormalan dengan atas nama : <strong>' + nama_rentang_kenormalan + '</strong> ?.</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/atribut/hapus_rentang_kenormalan',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        id: id,
                        nama_rentang_kenormalan: nama_rentang_kenormalan,
                    },
                    success: function(response) {
                        $("#datatables_rentang_kenormalan").DataTable().ajax.reload();
                        createToast('Informasi Nilai Rentang Kenormalan', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
}