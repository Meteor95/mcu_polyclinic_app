let data_table_tindakan;
let isHeaderAdded = false;
let choicesDokterBertugas,choicesPenanggungJawab;
let selectedDokterBertugas = document.getElementById('dokter_bertugas_mcu'),selectedPenanggungJawab = document.getElementById('penanggung_jawab_mcu');
let is_paket_mcu = 0;
let nama_paket_mcu = 0;
let generate_total_harga_tindakan_mcu_autoNumeric = new AutoNumeric('#generate_total_harga_tindakan_mcu', {
    decimal: '.',
    digit: ',',
    allowDecimalPadding: false,
    minimumValue: '0',
    modifyValueOnUpDownArrow: false,
    modifyValueOnWheel: false,
});
let nominalBayar = new AutoNumeric('#nominal_bayar', {  decimal: ',',
    digit: '.',
    allowDecimalPadding: false,
    minimumValue: '0',
    modifyValueOnUpDownArrow: false,
    modifyValueOnWheel: false,});
let nominalKembalian = new AutoNumeric('#nominal_kembalian', {  decimal: ',',
    digit: '.',
    allowDecimalPadding: false,
    modifyValueOnUpDownArrow: false,
    modifyValueOnWheel: false,});
let nominalBayarKonfirmasi = new AutoNumeric('#nominal_bayar_konfirmasi', {  decimal: ',',
    digit: '.',
    allowDecimalPadding: false,
    minimumValue: '0',
    modifyValueOnUpDownArrow: false,
    modifyValueOnWheel: false,});
const paramsURL = new URLSearchParams(window.location.search);
const paramter_tindakan = atob(paramsURL.get("paramter_tindakan"));
const param_url = paramsURL.get("paramter_tindakan");
let is_edit_transaksi = false;
let id_transaksi = "";
$(document).on('keydown', function (e) {
    if (e.key === 'F1') {
        e.preventDefault();
        $('#tindakan_tersedia_mcu').select2('open');
    }
    if (e.key === 'F5') {
        e.preventDefault();
        Swal.fire({
            html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/7db777ff-33db-4085-8f8b-b5dfb5063d55/5XyQMW66KU.lottie" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Segarkan Halaman Ini</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin memuat ulang halaman ini ? Semua data yang anda inputkan pada keranjang serta informasi yang anda inputkan akan hilang !</p></div></div>',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: 'orange',
            confirmButtonText: 'Muat Ulang Halaman',
            cancelButtonText: 'Nanti Dulu!!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        })
    }
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        if ($("#modalKonfimasiPendaftaran").hasClass('show')) {
            simpan_konfirmasi();
        }else{
            konfirmasi_transaksi_tindakan_mcu();
        }
    }
});
$(document).ready(function(){
    document.querySelectorAll('[data-state]').forEach(element => {
        element.classList.add('close_icon');
    });
    callGlobalSelect2SearchByMember('pencarian_member_mcu', 'tindakan');
    load_data_tindakan();
    updateCardStyles();
    flatpickr("#waktu_transaksi_mcu", {
        enableTime: true,
        dateFormat: "d-m-Y H:i:s",
        maxDate: 'today',
        time_24hr: true,
    });
    $("#waktu_transaksi_mcu").val(moment().format('DD-MM-YYYY HH:mm:ss'));
    flatpickr("#waktu_transaksi_sample_mcu", {
        enableTime: true,
        dateFormat: "d-m-Y H:i:s",
        maxDate: 'today',
        time_24hr: true,
    });
    load_data_dokter_bertugas('dokter')
    load_data_dokter_bertugas('')
    load_data_tarif_tindakan()
    load_table_template_tindakan_mcu()
    if (param_url !== null) {
        is_edit_transaksi = true;
        onload_detail_tindakan();
    }
});
function onload_detail_tindakan(){
    /* ini akan mengoveride semua data yang ada pada form transaksi */
    if (is_edit_transaksi){
        $("#container_transaksi_tindakan").addClass('blur-grayscale');
        createToast('Informasi Mode Ubah Data', 'top-right', 'Anda dalam mode EDIT data transaksi. Silahkan klik tombol "SIMPAN" untuk menyimpan data transaksi terbaru', 'warning', 3000);
        let newOption = new Option('['+paramter_tindakan.split('|')[2]+'] - '+paramter_tindakan.split('|')[3], paramter_tindakan.split('|')[2], true, false);
        $("#pencarian_member_mcu").append(newOption).trigger('change');
        $("#pencarian_member_mcu").val(paramter_tindakan.split('|')[2]).trigger('change');
        data_table_tindakan.rows().clear().draw();
        $("#container_transaksi_tindakan").addClass('blur-grayscale');
        $.get('/generate-csrf-token', function(response) {
            $.ajax({
                url: baseurlapi + '/laboratorium/detail_tindakan',
                type: 'GET',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                data: {
                    _token: response.csrf_token,
                    id_transaksi: paramter_tindakan.split('|')[0],
                    nomor_identitas: paramter_tindakan.split('|')[2],
                },
                success: function(response) {
                    id_transaksi = response.transaksi[0].id_transaksi;
                    is_paket_mcu = response.transaksi[0].is_paket_mcu;
                    nama_paket_mcu = response.transaksi[0].nama_paket_mcu;
                    $("#nomor_transaksi_mcu_generate").val(response.transaksi[0].no_nota);
                    $("#waktu_transaksi_mcu").val(moment(response.transaksi[0].waktu_trx).format('DD-MM-YYYY HH:mm:ss'));
                    $("#waktu_transaksi_sample_mcu").val(moment(response.transaksi[0].waktu_trx_sample).format('DD-MM-YYYY HH:mm:ss'));
                    const radioHutang = document.getElementById("hutang");
                    const radioTunai = document.getElementById("tunai");
                    if (response.transaksi[0].jenis_transaksi == 0) {
                        radioHutang.checked = true;
                    } else {
                        radioTunai.checked = true;
                        if (response.transaksi[0].jenis_transaksi == 1) {
                            $("#select2_metode_pembayaran").val(0).trigger('change');
                            nominalBayar.set(response.transaksi[0].total_bayar);
                            nominalKembalian.set(response.transaksi[0].total_bayar - response.transaksi[0].total_transaksi);
                        } else {
                            $("#select2_metode_pembayaran").val(1).trigger('change');
                            const values = response.transaksi[0].metode_pembayaran.split('|');
                            $("#beneficiary_bank").val(values[0]).trigger('change');
                            $("#nomor_transaksi_transfer").val(values[2]);
                        }
                    }
                    setTimeout(() => {
                        choicesDokterBertugas.setChoiceByValue(response.transaksi[0].id_dokter.toString());
                        choicesPenanggungJawab.setChoiceByValue(response.transaksi[0].id_pj.toString());
                    }, 1000);
                    response.transaksi.forEach((item, index) => {
                        let nomor = index + 1;
                        const metaDataKuantitatif = item.meta_data_kuantitatif == "{}" ? {} : JSON.parse(item.meta_data_kuantitatif);
                        const metaDataKualitatif = item.meta_data_kualitatif == "{}" ? {} : JSON.parse(item.meta_data_kualitatif);
                        data_table_tindakan.row.add([
                            nomor,
                            item.kode_item,
                            item.nama_item,
                            createInput(nomor, 'nilai_tindakan', item.kode_item, item.nilai_tindakan),
                            createInput(nomor, 'harga_jual', item.kode_item, item.is_paket_mcu > 0 ? 0 : item.harga),
                            createInput(nomor, 'diskon', item.kode_item, item.is_paket_mcu > 0 ? 0 : item.diskon),
                            createInput(nomor, 'harga_setelah_diskon', item.kode_item, item.is_paket_mcu > 0 ? 0 : item.harga_setelah_diskon, true),
                            createInput(nomor, 'jumlah', item.kode_item, '1'),
                            createInput(nomor, 'total_harga', item.kode_item, item.is_paket_mcu > 0 ? 0 : (item.harga_setelah_diskon * item.jumlah), true),
                            `<div class="d-flex justify-content-center gap-2">
                                <button onclick="modal_bagi_fee_tindakan_mcu('${item.id}','${item.meta_data_jasa_fee}','${item.kode_item}','${nomor}','${item.nama_item}')" class="w-100 btn btn-success btn-sm btn_bagi_fee_tindakan_mcu" id="btn_bagi_fee_tindakan_mcu_${item.kode_item}"><i class="fa fa-edit"></i> Fee</button>
                                ${item.is_paket_mcu > 0 ? '' : `<button class="w-100 btn btn-danger btn-sm btn_hapus_tindakan_mcu" id="btn_hapus_tindakan_mcu_${item.kode_item}"><i class="fa fa-trash"></i></button>`}
                            </div>`,
                            `<input type="text" class="form-control" id="meta_data_kuantitatif_${item.kode_item}_${nomor}" value="${Array.isArray(metaDataKuantitatif) ? btoa(item.meta_data_kuantitatif) : ''}">`,
                            `<input type="text" class="form-control" id="meta_data_kualitatif_${item.kode_item}_${nomor}" value="${Array.isArray(metaDataKualitatif) ? btoa(item.meta_data_kualitatif) : ''}">`,
                            `<input type="text" class="form-control" id="meta_data_jasa_${item.kode_item}_${nomor}" value="${item.meta_data_jasa}">`,
                            `<input type="text" class="form-control" id="meta_data_jasa_fee_${item.kode_item}_${nomor}" value="${item.meta_data_jasa_fee}">`,
                            `${item.id_item}`,
                        ]).draw();
                        initAutoNumeric(nomor, item.kode_item, item.is_paket_mcu > 0 ? item.total_transaksi : 0);
                    });
                    hitung_keranjang_tindakan(null, null, null, response.transaksi[0].is_paket_mcu > 0 ? response.transaksi[0].total_transaksi : 0);
                    updateRowNumbers(response.transaksi[0].is_paket_mcu > 0 ? response.transaksi[0].total_transaksi : 0);
                    let lastRow = data_table_tindakan.row(data_table_tindakan.rows().count() - 1).node();
                    $(lastRow).get(0).scrollIntoView({ behavior: 'smooth', block: 'end' });
                    $("#tindakan_tersedia_mcu").val(null).trigger('change');
                },
                complete: function() {
                    $("#container_transaksi_tindakan").removeClass('blur-grayscale');
                },
                error: function(xhr, status, error) {
                    return createToast('Kesalahan Penggunaan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                }
            });
        });
        
    }
}
function load_table_template_tindakan_mcu(){
    $.get('/generate-csrf-token', function(response) {
        $("#table_template_tindakan_mcu_modal").DataTable({
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
                "url": baseurlapi + '/laboratorium/daftar_template_laboratorium',
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
                            return row.used_paket_mcu == 0 ? "Tidak Terhubung Paket MCU" : "Nama Paket MCU : "+row.nama_paket+"<br>Harga MCU : "+(row.harga_paket == null ? 0 : row.harga_paket).toLocaleString('id-ID');
                        }
                        return data;
                    }
                },
                {
                    title: "Nama Tindakan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            if (row.meta_data_template && Array.isArray(row.meta_data_template)) {
                                const namaItems = row.meta_data_template.map(template => {
                                    const namaItem = template?.tarif?.nama_item;
                                    return namaItem 
                                        ? `<span class="badge bg-primary me-1">${namaItem}</span>` 
                                        : '';
                                });
                                return namaItems.join(' ');
                            } else {
                                return '';
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
                                <button id="btn_pilih_template_tindakan_mcu_${row.id_template_tindakan}" onclick="pilih_template_tindakan_mcu('${row.id_template_tindakan}','${row.nama_template}','${row.id_template_tindakan}','${row.used_paket_mcu}')" class="btn btn-success w-100">
                                    <i class="fa fa-check"></i> Pilih
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
$("#data_ditampilkan").change(function(){
    $("#table_template_tindakan_mcu_modal").DataTable().page.len($(this).val()).draw();
    $("#table_template_tindakan_mcu_modal").DataTable().ajax.reload();
});
function load_data_tarif_tindakan(){
    $.get('/generate-csrf-token', function(response) {
        $('#tindakan_tersedia_mcu').select2({ 
            placeholder: 'Ketikan Kode, Nama atau Harga Tindakan',
            allowClear: true,
            ajax: {
                url: baseurlapi + '/laboratorium/pencarian_tarif_laboratorium',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                method: 'GET',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        _token : response.csrf_token,
                        parameter_pencarian : (typeof params.term === "undefined" ? "" : params.term),
                        limit : 50,
                    }
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.nama_item,
                                id: `${item.id}`,
                                kode_item: item.kode_item,
                                harga_jual: item.harga_jual,
                                nama_item: capitalizeFirstLetter(item.nama_item),
                                group_item: capitalizeFirstLetter(item.group_item),
                                json_data: item,
                            };
                        })
                    }
                },
                error: function(xhr, status, error) {
                    return createToast('Kesalahan Penggunaan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                }
            },
            templateResult: function (data) {
                if (!data.id) {
                    return data.text;
                }
                let header = '';
                if (!isHeaderAdded) {
                    header = `
                        <tr>
                            <td style="width: 20%; font-weight: bold;">Kode Item</td>
                            <td style="width: 10%; font-weight: bold;">Harga Jual</td>
                            <td style="width: 50%; font-weight: bold;">Nama Item</td>
                            <td style="width: 20%; font-weight: bold;">Jenis</td>
                        </tr>`;
                    isHeaderAdded = true;
                }
                return $(
                    `<table style="width: 100%;">
                        ${header}
                        <tr>
                            <td style="width: 20%; text-align: left;">${data.kode_item}</td>
                            <td style="width: 10%; text-align: left;">${data.harga_jual.toLocaleString('id-ID')}</td>
                            <td style="width: 50%; text-align: left;">${data.nama_item}</td>
                            <td style="width: 20%; text-align: left;">${data.group_item}</td>
                        </tr>
                    </table>`
                );
            },
            templateSelection: function (data) {
                return data.nama_item || data.text;
            },
            escapeMarkup: function (markup) {
                return markup;
            },
        }); 
    });   
}
$('#tindakan_tersedia_mcu').on('select2:close', function () {
    isHeaderAdded = false;
}); 
function load_data_dokter_bertugas(role){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pengguna/load_data_dokter_bertugas',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                role: role,
            },
            success: function(response) {
                const hierarchicalData = response.data;
                selectedDokterBertugas.innerHTML = '';
                selectedPenanggungJawab.innerHTML = '';
                if (role == 'dokter') {
                    hierarchicalData.forEach(dokter_bertugas => {
                        const option = document.createElement('option');
                        option.value = dokter_bertugas.id;
                        option.textContent = `${dokter_bertugas.nama_pegawai}`;
                        selectedDokterBertugas.appendChild(option);
                    });
                    if (choicesDokterBertugas) { choicesDokterBertugas.destroy(); }
                        choicesDokterBertugas = new Choices(selectedDokterBertugas, {
                        searchEnabled: true,
                        shouldSort: false,
                        placeholder: true,
                        placeholderValue: 'Tentukan Dokter Bertugas',
                    });
                }
                if (role != 'dokter') {
                    hierarchicalData.forEach(dokter_bertugas => {
                        const option = document.createElement('option');
                        option.value = dokter_bertugas.id;
                        option.textContent = `${dokter_bertugas.nama_pegawai}`;
                        selectedPenanggungJawab.appendChild(option);
                    });
                    if (choicesPenanggungJawab) { choicesPenanggungJawab.destroy(); }
                        choicesPenanggungJawab = new Choices(selectedPenanggungJawab, {
                        searchEnabled: true,
                        shouldSort: false,
                        placeholder: true,
                        placeholderValue: 'Tentukan Penanggung Jawab',
                    });
                }
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
}
function load_data_tindakan(){
    data_table_tindakan = $("#table_tindakan_mcu").DataTable({
        language: {
            search: "Cari Data:"
        },
        dom: 'B<"sticky-wrapper"f>rtip',
        lengthChange: false,
        ordering: false,
        scrollX: $(window).width() < 768 ? true : false,
        info: false,
        paging: false,
        keys: true,
        columnDefs: [
            {
                targets: [10, 11, 12, 13, 14],
                className: "d-none",
            },
            {
                defaultContent: "-",
                targets: "_all",
            },
        ],
    }).on('key-focus', function ( e, datatable, cell, originalEvent ) {
        $('input', cell.node()).focus();
    }).on("focus", "td input", function(){
        $(this).select();
    });
    data_table_tindakan.on('key', function(e, dt, code) {
        if (code === 13) {
            data_table_tindakan.keys.move('down');
        }
    });
}
function clear_keranjang_tindakan(){
    data_table_tindakan.rows().clear().draw();
    hitung_keranjang_tindakan();
}
$("#pencarian_member_mcu").on('change', function(){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pendaftaran/getdatapasien',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token : response.csrf_token,
                nomor_identitas : $("#pencarian_member_mcu").val() == '' ? code.split('|')[2] : $("#pencarian_member_mcu").val()
            },
            success: function(response) {
                if (!response.success) {
                    clear_tanda_vital();
                    return createToast('Data tidak ditemukan', 'top-right', response.message, 'error', 3000);
                }
                $("#nomor_transaksi_mcu").text(response.data.no_transaksi);
                $("#nomor_identitas_mcu").text(response.data.nomor_identitas);
                $("#id_user_mcu").text(response.data.user_id);
                $("#id_transaksi_mcu").text(response.data.id_transaksi);
                $("#nama_peserta_mcu").text(response.data.nama_peserta);
                $("#no_telepon_mcu").text(response.data.no_telepon);
                $("#jenis_kelamin_mcu").text(response.data.jenis_kelamin);
                $("#email_mcu").text(response.data.email);
                $("#company_name_mcu").text(response.data.company_name);
                $("#nama_departemen_mcu").text(response.data.nama_departemen);
                $("#status_peserta_mcu").text(capitalizeFirstLetter(response.data.jenis_transaksi_pendaftaran));
                if (param_url === null) {
                    clear_keranjang_tindakan();
                    if (response.data.jenis_transaksi_pendaftaran == 'MCU') {
                        $("#nomor_transaksi_mcu_generate").val("Membaca Paket MCU "+response.data.nama_paket);
                        pilih_template_tindakan_mcu(response.data.id_template_tindakan, response.data.nama_paket, "", response.data.id_paket_mcu, response.data.harga_paket)
                    }
                    $("#nomor_transaksi_mcu_generate").val("TRX/LAB/" + moment().format('YYYYMMDDHHmmss')+"/"+$("#nomor_transaksi_mcu").text());
                }
            }
        });
    });
});
$("#btn_informasi_pasien_mcu").on('click', function(){
    $("#informasi_pasien_mcu").toggle();
    if (this.textContent == 'Tampilkan Informasi') {
        this.textContent = 'Sembunyikan Informasi';
    } else {
        this.textContent = 'Tampilkan Informasi';
    }
});
$("#generate_nomor_transaksi_mcu").on('click', function(){
    $("#nomor_transaksi_mcu_generate").val("TRX/LAB/" + moment().format('YYYYMMDDHHmmss')+"/"+$("#nomor_transaksi_mcu").text());
});
$("#btn_tambah_ke_keranjang_tindakan_mcu").on('click', function(){
    const data_tarif = $("#tindakan_tersedia_mcu").select2('data');
    tambah_ke_keranjang_tindakan(data_tarif);
});
$("#tindakan_tersedia_mcu").on("select2:select", function (e) {
    const data_tarif = $("#tindakan_tersedia_mcu").select2('data');
    tambah_ke_keranjang_tindakan(data_tarif);
});
$('#table_tindakan_mcu').on('click', '.btn_hapus_tindakan_mcu', function () {
    const row = $(this).closest('tr');
    const rowIndex = data_table_tindakan.row(row).index(); 
    delete rowIndexMap[rowIndex];
    data_table_tindakan.row(rowIndex).remove().draw();
    updateRowNumbers();
    hitung_keranjang_tindakan();
});
function updateRowNumbers(harga_paket = null) {
    data_table_tindakan.rows().every(function (rowIdx) {
        const row = this;
        const rowData = row.data();
        const rowIndex = rowData[0];
        const kode_item = rowData[1];
        this.cell({ row: rowIdx, column: 0 }).data(rowIdx + 1).draw();
        const fields = ['harga_jual', 'diskon', 'harga_setelah_diskon', 'total_harga', 'jumlah', 'meta_data_kuantitatif', 'meta_data_kualitatif', 'meta_data_jasa', 'meta_data_jasa_fee'];
        fields.forEach(field => {
            const input = $(row.node()).find(`#${field}_${kode_item}_${rowIndex}`);
            if (input.length > 0) {
                // Update input id
                input.attr('id', `${field}_${kode_item}_${rowIdx + 1}`);
                // Remove AutoNumeric if applicable
                if (field !== 'meta_data_kuantitatif' && field !== 'meta_data_kualitatif' && field !== 'meta_data_jasa' && field !== 'meta_data_jasa_fee') {
                    const autoNumericInstance = AutoNumeric.getAutoNumericElement(input[0]);
                    if (autoNumericInstance) {
                        autoNumericInstance.remove();
                    }
                }
            }
        });
        const deleteButton = $(row.node()).find(`#btn_hapus_tindakan_mcu_${rowIndex}`);
        if (deleteButton.length > 0) {
            deleteButton.attr('id', `btn_hapus_tindakan_mcu_${rowIdx + 1}`);
        }
        initAutoNumeric(rowIdx + 1, kode_item, harga_paket);
    });
    
}
const rowIndexMap = {};
function tambah_ke_keranjang_tindakan(data_tarif, dari = null, harga_paket = false) {
    if (data_tarif.length == 0) {
        return createToast('Tidak ada data yang dipilih', 'top-right', 'Setidaknya pilih 1 data tindakan untuk ditambahkan ke dalam keranjang tindakan lab dan pengobatan', 'error', 3000);
    }
    const tarif = dari === "template" ? data_tarif : data_tarif[0].json_data;
    const kode_item = tarif.kode_item;
    const nomor = (data_table_tindakan.rows().count() + 1);
    rowIndexMap[nomor] = {
        kode_item: kode_item,
    };
    data_table_tindakan.row.add([
        nomor,
        tarif.kode_item,
        tarif.nama_item,
        createInput(nomor, 'nilai_tindakan', kode_item, 0),
        createInput(nomor, 'harga_jual', kode_item, harga_paket > 0 ? 0 : tarif.harga_jual),
        createInput(nomor, 'diskon', kode_item, '0'),
        createInput(nomor, 'harga_setelah_diskon', kode_item, '0', true),
        createInput(nomor, 'jumlah', kode_item, '1'),
        createInput(nomor, 'total_harga', kode_item, '0', true),
        `<div class="d-flex justify-content-center gap-2">
            <button onclick="modal_bagi_fee_tindakan_mcu('${tarif.id}','${btoa(tarif.meta_data_jasa)}','${kode_item}','${nomor}','${tarif.nama_item}')" class="w-100 btn btn-success btn-sm btn_bagi_fee_tindakan_mcu" id="btn_bagi_fee_tindakan_mcu_${kode_item}"><i class="fa fa-edit"></i> Fee</button>
            ${harga_paket > 0 ? '' : `<button class="w-100 btn btn-danger btn-sm btn_hapus_tindakan_mcu" id="btn_hapus_tindakan_mcu_${kode_item}"><i class="fa fa-trash"></i></button>`}
        </div>`,
        `<input type="text" class="form-control" id="meta_data_kuantitatif_${kode_item}_${nomor}" value="${JSON.parse(tarif.meta_data_kuantitatif).length == 0 ? '' : btoa(tarif.meta_data_kuantitatif)}">`,
        `<input type="text" class="form-control" id="meta_data_kualitatif_${kode_item}_${nomor}" value="${JSON.parse(tarif.meta_data_kualitatif).length == 0 ? '' : btoa(tarif.meta_data_kualitatif)}">`,
        `<input type="text" class="form-control" id="meta_data_jasa_${kode_item}_${nomor}" value="">`,
        `<input type="text" class="form-control" id="meta_data_jasa_fee_${kode_item}_${nomor}" value="${btoa(tarif.meta_data_jasa)}">`,
        `${tarif.id}`,
    ]).draw();
    initAutoNumeric(nomor,kode_item, harga_paket);
    hitung_keranjang_tindakan(null, null, null, harga_paket);
    updateRowNumbers(harga_paket);
    let lastRow = data_table_tindakan.row(data_table_tindakan.rows().count() - 1).node();
    $(lastRow).get(0).scrollIntoView({ behavior: 'smooth', block: 'end' });
    $("#tindakan_tersedia_mcu").val(null).trigger('change');
}

function hitung_keranjang_tindakan(indexKe = null, id = null, paramter_id = null, harga_paket = null) {
    let general_total = 0;
    function hitungDiskon(hargaJual, diskon) {
        if (diskon < 100) {
            return (hargaJual - (hargaJual * diskon / 100)).toFixed(2);
        }
        return hargaJual - diskon;
    }
    if (indexKe != null) {
        const row = data_table_tindakan.row(indexKe - 1).node();
        const hargaJual = AutoNumeric.getAutoNumericElement($(row).find(`#harga_jual_${paramter_id}`)[0]).get();
        const diskon = AutoNumeric.getAutoNumericElement($(row).find(`#diskon_${paramter_id}`)[0]).get();

        let totalHargaSetelahDiskon = hitungDiskon(hargaJual, diskon);
        if (totalHargaSetelahDiskon < 0) {
            AutoNumeric.getAutoNumericElement($(row).find(`#diskon_${paramter_id}`)[0]).set("");
            return createToast('Kesalahan Diskon', 'top-right', 
                `Harga setelah diskon tidak boleh kurang dari 0. Silahkan cek kembali data diskon pada ID: ${capitalizeFirstLetter(id)} di Baris: ${indexKe}.`, 
                'error', 3000);
        }
        AutoNumeric.getAutoNumericElement($(row).find(`#harga_setelah_diskon_${paramter_id}`)[0]).set(totalHargaSetelahDiskon);
        const jumlah = AutoNumeric.getAutoNumericElement($(row).find(`#jumlah_${paramter_id}`)[0]).get();
        const totalHarga = totalHargaSetelahDiskon * jumlah;
        AutoNumeric.getAutoNumericElement($(row).find(`#total_harga_${paramter_id}`)[0]).set(totalHarga);
        general_total = Array.from($('.total_harga'))
            .map(el => AutoNumeric.getAutoNumericElement(el)?.get() || 0)
            .reduce((sum, value) => sum + parseFloat(value), 0);
        generate_total_harga_tindakan_mcu_autoNumeric.set(harga_paket > 0 ? harga_paket : general_total);
        return;
    }
    data_table_tindakan.rows().every(function () {
        const row = this;
        const kode_item = `${row.data()[1]}_${row.data()[0]}`;
        const hargaJual = AutoNumeric.getAutoNumericElement($(row.node()).find(`#harga_jual_${kode_item}`)[0]).get();
        const diskon = AutoNumeric.getAutoNumericElement($(row.node()).find(`#diskon_${kode_item}`)[0]).get();

        let totalHargaSetelahDiskon = hitungDiskon(hargaJual, diskon);
        AutoNumeric.getAutoNumericElement($(row.node()).find(`#harga_setelah_diskon_${kode_item}`)[0]).set(totalHargaSetelahDiskon);

        const jumlah = AutoNumeric.getAutoNumericElement($(row.node()).find(`#jumlah_${kode_item}`)[0]).get();
        const totalHarga = totalHargaSetelahDiskon * jumlah;
        AutoNumeric.getAutoNumericElement($(row.node()).find(`#total_harga_${kode_item}`)[0]).set(totalHarga);
        general_total += totalHarga;
    });
    generate_total_harga_tindakan_mcu_autoNumeric.set(harga_paket > 0 ? harga_paket : general_total);
}



function createInput(index, type, kode_item, value, readonly = false) {
    const readonlyAttr = readonly ? 'readonly' : '';
    const classAttr = readonly ? 'form-control readonly-input ' + type : 'form-control ' + type;
    let element = '';
    if (type == 'nilai_tindakan') {
        element = `<input data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" 
                        title="${value}" data-bs-original-title="${value}" 
                        type="text" class="${classAttr}" 
                        id="${type}_${kode_item}_${index}" 
                        value="${value}" ${readonlyAttr} 
                        style="text-align: right;width: ${calculateWidth(value,type)}">`;

        setTimeout(() => {
            $(`#${type}_${kode_item}_${index}`).tooltip();
        }, 50);
    }else{
        element = `<input type="text" class="${classAttr}" id="${type}_${kode_item}_${index}" value="${value}" ${readonlyAttr} style="text-align: right;width: ${calculateWidth(value,type)}">`;
    }
    
    return element;
}
$(document).on('keyup', 'input[data-bs-toggle="tooltip"]', debounce(function () {
    $(this).attr('data-bs-original-title', $(this).val()).tooltip('dispose').tooltip('show');
}, 300));

function calculateWidth(value,type) {
    const digitLength = value.toString().length;
    let minWidth = 150;
    let maxWidth = 300;
    if (type == 'jumlah') minWidth = 70;
    if (type == 'nilai_tindakan') { minWidth = 120; maxWidth = 120; }
    const width = Math.min(Math.max(minWidth, digitLength * 8), maxWidth);
    return `${width}px`;
}

function initAutoNumeric(index, kode_item, harga_paket = null) {
    const fields = ['harga_jual', 'diskon', 'harga_setelah_diskon', 'total_harga', 'jumlah'];
    fields.forEach(function (field, indexx) {
        const elementId = `#${field}_${kode_item}_${index}`;
        const inputElement = $(elementId);
        inputElement.off('keyup');
        if (!AutoNumeric.getAutoNumericElement(elementId)) {
            new AutoNumeric(elementId, getAutoNumericOptions());
        }
        inputElement.on('keyup', debounce(function(event) {
            if (harga_paket > 0) {
                $(elementId).val(0);
                AutoNumeric.getAutoNumericElement(elementId).set(0);
                return createToast('Kesalahan Perhitungan', 'top-right', 
                    'Pasien ini terindikasi terhubung dengan paket MCU, sehingga harga paket MCU akan dihitung secara otomatis dan tidak bisa diubah', 
                    'success', 3000);
            }
            const charCode = event.which ? event.which : event.keyCode;
            if (!((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105))) {
                event.preventDefault();
                return;
            }
            hitung_keranjang_tindakan(index, this.id, `${kode_item}_${index}`, harga_paket);
        }, 300));
    });   
}
function getAutoNumericOptions() {
    return {
        decimal: ',',
        digit: '.',
        allowDecimalPadding: false,
        minimumValue: '0',
        modifyValueOnUpDownArrow: false,
        modifyValueOnWheel: false,
    };
}
$("#table_tindakan_mcu_filter").on('keyup', function(){
    data_table_tindakan.search($(this).val()).draw();
});
$("#btn_baca_template_tindakan_mcu").on('click', function(){
    $("#modal_baca_template_tindakan_mcu").modal('show');
});
function pilih_template_tindakan_mcu(id_template_tindakan, nama_template, id_button, used_paket_mcu, harga_paket = false){
    $("#btn_pilih_template_tindakan_mcu_"+id_button).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memuat Data...');
    data_table_tindakan.rows().clear().draw();
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laboratorium/pilih_template_tindakan_mcu',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                id_template_tindakan: id_template_tindakan,
                nama_template: nama_template,
            },
            success: function(response) {
                if (response.data.length == 0) {
                    return createToast('Data tidak ditemukan', 'top-right', 'Informasi template tindakan tidak ditemukan. Silahkan tentukan template terlebih dahulu untuk menghindari kerancuan data', 'error', 3000);
                }
                is_paket_mcu = used_paket_mcu;
                nama_paket_mcu = nama_template;
                response.data.forEach(tarif => {
                    tambah_ke_keranjang_tindakan(tarif, "template", harga_paket);
                });
                $("#btn_pilih_template_tindakan_mcu_"+id_button).prop('disabled', false).html('<i class="fa fa-check"></i> Pilih');
                $("#modal_baca_template_tindakan_mcu").modal('hide');
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
}
$('#sembunyikan_informasi').click(function() {
    var icon = $('#arrowIcon');
    if (icon.hasClass('fa-arrow-up')) {
        icon.removeClass('fa-arrow-up').addClass('fa-arrow-down');
        $('.header_informasi').slideUp();
    } else {
        icon.removeClass('fa-arrow-down').addClass('fa-arrow-up');
        $('.header_informasi').slideDown();
    }
});
$("#refresh_keranjang_tindakan_mcu").on('click', function(){
    data_table_tindakan.rows().clear().draw();
    hitung_keranjang_tindakan();
    updateRowNumbers();
});
let tagifyInstances = {};
function modal_bagi_fee_tindakan_mcu(id_tarif, meta_data_jasa, kode_item, nomor, nama_item) {
    tagifyInstances = {};
    let tableHtml = '';
    $('#table_jasa tbody').html('');
    $("#kode_item_tabel").html(kode_item);
    $("#nomor_item_tabel").html(nomor);
    $("#konfirmasi_pembagian_fee_tindakan_mcu").prop('disabled', false);
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pengguna/load_data_dokter_bertugas',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: { _token: response.csrf_token, role: '' },
            success: function(response) {
                const hierarchicalData = response.data;
                let json_meta_data_jasa = JSON.parse(atob(meta_data_jasa));
                $('#table_jasa_container').html('');
                $('#table_jasa_container_alert').html('');
                json_meta_data_jasa.forEach(item => {
                    const tableId = `table_jasa_item_${item.kode_jasa}`;
                    tableHtml = `
                        <table id="${tableId}" class="table table-bordered table-padding-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Kode Jasa</th>
                                    <th>Harga Jasa</th>
                                    <th>Tujuan Jasa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>${item.kode_jasa}</td>
                                    <td>${Number(item.harga_jasa).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                                    <td>${item.tujuan_jasa}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <input id="input_pilih_jasa_${item.kode_jasa}" placeholder="Tentukan Penerima Jasa Untuk ${item.tujuan_jasa}" class="form-control">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    `;
                    $('#table_jasa_container').append(tableHtml);
                    let input = document.getElementById(`input_pilih_jasa_${item.kode_jasa}`);
                    tagifyInstances[item.kode_jasa] = new Tagify(input, {
                        whitelist: hierarchicalData.map(pegawai => ({ 
                            id: pegawai.id, 
                            value: pegawai.nama_pegawai,
                            harga_jasa: item.harga_jasa,
                            jumlah_tag_terpilih: 0,
                            kode_jasa: item.kode_jasa
                        })),
                        dropdown: { 
                            position: "manual", 
                            maxItems: 3, 
                            enabled: 0, 
                            classname: "customSuggestionsList" 
                        },
                        templates: {
                            dropdownItemNoMatch() {
                                return `<div class='empty'>Tidak ada pilihan yang tersedia</div>`;
                            },
                        },
                        enforceWhitelist: true,
                    });
                    tagifyInstances[item.kode_jasa].dropdown.show();
                    tagifyInstances[item.kode_jasa].DOM.scope.parentNode.appendChild(tagifyInstances[item.kode_jasa].DOM.dropdown);
                    tagifyInstances[item.kode_jasa].on('change', (e) => {
                        const selectedItems = e.detail.value;
                        tagifyInstances[item.kode_jasa].selectedItems = JSON.parse(selectedItems);
                        let selectedTagCount = tagifyInstances[item.kode_jasa].selectedItems.length;
                        hierarchicalData.forEach((pegawai) => {
                            const pegawaiInWhitelist = tagifyInstances[item.kode_jasa].settings.whitelist.find(p => p.id === pegawai.id);
                            if (pegawaiInWhitelist) {
                                pegawaiInWhitelist.jumlah_tag_terpilih = selectedTagCount;
                            }
                        });
                        tagifyInstances[item.kode_jasa].settings.whitelist.forEach(pegawai => {
                            pegawai.jumlah_tag_terpilih = selectedTagCount;
                        });
                        tagifyInstances[item.kode_jasa].jumlah_tag_terpilih = selectedTagCount;
                    });
                });
            },
            complete: function() {
                if (tableHtml == '') {
                    $('#table_jasa_container_alert').html(`
                        <div class="alert-box">
                            <div class="alert-body">
                                <svg><use href="/mofi/assets/svg/icon-sprite.svg#alert-popup"></use></svg>
                                <h6 class="mb-1">Meta Data Jasa Tidak Ditemukan</h6>
                                <p>Silahkan buat meta data jasa untuk Nama Tindakan / Item: ${nama_item} di Baris: ${nomor}.<br>Klik <a target="_blank" href="/laboratorium/tarif">disini</a> untuk membuat meta data jasa.</p>
                            </div>
                        </div>                                            
                    `);
                    $("#konfirmasi_pembagian_fee_tindakan_mcu").prop('disabled', true);
                }
                setTimeout(() => {
                    let modal_data_dari_tabel = $(`#meta_data_jasa_${kode_item}_${nomor}`).val();
                    if (modal_data_dari_tabel != '') {
                        let json_meta_data_jasa = JSON.parse(atob(modal_data_dari_tabel));
                        Object.keys(json_meta_data_jasa).forEach(kode_jasa => {
                            $(`#input_pilih_jasa_${kode_jasa}`).each(function() {
                                $(this).val(json_meta_data_jasa[kode_jasa].map(item => item.value).join(", "));
                            });
                        });
                    }
                }, 300);
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Pengambilan Data', 'top-right', error, 'error', 3000);
            },
        });
    });
    setTimeout(() => {
        $("#modal_bagi_fee_tindakan_mcu").modal('show');
    }, 100);
}
$("#konfirmasi_pembagian_fee_tindakan_mcu").on('click', function(){
    let metaDataJasa = {};
    let kodeitem = $("#kode_item_tabel").html();
    let nomoritem = $("#nomor_item_tabel").html();
    Object.keys(tagifyInstances).forEach(kode_jasa => {
        let selectedItems = tagifyInstances[kode_jasa].selectedItems;
        metaDataJasa[kode_jasa] = selectedItems;
    });
    const metaDataJasaString = JSON.stringify(metaDataJasa);
    $(`#meta_data_jasa_${kodeitem}_${nomoritem}`).val(btoa(metaDataJasaString));
    $("#modal_bagi_fee_tindakan_mcu").modal('hide');
});
function updateCardStyles() {
    if ($('#hutang').is(':checked')) {
        $('#card-hutang').css({'border': '2px solid #ccc', 'background-color': '#f8f9fa'});
        $('#card-tunai').css({'border': '', 'background-color': ''});
        nominalBayar.set(0);
        nominalKembalian.set(nominalBayarKonfirmasi.getNumber() * -1);
        $('#pembayaran_tunai').hide();
        $('#select2_metode_pembayaran')[0].selectedIndex = 0;
    } else if ($('#tunai').is(':checked')) {
        $('#card-tunai').css({'border': '2px solid #ccc', 'background-color': '#f8f9fa'});
        $('#card-hutang').css({'border': '', 'background-color': ''});
        $('#pembayaran_tunai').show();
        $('.transaksi_transfer').hide();
        $('#nominal_bayar').focus();
    }
}
$('#select2_metode_pembayaran').on('change', function(){
    if ($('#select2_metode_pembayaran').val() == 1) {
        $('.transaksi_transfer').show();
        $('.transaksi_tunai').hide();
        nominalBayar.set(0);
        nominalKembalian.set(nominalBayarKonfirmasi.getNumber() * -1);
    }else{
        $('.transaksi_transfer').hide();
        $('.transaksi_tunai').show();
        $('#nomor_transaksi_transfer').val('');
        $('#beneficiary_bank')[0].selectedIndex = 0;
    }
});
$('input[name="tipe_pembayaran"]').on('change', updateCardStyles);
function konfirmasi_transaksi_tindakan_mcu(){
    if ($("#pencarian_member_mcu").val() == null) {
        return createToast('Kesalahan Tranaksi', 'top-right', 'Tentukan pasien terlebih dahulu sebelum melanjutkan transaksi tindakan.', 'error', 3000);
    }
    if ($("#waktu_transaksi_mcu").val() == '' || $("#waktu_transaksi_sample_mcu").val() == '') {
        return createToast('Kesalahan Tranaksi', 'top-right', 'Waktu transaksi tidak ditemukan. Silahakan tentukan waktu transaksi MCU dan waktu transaksi sample MCU terlebih dahulu agar transaksi terebut valid.', 'error', 3000);
    }
    if (choicesDokterBertugas.getValue() == null || choicesPenanggungJawab.getValue() == null) {
        return createToast('Kesalahan Tranaksi', 'top-right', 'Tentukan dokter bertugas serta penanggung jawab lab terlebih dahulu sebelum melanjutkan transaksi tindakan.', 'error', 3000);
    }
    if ($("#nomor_transaksi_mcu_generate").val() == '') {
        return createToast('Kesalahan Tranaksi', 'top-right', 'Nomor transaksi MCU tidak ditemukan. Silahakan buat No Transaksi terlebih dahulu atau buat secara otomatis dengan klik tombol <i class="fa fa-refresh fa-spin"></i> Generate', 'error', 3000);
    }
    if (data_table_tindakan.rows().count() == 0) {
        return createToast('Kesalahan Tranaksi', 'top-right', 'Keranjang tindakan MCU kosong, silahkan cek kembali data transaksi tindakan. Tambahkan data tindakan MCU terlebih dahulu minimal 1 data.', 'error', 3000);
    }
    if (generate_total_harga_tindakan_mcu_autoNumeric.get() <= 0) {
        return createToast('Kesalahan Tranaksi', 'top-right', 'Total harga harus lebih dari 0 untuk melanjukan transaksi tindakan. Silahkan cek kembali data transaksi tindakan. Tambahkan data tindakan terlebih dahulu minimal 1 data.', 'error', 3000);
    }
    nominalBayarKonfirmasi.set(generate_total_harga_tindakan_mcu_autoNumeric.getNumber());
    updateCardStyles();
    $("#modalKonfimasiPendaftaran").modal("show");
    if (is_edit_transaksi && $("#select2_metode_pembayaran").val() == 1) {
        $('.transaksi_transfer').show();
    }
}
function getKeranjangTindakan() {
    let keranjangTindakan = [];
    data_table_tindakan.rows().every(function (rowIdx, tableLoop, rowLoop) {
        let data = this.data();
        let id_index = data[1]+"_"+(rowIdx+1);
        let id_item = data[14];
        let kodeItem = data[1];
        let namaItem = data[2];
        let nilai_tindakan = $(`#nilai_tindakan_${id_index}`).val();
        let hargaJual = AutoNumeric.getAutoNumericElement($(this.node()).find('#harga_jual_' + id_index)[0]).get();
        let diskon = AutoNumeric.getAutoNumericElement($(this.node()).find('#diskon_' + id_index)[0]).get();
        let hargaSetelahDiskon = AutoNumeric.getAutoNumericElement($(this.node()).find('#harga_setelah_diskon_' + id_index)[0]).get();
        let jumlah = AutoNumeric.getAutoNumericElement($(this.node()).find('#jumlah_' + id_index)[0]).get();
        let metaKuantitatif = $(`#meta_data_kuantitatif_${id_index}`).val();
        let metaKualitatif = $(`#meta_data_kualitatif_${id_index}`).val();
        let metaJasa = $(`#meta_data_jasa_${id_index}`).val();
        let metaJasaFee = $(`#meta_data_jasa_fee_${id_index}`).val();
        keranjangTindakan.push({
            id_item: id_item,
            kode_item: kodeItem,
            nama_item: namaItem,
            nilai_tindakan:nilai_tindakan,
            harga: hargaJual,
            diskon: diskon,
            harga_setelah_diskon: hargaSetelahDiskon,
            jumlah: jumlah,
            keterangan: "",
            meta_kuantitatif: metaKuantitatif,
            meta_kualitatif: metaKualitatif,
            meta_jasa: metaJasa,
            meta_jasa_fee: metaJasaFee,
        });
    });
    return keranjangTindakan;
}
function simpan_konfirmasi(){
    $("#btnSimpanPendaftaran").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses Simpan Data...');
    let jenis_transaksi = $('input[name="tipe_pembayaran"]:checked').val();
    let metode_pembayaran = "";
    let keranjangTindakan = getKeranjangTindakan();
    if (jenis_transaksi == 1) {
        if ($('#select2_metode_pembayaran').val() == 0) {
            jenis_transaksi = 1;
        }else{
            jenis_transaksi = 2;
            metode_pembayaran = $('#beneficiary_bank').val()+"|"+$('#beneficiary_bank option:selected').text()+"|"+$('#nomor_transaksi_transfer').val();
        }
    }
    $.get('/generate-csrf-token', function(response){
        $.ajax({
            url: baseurlapi + '/laboratorium/simpan_tindakan',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            method: 'POST', 
            dataType: 'json',
            contentType: false,
            processData: false,
            data: (function() {
                /* parameter transkasi */
                const formData = new FormData();
                formData.append('_token', response.csrf_token);
                formData.append('is_edit_transaksi', is_edit_transaksi);
                formData.append('id_transaksi', id_transaksi);
                formData.append('no_mcu', $('#id_transaksi_mcu').html());
                formData.append('no_nota', $('#nomor_transaksi_mcu_generate').val());
                formData.append('waktu_trx', $('#waktu_transaksi_mcu').val());
                formData.append('waktu_trx_sample', $('#waktu_transaksi_sample_mcu').val());
                formData.append('id_dokter', choicesDokterBertugas.getValue().value);
                formData.append('nama_dokter', choicesDokterBertugas.getValue().label);
                formData.append('id_pj', choicesPenanggungJawab.getValue().value);
                formData.append('nama_pj', choicesPenanggungJawab.getValue().label);
                formData.append('total_bayar', nominalBayar.getNumber());
                formData.append('total_transaksi', nominalBayarKonfirmasi.getNumber());
                formData.append('total_tindakan', data_table_tindakan.rows().count());   
                formData.append('jenis_transaksi', jenis_transaksi);
                formData.append('metode_pembayaran', metode_pembayaran);
                formData.append('status_pembayaran', 'process');
                const fileInput = $('#surat_pengantar')[0];
                if (fileInput.files[0]) {
                    formData.append('nama_file_surat_pengantar', fileInput.files[0]);
                }
                formData.append('is_paket_mcu', is_paket_mcu);
                formData.append('nama_paket_mcu', nama_paket_mcu);
                /* paramter transaksi detail */
                formData.append('keranjang_tindakan', JSON.stringify(keranjangTindakan));
                return formData;
            })(),
            success: function(response_data){
                if (response_data.rc == 200) {
                    createToast('Berhasil', 'top-right', response_data.message, 'success', 3000);
                    $("#btnSimpanPendaftaran").prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Data');
                    return Swal.fire({
                        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/bf2bdd2d-1dac-4285-aa3d-9548be13b15d/zzf9qF3Q23.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player>Informasi berhasil disimpan ke dalam sistem MCU Artha Medica. Silahkan tambah informasi detail MCU berdasarkan Nomor Indetitas yang sudah didaftarkan ['+$('#nomor_identitas').val()+']. Aksi apa yang ingin anda lakukan selanjutnya?<div>',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: 'orange',
                        confirmButtonText: 'Lihat Data Pasien',
                        cancelButtonText: 'Tambah Data Baru',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/laboratorium/daftar_tindakan';
                        }else{
                            window.location.reload();
                        }
                    });
                }else{
                    createToast('Kesalahan Cek Data', 'top-right', response_data.message, 'error', 3000);
                }
            },
            error: function(xhr, status, error){
                createToast('Kesalahan Cek Data', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
}
function hitungNominalBayar(){
    nominalKembalian.set((nominalBayarKonfirmasi.getNumber() - nominalBayar.getNumber()) * -1);
}
$('#nominal_bayar').on('input', function(){
    hitungNominalBayar();
});
$("#btnSimpanPendaftaran").on('click', function(){
    simpan_konfirmasi();
});
$("#floating_button_tambah_tindakan_mcu").on('click', function(){
    konfirmasi_transaksi_tindakan_mcu();
});