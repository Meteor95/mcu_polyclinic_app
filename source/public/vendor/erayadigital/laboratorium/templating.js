let selectElementPaketMcuTersedia = document.getElementById("paket_mcu_tersedia");
let choicePaketMcuTersedia;
let table_tindakan_lab, table_tindakan_lab_home, isedit;
let id_templat_laboratorium;
$(document).ready(function(){
    load_paket_mcu_tersedia();
    load_template_laboratorium();
    load_table_paket_tindakan_lab();
});
function load_template_laboratorium() {
    $.get('/generate-csrf-token', function(response) {
        $("#table_tindakan_lab").DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            bFilter: false,
            bProcessing: true,
            serverSide: true,
            scrollX: $(window).width() < 768 ? true : false,
            pageLength: $('#data_ditampilkan_tindakan').val(),
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
                "url": baseurlapi + '/laboratorium/daftar_template_laboratorium',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian_tindakan').val();
                    d.length = $('#data_ditampilkan_tindakan').val();
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
                    const infoString = 'Hal ke: ' + currentPage + ' Ditampilkan: ' + ($('#data_ditampilkan_tindakan').val() < 0 ? 'Semua' : $('#data_ditampilkan_tindakan').val()) + ' Baris dari Total : ' + recordsFiltered + ' Data';
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
                    title: "Nama Template",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nama_template;
                        }
                        return data;
                    }
                },
                {
                    title: "Paket MCU",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.used_paket_mcu == 0 ? "Tidak Terhubung Paket MCU" : "Nama Paket MCU : "+row.nama_paket+"<br>Harga MCU : "+row.harga_paket.toLocaleString('id-ID');
                        }
                        return data;
                    }
                },
                {
                    title: "Nama Tindakan",
                    render: function(data, type, row, meta) {
                        if (type === 'display' && row.meta_data_template) {
                            const namaItems = row.meta_data_template.map(template => 
                                `<span class="badge bg-primary me-1">${template.tarif.nama_item}</span>`
                            );
                            return namaItems.join(' ');
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
                                <button onclick="detail_template_laboratorium_table('${row.id_template_tindakan}','${row.nama_template}','${row.used_paket_mcu}','${row.id_paket_mcu}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah
                                </button>
                                <button onclick="hapus_template_laboratorium('${row.id_template_tindakan}','${row.nama_template}')" class="btn btn-danger w-100">
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
function load_table_paket_tindakan_lab() {
    $.get('/generate-csrf-token', function(response) {
        table_tindakan_lab = $("#table_paket_tindakan_lab").DataTable({
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
                "url": baseurlapi + '/laboratorium/daftar_tarif',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian').val();
                    d.length = $('#data_ditampilkan').val();
                    d.jenis_item_tampilkan = $('#jenis_item_tampilkan').val();
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
                    title: "Kode Item",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.kode_item;
                        }
                        return data;
                    }
                },
                {
                    title: "Nama Item",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nama_item;
                        }
                        return data;
                    }
                },
                {
                    title: "Group Item",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return capitalizeFirstLetter(row.group_item);
                        }
                        return data;
                    }
                },
                {
                    title: "Kategori",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nama_kategori;
                        }
                        return data;
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
                    title: "Jenis Item",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            if (row.group_item === 'laboratorium') {
                                return capitalizeFirstLetter(row.jenis_item);
                            } else {
                                return "Tindakan";
                            }
                        }
                        return data;
                    }
                },
                {
                    title: "Harga Dasar",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="text-end">${row.harga_dasar.toLocaleString('id-ID')}</div>`;
                        }
                        return data;
                    }
                },
                {
                    title: "Harga Jual",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="text-end">${row.harga_jual.toLocaleString('id-ID')}</div>`;
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-center",
                    width: "280px",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<input value="${row.kode_item}" type="checkbox" class="form-check-input" style="width: 20px; height: 20px;" name="templat_id[]">`;
                        }       
                        return data;
                    }
                }
            ]
        });
    });
}
$("#table_paket_tindakan_lab").on('draw.dt', function() {
    $("#table_paket_tindakan_lab tbody tr").css("cursor", "pointer");
});
$("#table_paket_tindakan_lab").on("click", "tr", function(event) {
    $(this).toggleClass("clicked-row");
    if ($(event.target).is("input[type='checkbox']")) {
        return;
    }
    let checkbox = $(this).find("input[name='templat_id[]']");
    checkbox.prop("checked", !checkbox.prop("checked"));
});
function load_paket_mcu_tersedia() {
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/masterdata/daftarpaketmcu_non_dt',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
            },
            success: function(response) {
                const hierarchicalData = response.data;
                selectElementPaketMcuTersedia.innerHTML = '';
                const optionroot = document.createElement('option');
                optionroot.value = 'tidak_terhubung_paket_mcu';
                optionroot.textContent = 'Tidak Terhubung Paket MCU';
                selectElementPaketMcuTersedia.appendChild(optionroot);
                hierarchicalData.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = `${category.nama_paket}`;
                    selectElementPaketMcuTersedia.appendChild(option);
                });
                if (choicePaketMcuTersedia) { choicePaketMcuTersedia.destroy(); }
                choicePaketMcuTersedia = new Choices(selectElementPaketMcuTersedia, {
                    searchEnabled: true,
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: 'Silahkan Pilih Paket MCU Tersedia',
                });
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
}
$("#collapse_formulir").on("click", function(event) {
    const form = $("#formulir_buat_templat");
    if (form.hasClass("show")) {
        form.collapse("hide");
        $(this).text("Tampilkan Formulir");
    } else {
        form.collapse("show");
        $(this).text("Sembunyikan Formulir");
    }
})
$("#paket_mcu_tersedia").on("change", function(event) {
    $("#nama_template").val('');
    if (event.target.value !== 'tidak_terhubung_paket_mcu') {
        $("#nama_template").val($(this).find("option:selected").text());
    }
});
$("#kotak_pencarian").on("keyup", debounce(function(){
    $("#table_paket_tindakan_lab").DataTable().ajax.reload();
}, 300));
$("#data_ditampilkan").change(function(){
    $("#table_paket_tindakan_lab").DataTable().page.len($(this).val()).draw();
    $("#table_paket_tindakan_lab").DataTable().ajax.reload();
});
$("#kotak_pencarian_tindakan").on("keyup", debounce(function(){
    $("#table_tindakan_lab").DataTable().ajax.reload();
}, 300));
$("#data_ditampilkan_tindakan").change(function(){
    $("#table_tindakan_lab").DataTable().page.len($(this).val()).draw();
    $("#table_tindakan_lab").DataTable().ajax.reload();
});
$("#jenis_item_tampilkan").change(function(){
    $("#table_paket_tindakan_lab").DataTable().ajax.reload();
});
$("#btn_simpan_templat_laboratorium").on("click", function(){
    if ($("#table_paket_tindakan_lab tbody input[name='templat_id[]']:checked").length === 0) {
        return createToast('Kesalahan Pengambilan Data', 'top-right', "Dalam pembuatan template laboratorium, harap pilih tindakan laboratorium terlebih dahulu minimal 1 tindakan atau jenis pengobatan", 'error', 3000);
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
            let selectedRows = $("#table_paket_tindakan_lab tbody input[name='templat_id[]']:checked").closest('tr');
            let selectedData = [];
            selectedRows.each(function() {
                let rowData = table_tindakan_lab.row(this).data();
                let data_tindakan = {
                    kode_item: rowData.kode_item,
                }
                selectedData.push(data_tindakan);
            });
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laboratorium/simpan_template_laboratorium',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        isedit: isedit,
                        id: id_templat_laboratorium,
                        used_paket_mcu: $("#paket_mcu_tersedia").val() == 'tidak_terhubung_paket_mcu' ? 0 : 1,
                        id_paket_mcu: $("#paket_mcu_tersedia").val() == 'tidak_terhubung_paket_mcu' ? "0" : $("#paket_mcu_tersedia").val(),
                        nama_template: $("#nama_template").val(),
                        meta_data_template: selectedData,
                    },
                    success: function(response) {
                        clear_form();
                        $("#table_tindakan_lab").DataTable().ajax.reload();
                        return createToast('Informasi Template Laboratorium', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });    
});
$("#btn_bersihkan_formulir").on("click", function(){
    clear_form();
});
function clear_form() {
    load_paket_mcu_tersedia();
    $("#nama_template").val('');
    $("#table_paket_tindakan_lab tr").removeClass("clicked-row");
    $("#table_paket_tindakan_lab input[name='templat_id[]']").prop("checked", false);
}
function detail_template_laboratorium_table(id, nama_template, used_paket_mcu, id_paket_mcu) {
    isedit = true;
    id_templat_laboratorium = id;
    const form = $("#formulir_buat_templat");
    form.collapse("show");
    choicePaketMcuTersedia.setChoiceByValue(used_paket_mcu == 0 ? 'tidak_terhubung_paket_mcu' : id_paket_mcu.toString());
    $("#paket_mcu_tersedia").val(used_paket_mcu == 0 ? 'tidak_terhubung_paket_mcu' : id_paket_mcu.toString()).trigger('change');
    $("#nama_template").val(nama_template);
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laboratorium/detail_template_laboratorium',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                id: id,
            },
            success: function(response) {
                $('#table_paket_tindakan_lab').find("input[name='templat_id[]']").prop("checked", false);
                let meta_data_template_decode = JSON.parse(response.data.meta_data_template);
                const allRowsData = table_tindakan_lab.rows().data().toArray();
                const rowDataMap = new Map();
                allRowsData.forEach(rowData => {
                    rowDataMap.set(rowData.kode_item, rowData);
                });
                meta_data_template_decode.forEach(item => {
                    const matchingRow = rowDataMap.get(item.kode_item);
                    if (matchingRow) {
                        $('#table_paket_tindakan_lab')
                        .find(`input[name='templat_id[]'][value='${matchingRow.kode_item}']`)
                        .prop("checked", true);
                    }
                });

            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
}
function hapus_template_laboratorium(id, nama_template) {
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Template Laboratorium</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus template laboratorium dengan atas nama : <strong>' + $("#nama_template").val() + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laboratorium/hapus_template_laboratorium',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        id: id,
                        nama_template: nama_template,
                    },
                    success: function(response) {
                        clear_form();
                        $("#table_tindakan_lab").DataTable().ajax.reload();
                        return createToast('Informasi Template Laboratorium', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    }); 
}