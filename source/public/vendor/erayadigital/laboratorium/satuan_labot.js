let choiceGrupItem;
let isedit = false;
let id_satuan = null;
$(document).ready(function() {
    choiceGrupItem = new Choices('#grup_item',{
        placeholder: true,
        placeholderValue: 'Pilih Grup Item',
    });
    load_satuan_laboratorium();
});
function load_satuan_laboratorium(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_satuan").DataTable({
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
                "url": baseurlapi + '/atribut/daftar_satuan',
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
                    title: "Nama Satuan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nama_satuan;
                        }
                        return data;
                    }
                },
                {
                    title: "Group Satuan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return capitalizeFirstLetter(row.grup_satuan);
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
                                <button onclick="detail_satuan_laboratorium('${row.id}','${row.nama_satuan}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah
                                </button>
                                <button onclick="hapus_satuan_laboratorium('${row.id}','${row.nama_satuan}')" class="btn btn-danger w-100">
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
$("#kotak_pencarian").on("keyup", debounce(function(){
    $("#datatables_satuan").DataTable().ajax.reload();
}, 300));
$("#data_ditampilkan").change(function(){
    $("#datatables_satuan").DataTable().page.len($(this).val()).draw();
    $("#datatables_satuan").DataTable().ajax.reload();
});
$("#btn_simpan_satuan").click(function(){
    if ($("#nama_satuan").val() == '' || $("#grup_item").val() == '') {
        return createToast('Kesalahan Formulir', 'top-right', "Silahkan isi nama satuan dan grup satuan terlebih dahulu jikalau ingin menambahkan satuan baru.", 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Formulir Satuan Laboratorium</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Satuan Laboratorium dengan atas nama : <strong>' + $("#nama_satuan").val() + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/atribut/simpan_satuan',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        isedit: isedit,
                        id: id_satuan,
                        nama_satuan: $("#nama_satuan").val(),
                        grup_satuan: $("#grup_item").val(),
                    },
                    success: function(response) {
                        clear_form();
                        $("#datatables_satuan").DataTable().ajax.reload();
                        createToast('Informasi Satuan Laboratorium', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
});
function detail_satuan_laboratorium(id, nama_satuan){
    id_satuan = id;
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/atribut/detail_satuan',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                id: id,
            },
            success: function(response) {
                isedit = true;
                $("#nama_satuan").val(response.data.nama_satuan);
                choiceGrupItem.setChoiceByValue(response.data.grup_satuan);
                $("#grup_item").val(response.data.grup_satuan);
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
}
function hapus_satuan_laboratorium(id, nama_satuan){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Satuan Laboratorium</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus satuan laboratorium dengan atas nama : <strong>' + nama_satuan + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/atribut/hapus_satuan',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        id: id,
                        nama_satuan: nama_satuan,
                    },
                    success: function(response) {
                        if (!response.success){
                            return createToast('Kesalahan Pengambilan Data', 'top-right', response.message, 'error', 3000);
                        }
                        $("#datatables_satuan").DataTable().ajax.reload();
                        createToast('Informasi Satuan Laboratorium', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
}
$("#btn_refresh_satuan").click(function(){
    clear_form();
});
function clear_form(){
    choiceGrupItem.setChoiceByValue("laboratorium");
    $("#nama_satuan").val('');
}