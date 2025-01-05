let selectElementKategori = document.getElementById('parent_kategori');
let choiceGrupItem, choiceKategori;
let isedit = false;
$(document).ready(function(){
    choiceGrupItem = new Choices('#grup_item',{
        placeholder: true,
        placeholderValue: 'Pilih Grup Item',
    });
    load_kategori_dinamis();
    load_kategori_item();
});
function load_kategori_item(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_kategori").DataTable({
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
                "url": baseurlapi + '/atribut/daftar_kategori',
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
                    title: "Nama Kategori",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nama_kategori;
                        }
                        return data;
                    }
                },
                {
                    title: "Group Kategori",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return capitalizeFirstLetter(row.grup_kategori);
                        }
                        return data;
                    }
                },
                {
                    title: "Parent Kategori",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.parent_kategori;
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
                                <button onclick="detail_informasi_kategori_tabel('${row.id}','${row.nama_kategori}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah
                                </button>
                                <button onclick="hapus_informasi_kategori('${row.id}','${row.nama_kategori}')" class="btn btn-danger w-100">
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
$("#grup_item").change(function(){
    load_kategori_dinamis();
});
function load_kategori_dinamis() {
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/atribut/kategori',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                grup_kategori: $("#grup_item").val(),
            },
            success: function(response) {
                const hierarchicalData = buildHierarchy(response.data);
                selectElementKategori.innerHTML = '';
                const optionroot = document.createElement('option');
                optionroot.value = 'root';
                optionroot.textContent = 'Jadikan Root Kategori';
                selectElementKategori.appendChild(optionroot);
                addCategoryOptions(hierarchicalData);
                if (choiceKategori) { choiceKategori.destroy(); }
                choiceKategori = new Choices(selectElementKategori, {
                    searchEnabled: true,
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: 'Silahkan Pilih Kategori',
                });
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
}
function clear_form_kategori() {
    isedit = false;
    $("#nama_kategori").val('');
    choiceGrupItem.setChoiceByValue("laboratorium");
    load_kategori_dinamis();
}
$("#btn_refresh_kategori").click(function(){
    clear_form_kategori();
    $("#datatables_kategori").DataTable().ajax.reload();
});
$("#kotak_pencarian").on("keyup", debounce(function(){
    $("#datatables_kategori").DataTable().ajax.reload();
}, 300));
$("#data_ditampilkan").change(function(){
    $("#datatables_kategori").DataTable().page.len($(this).val()).draw();
    $("#datatables_kategori").DataTable().ajax.reload();
});
$("#btn_simpan_kategori").click(function(){
    if ($("#nama_kategori").val() == '' || $("#grup_item").val() == '') {
        return createToast('Kesalahan Formulir', 'top-right', "Silahkan isi nama kategori dan grup kategori terlebih dahulu jikalau ingin menambahkan kategori baru.", 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Formulir Kategori Laboratorium</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Kategori Laboratorium dengan atas nama : <strong>' + $("#nama_kategori").val() + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/atribut/simpan_kategori',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        isedit: isedit,
                        nama_kategori: $("#nama_kategori").val(),
                        parent_kategori: $("#parent_kategori").val(),
                        grup_kategori: $("#grup_item").val(),
                    },
                    success: function(response) {
                        clear_form_kategori();
                        $("#datatables_kategori").DataTable().ajax.reload();
                        createToast('Informasi Kategori Laboratorium', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
});
function detail_informasi_kategori_tabel(id_kategori, nama_kategori) {
    isedit = true;
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/atribut/detail_kategori',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                id: id_kategori,
                nama_kategori: nama_kategori,
            },
            success: function(response) {
                choiceGrupItem.setChoiceByValue(response.data.grup_kategori);
                if (!response.data.parent_id) {
                    choiceKategori.setChoiceByValue('root');
                    $("#parent_kategori").val('root');
                }else{
                    choiceKategori.setChoiceByValue(response.data.parent_id.toString());
                    $("#parent_kategori").val(response.data.parent_id.toString());
                }
                $("#nama_kategori").val(response.data.nama_kategori);
                $("#grup_item").val(response.data.grup_kategori);
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
}
function hapus_informasi_kategori(id_kategori, nama_kategori) {
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Informasi Kategori Laboratorium</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi kategori laboratorium dengan atas nama : <strong>' + nama_kategori + '</strong> ?. Penghapusan akan ditolak oleh sistem jikalau kategori tersebut dipakai oleh item laboratorium.</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/atribut/hapus_kategori',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        id: id_kategori,
                        nama_kategori: nama_kategori,
                    },
                    success: function(response) {
                        if (!response.success) {
                            return createToast('Kesalahan Pengambilan Data', 'top-right', response.message, 'error', 3000);
                        }
                        clear_form_kategori();
                        $("#datatables_kategori").DataTable().ajax.reload();
                        createToast('Informasi Kategori Laboratorium', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
}