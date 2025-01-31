let isedit;
let grup_item_tarif, kategori_tarif,rentang_kenormalan;
let table_tarif_laboratorium, table_rentang_nilai_kenormalan_kualitatif, table_rentang_nilai_kenormalan_kuantitatif, table_jasa_laboratorium_tarif_kuantitatif, table_jasa_laboratorium_tarif_kualitatif, table_jasa_laboratorium_tarif_modal;
let harga_jual_tarif_laboratorium = new AutoNumeric('#harga_jual_tarif_laboratorium', {
    digitGroupSeparator: '.',
    decimalCharacter: ',',
    decimalPlaces: 0,
    allowDecimalPadding: false,
    minimumValue: '0',
    modifyValueOnUpDownArrow: false,
    modifyValueOnWheel: false
});
let harga_dasar_tarif_laboratorium = new AutoNumeric('#harga_dasar_tarif_laboratorium', {
    digitGroupSeparator: '.',
    decimalCharacter: ',',
    decimalPlaces: 0,
    allowDecimalPadding: false,
    minimumValue: '0',
    modifyValueOnUpDownArrow: false,
    modifyValueOnWheel: false
});
let autoNumericFields = [];
const selectElementKategori = document.getElementById('kategori');
const selectElementSatuan = document.getElementById('satuan');
const selectElementRentangKenormalanKualitatif = document.getElementById('rentang_kenormalan_kualitatif');
const selectElementRentangKenormalanKuantitatif = document.getElementById('rentang_kenormalan_kuantitatif');
let choiceGrupItem, choiceKategori, choiceSatuan, choiceRentangKenormalanKualitatif, choiceRentangKenormalanKuantitatif, choiceVisibleItem;
$(document).ready(function(){
    choiceGrupItem = new Choices('#grup_item',{
        placeholder: true,
        placeholderValue: 'Pilih Grup Item',
    });
    choiceVisibleItem = new Choices('#visible_item',{
        placeholder: true,
        placeholderValue: 'Tentukan Status Penampilkan Item',
    });
    choiceRentangKenormalanKualitatif = new Choices(selectElementRentangKenormalanKualitatif, {
        searchEnabled: true,
        shouldSort: false,
        placeholder: true,
        placeholderValue: 'Silahkan Pilih Rentang Kenormalan Kualitatif',
    });
    choiceRentangKenormalanKuantitatif = new Choices(selectElementRentangKenormalanKuantitatif, {
        searchEnabled: true,
        shouldSort: false,
        placeholder: true,
        placeholderValue: 'Silahkan Pilih Rentang Kenormalan Kuantitatif',
    });
    table_jasa_laboratorium_tarif_modal = $("#table_jasa_laboratorium_tarif_modal").DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false,
        "lengthChange": false,
    });
    table_jasa_laboratorium_tarif_kuantitatif = $("#table_jasa_laboratorium_tarif_kuantitatif").DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false,
        "lengthChange": false,
    });
    table_jasa_laboratorium_tarif_kualitatif = $("#table_jasa_laboratorium_tarif_kualitatif").DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false,
        "lengthChange": false,
    });
    table_rentang_nilai_kenormalan_kualitatif = $("#tabel_rentang_nilai_kenormalan_kualitatif").DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false,
        "lengthChange": false,
        "columnDefs": [
            { "visible": false, "targets": [1, 2] }
        ]
    });
    table_rentang_nilai_kenormalan_kuantitatif = $("#tabel_rentang_nilai_kenormalan_kuantitatif").DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false,
        "lengthChange": false,
        "columnDefs": [
            { "visible": false, "targets": [1, 2] }
        ]
    });
    onload_table_tindakan_lab();
    load_satuan_dinamis();
    load_kategori_dinamis();
    load_table_tarif_laboratorium();
});
function load_table_tarif_laboratorium() {
    let tables = [];
    $.get('/generate-csrf-token', function(response) {
        table_tarif_laboratorium = $("#table_tarif_laboratorium").DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            bFilter: false,
            bProcessing: true,
            info: false,
            paging: false,
            serverSide: true,
            scrollX: $(window).width() < 768 ? true : false,
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            keys: true,
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            ajax: {
                "url": baseurlapi + '/masterdata/daftarjasa_laboratorium',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.grup_item = $('#grup_item').val();
                },
                "dataSrc": function(json) {
                    return json.data.map(item => ({
                        ...item,
                        recordsFiltered: json.recordsFiltered,
                    }));
                },
            },
            infoCallback: function(settings) {
                if (typeof settings.json !== "undefined") {
                    const currentPage = Math.floor(settings._iDisplayStart / settings._iDisplayLength) + 1;
                    const recordsFiltered = settings.json.recordsFiltered;
                    return `Hal ke: ${currentPage} Ditampilkan: ${$('#data_ditampilkan').val() < 0 ? 'Semua' : $('#data_ditampilkan').val()} Baris dari Total: ${recordsFiltered} Data`;
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
                    title: "Jasa Laboratorium",
                    data: "nama_jasa_pelayanan"
                },
                {
                    title: "Harga Rekomendasi",
                    render: function(data, type, row) {
                        return `<div class="text-end">${row.nominal_layanan.toLocaleString('id-ID')}</div>`;
                    }
                },
                {
                    title: "Tentukan Nominal",
                    render: function(data, type, row) {
                        return `<input class="form-control nominal_pembayaran" type="text" name="tarif_laboratorium[]" placeholder="Tentukan harga jasa laboratorium" value="${row.nominal_layanan}">`;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-center",
                    width: "280px",
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex justify-content-between gap-2">
                                    <button onclick="hapusBarisTarif('${meta.row}','${row.nama_jasa_pelayanan}')" class="btn btn-danger w-100">
                                        <i class="fa fa-trash-o"></i> Hapus
                                    </button>
                                </div>`;
                    }
                }
            ],
            drawCallback: function() {
                autoNumericFields = [];
                $(".nominal_pembayaran").each(function() {
                    let autoNumericInstance = new AutoNumeric(this, {
                        decimal: '.',
                        digit: ',',
                        allowDecimalPadding: false,
                        minimumValue: '0',
                        modifyValueOnUpDownArrow: false,
                        modifyValueOnWheel: false
                    });
                    autoNumericFields.push(autoNumericInstance);
                });
            }
        }).on('key-focus', function ( e, datatable, cell, originalEvent ) {
            $('input', cell.node()).focus();
        }).on("focus", "td input", function(){
            $(this).select();
        }) 
        tables.push(table_tarif_laboratorium);
        table_tarif_laboratorium.on('key', function(e, dt, code) {
            if (code === 13) {
                table_tarif_laboratorium.keys.move('down');
            }
        });
    });
}
function hapusBarisTarif(rowIndex, nama_jasa_pelayanan) {
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Hapus Perkaitan Jasa Ini</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus perkaitan jasa laboratorium Nama : <strong>' + nama_jasa_pelayanan + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $("#table_tarif_laboratorium tbody tr:eq(" + rowIndex + ")").remove();
        }
    });
}

function load_satuan_dinamis() {
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/atribut/satuan',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                grup_satuan: $("#grup_item").val(),
            },
            success: function(response) {
                const hierarchicalData = response.data;
                selectElementSatuan.innerHTML = '';
                hierarchicalData.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = `${category.nama_satuan}`;
                    selectElementSatuan.appendChild(option);
                });
                if (choiceSatuan) { choiceSatuan.destroy(); }
                choiceSatuan = new Choices(selectElementSatuan, {
                    searchEnabled: true,
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: 'Silahkan Pilih Satuan',
                });
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
}
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
$("#grup_item").change(function(){
    load_kategori_dinamis();
    load_satuan_dinamis();
    $("#table_tarif_laboratorium").DataTable().ajax.reload();
    if ($("#grup_item").val() === 'laboratorium') {
        $("#section_nilai_kenormalan").show();
    } else {
        $("#section_nilai_kenormalan").hide();
    }
});
function onload_table_tindakan_lab() {
    $.get('/generate-csrf-token', function(response) {
        $("#table_tindakan_lab").DataTable({
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
                            return `<div class="d-flex justify-content-between gap-2">
                                <button onclick="detail_informasi_tarif('${row.kode_item}','${row.nama_item}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> Lihat
                                </button>
                                <button onclick="detail_informasi_tarif_tabel('${row.kode_item}','${row.group_item}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah
                                </button>
                                <button onclick="hapus_informasi_tarif('${row.kode_item}','${row.nama_item}')" class="btn btn-danger w-100">
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
$("#btn_generate_kode_item").click(function(e){
    e.preventDefault();
    $("#kode_item").val(moment().format('YYYYMMDDHHmmss'));
});
$("#jenis_item_tampilkan").change(function(){
    $("#table_tindakan_lab").DataTable().ajax.reload();
});
$("#btn_tambah_tarif_kuantitatif").click(function(e){
    e.preventDefault();
    const selectedValue = choiceRentangKenormalanKuantitatif.getValue();
    const [nilai_kenormalan_id, nilai_kenormalan_umur] = selectedValue.value.split(" | ");
    table_rentang_nilai_kenormalan_kuantitatif.row.add([
        table_rentang_nilai_kenormalan_kuantitatif.data().length + 1,
        nilai_kenormalan_id,
        nilai_kenormalan_umur,
        selectedValue.label.trim().replace(/\s+/g, ' '),
        $("#batas_atas").val(),
        $("#antara").val(),
        $("#batas_bawah").val(),
        '<button onclick="hapus_tarif_tabel(this, \'kuantitatif\')" class="btn btn-danger btn-sm w-100"><i class="fa fa-trash"></i> Hapus</button>'
    ]);
    table_rentang_nilai_kenormalan_kuantitatif.draw();
});
$("#btn_tambah_tarif_kualitatif").click(function(e){
    e.preventDefault();
    const selectedValue = choiceRentangKenormalanKualitatif.getValue();
    const [nilai_kenormalan_id, nilai_kenormalan_umur] = selectedValue.value.split(" | ");
    table_rentang_nilai_kenormalan_kualitatif.row.add([
        table_rentang_nilai_kenormalan_kualitatif.data().length + 1,
        nilai_kenormalan_id,
        nilai_kenormalan_umur,
        selectedValue.label.trim().replace(/\s+/g, ' '),
        ($("#keterangan_kualitatif_positif").val() != '' ? "✔️" : '❌'),
        $("#keterangan_kualitatif_positif").val(),
        ($("#keterangan_kualitatif_negatif").val() != '' ? "✔️" : '❌'),
        $("#keterangan_kualitatif_negatif").val(),
        '<button onclick="hapus_tarif_tabel(this, \'kualitatif\')" class="btn btn-danger btn-sm w-100"><i class="fa fa-trash"></i> Hapus</button>'
    ]);
    table_rentang_nilai_kenormalan_kualitatif.draw();
});
$("#collapse_formulir").on("click", function(event) {
    const form = $("#formulir_tambah_tarif");
    if (form.hasClass("show")) {
        form.collapse("hide");
        $(this).text("Tampilkan Formulir");
    } else {
        form.collapse("show");
        $(this).text("Sembunyikan Formulir");
    }
})
function calculateTotal() {
    let hargaDasar = harga_dasar_tarif_laboratorium.get();
    hargaDasar = parseFloat(hargaDasar);
    let totalJasa = 0;
    autoNumericFields.forEach(function (autoNumericInstance) {
        let value = autoNumericInstance.get() || 0;
        totalJasa += parseFloat(value);
    });
    let hargaJual = hargaDasar + totalJasa;
    harga_jual_tarif_laboratorium.set(hargaJual);
}
$("#harga_dasar_tarif_laboratorium").on("change keyup input", function(){
    calculateTotal();
});
$(document).on("change keyup input", "input[name='tarif_laboratorium[]']", function() {
    calculateTotal();
});
$("#btn_simpan_tarif_laboratorium").click(function(e){
    e.preventDefault();
    if ($("#kode_item").val() == '' || $("#nama_item").val() == '') {
        return createToast('Kesalahan Formulir', 'top-right', "Informasi KODE ITEM dan NAMA ITEM tidak boleh kosong, Silahkan isi terlebih dahulu karena dibutuhkan untuk proses simpan data", 'error', 3000);
    }
    if ($("#grup_item").val() == '' || $("#kategori").val() == '' || $("#satuan").val() == '') {
        return createToast('Kesalahan Formulir', 'top-right', "Informasi GRUP ITEM, KATEGORI dan SATUAN tidak boleh kosong, Silahkan isi terlebih dahulu karena dibutuhkan untuk proses simpan data", 'error', 3000);
    }
    if ($("#harga_dasar_tarif_laboratorium").val() == '' || $("#harga_jual_tarif_laboratorium").val() == '') {
        return createToast('Kesalahan Formulir', 'top-right', "Informasi HARGA DASAR dan HARGA JUAL tidak boleh kosong. Jika ingin memberikan GRATIS untuk tindakan "+$("#nama_item").val()+" dengan kode item : "+$("#kode_item").val()+" , Silahkan isi dengan angka 0 (nol)", 'error', 3000);
    }
    if (((table_rentang_nilai_kenormalan_kualitatif.data().length == 0 && table_rentang_nilai_kenormalan_kuantitatif.data().length == 0) && $("#grup_item").val() === 'laboratorium') || ((table_rentang_nilai_kenormalan_kualitatif.data().length > 0 && table_rentang_nilai_kenormalan_kuantitatif.data().length > 0) && $("#grup_item").val() === 'laboratorium')) {
        return createToast('Kesalahan Formulir', 'top-right', "Informasi TABEL KONORMALAN KUALITATIF dan TABEL KONORMALAN KUANTITATIF tidak boleh kosong dan harus diisi salah satunya, Silahkan isi terlebih dahulu karena dibutuhkan untuk proses simpan data", 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Formulir Tarif Laboratorium</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Tarif Laboratorium dengan atas nama : <strong>' + $("#nama_item").val() + ' dengan kode item : ' + $("#kode_item").val() + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laboratorium/simpan_tarif_laboratorium',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token:response.csrf_token,
                        isedit:isedit,
                        kode_item:$("#kode_item").val(),
                        nama_item:$("#nama_item").val(),
                        group_item:$("#grup_item").val(),
                        id_kategori:$("#kategori").val(),
                        nama_kategori:$("#kategori option:selected").text(),
                        satuan:$("#satuan").val(),
                        jenis_item:table_rentang_nilai_kenormalan_kuantitatif.data().length > 0 ? "kuantitatif" : "kualitatif",
                        meta_data_kuantitatif:getTableDataAsJson('kuantitatif'),
                        meta_data_kualitatif:getTableDataAsJson('kualitatif'),
                        harga_dasar:harga_dasar_tarif_laboratorium.get(),
                        meta_data_jasa:getTableDataAsJson('jasa'),
                        harga_jual:harga_jual_tarif_laboratorium.get(),
                        visible_item:$("#visible_item").val(),
                    },
                    success: function(response) {
                        if (response.success == false){
                            return createToast('Kesalahan Penyimpanan', 'top-right', response.message, 'error', 3000);
                        }
                        clear_formulir();
                        $("#table_tindakan_lab").DataTable().ajax.reload();
                        return createToast('Tarif Laboratorium Telah Tersimpan', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
});
$("#btn_bersihkan_formulir").click(function(e){
    e.preventDefault();
    clear_formulir();
});
$("#kotak_pencarian").on("keyup", debounce(function(){
    $("#table_tindakan_lab").DataTable().ajax.reload();
}, 300));
$("#data_ditampilkan").change(function(){
    $("#table_tindakan_lab").DataTable().page.len($(this).val()).draw();
    $("#table_tindakan_lab").DataTable().ajax.reload();
});
function clear_formulir() {
    isedit = false;
    $("#kode_item").val('');
    $("#nama_item").val('');
    $("#batas_atas").val('');
    $("#antara").prop('selectedIndex', 0);
    $("#batas_bawah").val('');
    $("#keterangan_kualitatif_positif").val('');
    $("#keterangan_kualitatif_negatif").val('');
    choiceGrupItem.setChoiceByValue("laboratorium");
    load_kategori_dinamis();
    load_satuan_dinamis();
    choiceRentangKenormalanKualitatif.setChoiceByValue(selectElementRentangKenormalanKualitatif.options[0].value);
    choiceRentangKenormalanKuantitatif.setChoiceByValue(selectElementRentangKenormalanKuantitatif.options[0].value);
    harga_dasar_tarif_laboratorium.set('');
    harga_jual_tarif_laboratorium.set('');
    table_rentang_nilai_kenormalan_kualitatif.clear().draw();
    table_rentang_nilai_kenormalan_kuantitatif.clear().draw();
    table_tarif_laboratorium.clear().draw();
}
function hapus_tarif_tabel(button, type) {
    let row = $(button).closest('tr');
    let table = type === 'kualitatif' ? table_rentang_nilai_kenormalan_kualitatif : table_rentang_nilai_kenormalan_kuantitatif;
    table.row(row).remove().draw();
    table.on('draw', function () {
        table
            .rows()
            .every(function (rowIdx, tableLoop, rowLoop) {
                this.cell(rowIdx, 0).data(rowIdx + 1);
            });
    });
}
function getTableDataAsJson(kondisi) {
    if (kondisi == 'kuantitatif') {
        const data = table_rentang_nilai_kenormalan_kuantitatif.data().toArray();
        if (data.length == 0) {
            return [];
        }
        return data.map(row => {
            return {
                id_nilai_kenormalan: row[1], 
                batas_umur: row[2], 
                nama_nilai_kenormalan: row[3], 
                batas_bawah: row[4], 
                antara: row[5], 
                batas_atas: row[6], 
            };
        });
    } else if (kondisi == 'kualitatif') {
        const data = table_rentang_nilai_kenormalan_kualitatif.data().toArray();
        if (data.length == 0) {
            return [];
        }
        return data.map(row => {
            return {
                id_nilai_kenormalan: row[1], 
                batas_umur: row[2], 
                nama_nilai_kenormalan: row[3], 
                nilai_positif: row[5] !== "" ? true : false, 
                keterangan_positif: row[5], 
                nilai_negatif: row[7] !== "" ? true : false, 
                keterangan_negatif: row[7], 
            };
        });
    } else if (kondisi == 'jasa') {
        const data = table_tarif_laboratorium.data().toArray();
        if (data.length == 0) {
            return [];
        }
        const harga_baru = document.querySelectorAll('.nominal_pembayaran');
        const data_harga = Array.from(harga_baru).map(input => input.value);
        return data.map((row, index) => {
            return {
                id_jasa: row.id_jasa,
                kode_jasa: row.kode_jasa_pelayanan,
                tujuan_jasa: row.nama_jasa_pelayanan,
                harga_jasa: numbro.unformat(data_harga[index]),
                kategori_layanan: row.kategori_layanan,
            };
        });
    }
}
$("#antara").change(function(){
    if ($(this).prop('selectedIndex') > 0) { 
        $("#batas_atas").val('0');
        $("#batas_atas").prop('disabled', true);
    } else {
        $("#batas_atas").prop('disabled', false);
    }
});
function detail_informasi_tarif_tabel(kode_item, group_item) {
    isedit = true;
    $("#grup_item").val(group_item).trigger('change');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laboratorium/detail_tarif_laboratorium',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token:response.csrf_token,
                kode_item:kode_item,
            },
            success: function(response) {
                const targetElement = document.getElementById('on_top_formulir_tambah_tarif');
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                const form = $("#formulir_tambah_tarif");
                form.collapse("show");
                $("#collapse_formulir").text("Sembunyikan Formulir");
                $("#kode_item").val(response.data.kode_item);
                $("#nama_item").val(response.data.nama_item);
                choiceGrupItem.setChoiceByValue(response.data.group_item);
                setTimeout(() => {
                    choiceKategori.setChoiceByValue(response.data.id_kategori.toString());
                    choiceSatuan.setChoiceByValue(response.data.satuan);
                    $("#kategori").val(response.data.id_kategori.toString());
                    $("#satuan").val(response.data.satuan);
                }, 500);
                table_rentang_nilai_kenormalan_kuantitatif.clear().draw();
                table_rentang_nilai_kenormalan_kualitatif.clear().draw();
                if (response.data.group_item === 'laboratorium'){
                    $("#section_nilai_kenormalan").show();
                    if (response.data.jenis_item == 'kuantitatif'){
                        const tabKuantitatif = new bootstrap.Tab(document.querySelector('#tab_jenis_item_kuantitatif'));
                        tabKuantitatif.show();
                        const dataKuantitatif = JSON.parse(response.data.meta_data_kuantitatif);
                        table_rentang_nilai_kenormalan_kuantitatif.clear().draw();
                        dataKuantitatif.forEach(function(row, index) {
                            table_rentang_nilai_kenormalan_kuantitatif.row.add([
                                index + 1,
                                row.id_nilai_kenormalan,
                                row.batas_umur,
                                row.nama_nilai_kenormalan,
                                row.batas_bawah,
                                row.antara,
                                row.batas_atas,
                                `<button onclick="hapus_tarif_tabel(this, 'kuantitatif')" class="btn btn-danger btn-sm w-100"><i class="fa fa-trash"></i> Hapus</button>`
                            ]);
                        });
                        table_rentang_nilai_kenormalan_kuantitatif.draw();
                    }else if (response.data.jenis_item == 'kualitatif'){
                        const tabKualitatif = new bootstrap.Tab(document.querySelector('#tab_jenis_item_kualitatif'));
                        tabKualitatif.show();
                        const dataKualitatif = JSON.parse(response.data.meta_data_kualitatif);
                        table_rentang_nilai_kenormalan_kualitatif.clear().draw();
                        dataKualitatif.forEach(function(row, index) {
                            table_rentang_nilai_kenormalan_kualitatif.row.add([
                                index + 1,
                                row.id_nilai_kenormalan,
                                row.batas_umur,
                                row.nama_nilai_kenormalan,
                                row.nilai_positif ? "✔️" : "❌",
                                row.keterangan_positif,
                                row.nilai_negatif ? "✔️" : "❌",
                                row.keterangan_negatif,
                                `<button onclick="hapus_tarif_tabel(this, 'kualitatif')" class="btn btn-danger btn-sm w-100"><i class="fa fa-trash"></i> Hapus</button>`
                            ]);
                        });
                        table_rentang_nilai_kenormalan_kualitatif.draw();
                    }
                }else{
                    $("#section_nilai_kenormalan").hide();
                }
                $("#harga_dasar_tarif_laboratorium").val(response.data.harga_dasar);
                $("#harga_jual_tarif_laboratorium").val(response.data.harga_jual);
                harga_dasar_tarif_laboratorium.set(response.data.harga_dasar);
                const data = JSON.parse(response.data.meta_data_jasa);
                table_tarif_laboratorium.rows().every(function() {
                    const row = this.data();
                    const kodeJasaPelayanan = row.kode_jasa_pelayanan;
                    const matchingData = data.find(item => item.kode_jasa === kodeJasaPelayanan);
                    if (matchingData) {
                        const input = this.node().querySelector('input[name="tarif_laboratorium[]"]');
                        const autoNumericInstance = autoNumericFields.find(instance => instance.domElement === input);
                        autoNumericInstance.set(matchingData.harga_jasa);
                    }
                });
                calculateTotal();
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Pembacaan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
}
function detail_informasi_tarif(kode_item, nama_item) {
    isedit = false;
    $("#modalLihatTarifLabel").text("Lihat Tarif Nama : " + nama_item);
    $("#kode_item_tarif").text(kode_item);
    $("#nama_item_tarif").text(nama_item);
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laboratorium/detail_tarif_laboratorium',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token:response.csrf_token,
                kode_item:kode_item,
            },
            success: function(response) {
                if (response.data.group_item === 'laboratorium') {
                    $("#section_nilai_kenormalan_tarif_modal").show();
                } else {
                    $("#section_nilai_kenormalan_tarif_modal").hide();
                }
                $("#grup_item_tarif").text(capitalizeFirstLetter(response.data.group_item));
                $("#jenis_item_tarif").text(capitalizeFirstLetter(response.data.nama_kategori));
                $("#satuan_tarif").text(response.data.nama_satuan);
                $("#harga_dasar_tarif").text(response.data.harga_dasar.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
                $("#harga_jual_tarif").text(response.data.harga_jual.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
                if (response.data.group_item == 'laboratorium') {
                    if (response.data.jenis_item == 'kuantitatif') {
                        table_jasa_laboratorium_tarif_kuantitatif.clear().draw();
                        const data = JSON.parse(response.data.meta_data_kuantitatif);
                        let nomor_kuantitatif = 1;
                        data.forEach(function(row, index) {
                            table_jasa_laboratorium_tarif_kuantitatif.row.add([
                                nomor_kuantitatif,     
                                row.nama_nilai_kenormalan || '-',     
                                row.batas_bawah || '-',                             
                                row.antara || '-',    
                                row.batas_atas || '-',                  
                                row.batas_umur == "-1" ? "Semua Umur" : row.batas_umur || '-'    
                            ]);
                            nomor_kuantitatif++;
                        });
                        table_jasa_laboratorium_tarif_kuantitatif.draw();
                        $("#table_jasa_laboratorium_tarif_kuantitatif").show();
                        $("#table_jasa_laboratorium_tarif_kualitatif").hide();
                    } else if (response.data.jenis_item == 'kualitatif') {
                        table_jasa_laboratorium_tarif_kualitatif.clear().draw();
                        const data = JSON.parse(response.data.meta_data_kualitatif);
                        let nomor_kualitatif = 1;
                        data.forEach(function(row, index) {
                            table_jasa_laboratorium_tarif_kualitatif.row.add([
                                nomor_kualitatif,
                                row.nama_nilai_kenormalan || '-',
                                row.nilai_positif ? "✔️" : '❌',
                                row.keterangan_positif || '-',
                                row.nilai_negatif ? "✔️" : '❌',
                                row.keterangan_negatif || '-',
                            ]);
                            nomor_kualitatif++;
                        });
                        table_jasa_laboratorium_tarif_kualitatif.draw();
                        $("#table_jasa_laboratorium_tarif_kuantitatif").hide();
                        $("#table_jasa_laboratorium_tarif_kualitatif").show();
                    }
                }
                
                const dataTarif = JSON.parse(response.data.meta_data_jasa);
                table_jasa_laboratorium_tarif_modal.clear().draw();
                let nomor_jasa = 1;
                dataTarif.forEach(function(row, index_jasa) {
                    if (row.harga_jasa != null) {
                        table_jasa_laboratorium_tarif_modal.row.add([
                            nomor_jasa,
                            row.tujuan_jasa,
                            parseInt(row.harga_jasa).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }),
                        ]);
                        nomor_jasa++;
                    }
                });
                table_jasa_laboratorium_tarif_modal.draw();
                $("#modalLihatTarif").modal("show");
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Pembacaan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
}
function hapus_informasi_tarif(kode_item, nama_item) {
    isedit = false;
    Swal.fire({ 
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Informasi Tarif Laboratorium</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi tarif laboratorium dengan atas nama : <strong>' + nama_item + '</strong> ?. Informasi yang terhapus dapat dikembalikan jika membutuhkan restore data, silahkan hubungi administrator dengan konfirmasi kode item : <strong>' + kode_item + '</strong> serta membutuhkan waktu sesuai antrian permintaan restore data</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laboratorium/hapus_tarif_laboratorium',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token:response.csrf_token,
                        kode_item:kode_item,
                        nama_item:nama_item,
                    },
                    success: function(response) {
                        if (response.success == false){
                            return createToast('Kesalahan Penyimpanan', 'top-right', response.message, 'error', 3000);
                        }
                        $("#table_tindakan_lab").DataTable().ajax.reload();
                        return createToast('Tarif Laboratorium Telah Terhapus', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    }); 
}   