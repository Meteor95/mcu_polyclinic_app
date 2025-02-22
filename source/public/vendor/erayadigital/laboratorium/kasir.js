let daftar_table_tindakan_modal;
let id_transaksi_konfirmasi;
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
let nominalApotekKonfirmasi = new AutoNumeric('#nominal_apotek_konfirmasi', {  decimal: ',',
    digit: '.',
    allowDecimalPadding: false,
    minimumValue: '0',
    modifyValueOnUpDownArrow: false,
    modifyValueOnWheel: false,});
$(document).ready(function(){
    document.querySelectorAll('[data-state]').forEach(element => {
        element.classList.add('close_icon');
    });
    load_datatables_tindakan();
    daftar_table_tindakan_modal = initDataTable("#table_tindakan_lab_modal");
});
function initDataTable(selector, options = {}) {
    const defaultOptions = {
        searching: false,
        lengthChange: false,
        ordering: false,
        bFilter: false,
        scrollX: $(window).width() < 768 ? true : false,
        pagingType: "full_numbers",
        pageLength: 15,
        columnDefs: [{
            defaultContent: "-",
            targets: "_all"
        }],
        language: {
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "paginate": {
                "first": '<i class="fa fa-angle-double-left"></i>',
                "last": '<i class="fa fa-angle-double-right"></i>',
                "next": '<i class="fa fa-angle-right"></i>',
                "previous": '<i class="fa fa-angle-left"></i>',
            },
        },
    };
    return $(selector).DataTable($.extend(true, {}, defaultOptions, options));
}
function load_datatables_tindakan(){
    $.get('/generate-csrf-token', function(response) {
        $("#daftar_table_tindakan").DataTable({
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
                "url": baseurlapi + '/laboratorium/daftar_tindakan',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian').val();
                    d.status_pembayaran = $('#status_pembayaran').val();
                    d.jenis_layanan = $('#jenis_layanan').val();
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
                    title: "No Transaksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            let parts = row.no_nota.split('/');
                            let no_trx = parts.slice(0, 3).join('/');
                            let no_mcu = parts.slice(3).join('/');
                            let jenis_transaksi = '';
                            switch (row.jenis_transaksi) {
                                case 0:
                                    jenis_transaksi = 'Invoice';
                                    break;
                                case 1:
                                    jenis_transaksi = 'Tunai';
                                    break;
                                case 2:
                                    jenis_transaksi = 'Non Tunai';
                                    break;
                                default:
                                    jenis_transaksi = 'Unknown';
                                    break;
                            }
                            return "No Nota : " + no_trx + "<br>No MCU : " + no_mcu + "<br>Jenis Transaksi : <b>" + jenis_transaksi + "</b>";
                        }
                        return data;
                    }
                },
                {
                    title: "Entitas",
                    render: function(data, type, row, meta) {
                        return `Nama Pasien : ${row.nama_peserta}<br>
                        Nama Dokter : ${row.nama_dokter}<br>
                        Nama PJ : ${row.nama_pj}<br>
                        Operator : ${capitalizeFirstLetter(row.username)}
                        `
                    }
                },
                {
                    title: "Waktu Transaksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Trx : ${moment(row.waktu_trx).format('DD-MM-YYYY HH:mm:ss')}<br>Sample : ${moment(row.waktu_trx_sample).format('DD-MM-YYYY HH:mm:ss')}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Jenis Layanan",
                    render: function(data, type, row, meta) {
                        return `${row.jenis_layanan.replace('_',' ')}<br>Tindakan : ${row.total_tindakan.toLocaleString('id-ID')}<br><span class="badge ${row.status_pembayaran == 'done' ? 'badge-success' : row.status_pembayaran == 'pending' ? 'badge-warning' : 'badge-danger'}">${capitalizeFirstLetter(row.status_pembayaran)}</span>`;
                    }
                },
                {
                    title: "Total Transaksi",
                    className: "text-end",
                    render: function(data, type, row, meta) {
                        return `<span style="font-size: 2rem; font-weight: bold;">${(row.total_transaksi + row.nominal_apotek).toLocaleString('id-ID')}</span>`;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-center",
                    width: "230px",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-between gap-2">
                                <button onclick="detail_tindakan('${row.id_transaksi}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> Detail
                                </button>
                                <button onclick="konfirmasi_pembayaran('${row.id_transaksi}')" class="btn btn-primary w-100">
                                    <i class="fa fa-check"></i> Konfirmasi
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
function detail_tindakan(id,parts,nama_peserta){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laboratorium/detail_tindakan',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token:response.csrf_token,
                id_transaksi:id,
            },
            success: function(response) {
                let parts = response.transaksi[0].no_nota.split('/');
                let no_trx = parts.slice(0, 3).join('/');
                let no_mcu = parts.slice(3).join('/');
                $("#no_trx").html(no_trx);
                $("#total_pendapatan").html((response.transaksi[0].total_transaksi + response.transaksi[0].nominal_apotek).toLocaleString('id-ID'));
                $("#total_pendapatan_apotek_keterangan").html("Laboratorium : "+response.transaksi[0].total_transaksi.toLocaleString('id-ID')+"<br>Apotek : "+response.transaksi[0].nominal_apotek.toLocaleString('id-ID'));
                $("#no_mcu_label").html(no_mcu);
                $("#waktu_transaksi_label").html(moment(response.transaksi[0].waktu_trx).format('DD-MM-YYYY HH:mm:ss'));
                $("#waktu_sample_label").html(moment(response.transaksi[0].waktu_trx_sample).format('DD-MM-YYYY HH:mm:ss'));
                $("#dibuat_tanggal_label").html(moment(response.transaksi[0].created_at).format('DD-MM-YYYY HH:mm:ss'));
                $("#nama_dokter_label").html(response.transaksi[0].nama_dokter);
                $("#nama_penanggung_jawab_label").html(response.transaksi[0].nama_pj);
                nominalApotekKonfirmasi.set(response.transaksi[0].nominal_apotek);
                if (response.transaksi[0].is_paket_mcu == 1){
                    $("#nama_paket_label").html("Terhubung Paket MCU : "+response.transaksi[0].nama_paket_mcu);  
                }else if (response.transaksi[0].is_paket_mcu == 0 && response.transaksi[0].nama_paket_mcu == 0){
                    $("#nama_paket_label").html("Tidak Terhubung Paket MCU");
                }else{
                    $("#nama_paket_label").html("Template Tindakan : "+response.transaksi[0].nama_paket_mcu);
                }
                if (response.transaksi[0].nama_file_surat_pengantar == ""){
                    $("#surat_pengantaran_label").html("Tidak Ada Surat Pengantaran / Unggahan");
                }else{
                    $("#surat_pengantaran_label").html("<a href='"+baseurlapi+"/file/unduh_surat_pengantar?file_name="+response.transaksi[0].nama_file_surat_pengantar+"' target='_blank'><i class='fa fa-download'></i> Unduh Berkas</a>");
                }
                daftar_table_tindakan_modal.rows().clear().draw();
                response.transaksi.forEach((item, index) => {
                    daftar_table_tindakan_modal.row.add([
                        `<div class="text-center">${index + 1}</div>`,
                        item.kode_item,
                        item.nama_item,
                        `<div class="text-end">${item.harga.toLocaleString('id-ID')}</div>`,
                        `<div class="text-end">${item.diskon.toLocaleString('id-ID')}</div>`,
                        `<div class="text-end">${item.harga_setelah_diskon.toLocaleString('id-ID')}</div>`,
                        `<div class="text-end">${item.jumlah.toLocaleString('id-ID')}</div>`,
                        `<div class="text-end">${(item.harga_setelah_diskon * item.jumlah).toLocaleString('id-ID')}</div>`,
                    ]).draw();
                });
                $("#button_edit_transaksi").html(``);
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
            },
        });
    });
    $('#modalDetailTindakan').modal('show');
}
$("#data_ditampilkan, #status_pembayaran, #jenis_layanan").change(function(){
    $("#daftar_table_tindakan").DataTable().page.len($(this).val()).draw();
    $("#daftar_table_tindakan").DataTable().ajax.reload();
});
$("#kotak_pencarian").keyup(debounce(function(){
    $("#daftar_table_tindakan").DataTable().ajax.reload();
}, 300));
function konfirmasi_pembayaran(id_transaksi){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laboratorium/detail_tindakan',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token:response.csrf_token,
                id_transaksi:id_transaksi,
            },
            success: function(response) {
                id_transaksi_konfirmasi = id_transaksi;
                $("#status_pembayaran_terakhir").val(response.transaksi[0].status_pembayaran).trigger('change');
                nominalBayarKonfirmasi.set(response.transaksi[0].total_transaksi);
                const radioHutang = document.getElementById("hutang");
                const radioTunai = document.getElementById("tunai");
                if (response.transaksi[0].jenis_transaksi == 0) {
                    radioHutang.checked = true;
                    nominalBayar.set(0);
                    nominalKembalian.set(nominalBayarKonfirmasi.getNumber() * -1);
                    $('#pembayaran_tunai').hide();
                } else {
                    radioTunai.checked = true;
                    $('#pembayaran_tunai').show();
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
                
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
            },
        });
    });
    $('#modalKonfimasiPendaftaran').modal('show');
}
$('input[name="tipe_pembayaran"]').on('change', updateCardStyles);
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
$("#btnKonfirmasiPembayaran").click(function(){
    if(id_transaksi_konfirmasi == null || id_transaksi_konfirmasi == ""){
        return createToast('Kesalahan Penyimpanan', 'top-right', 'Id Transaksi tidak ditemukan. Silahkan tentukan transaksi mana yang ingin di konfirmasi status pembayarannya', 'error', 3000);
    }
    let jenis_transaksi_status = $('input[name="tipe_pembayaran"]:checked').val();
    let metode_pembayaran_status = "";
    if (jenis_transaksi_status == 1) {
        if ($('#select2_metode_pembayaran').val() == 0) {
            jenis_transaksi_status = 1;
        }else{
            jenis_transaksi_status = 2;
            metode_pembayaran_status = $('#beneficiary_bank').val()+"|"+$('#beneficiary_bank option:selected').text()+"|"+$('#nomor_transaksi_transfer').val();
        }
    }
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/transaksi/konfirmasi_pembayaran',
            type: 'POST',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token:response.csrf_token,
                id_transaksi:id_transaksi_konfirmasi,
                status_pembayaran:$("#status_pembayaran_terakhir").val(),
                total_bayar: nominalBayar.getNumber(),
                jenis_transaksi:jenis_transaksi_status,
                metode_pembayaran:metode_pembayaran_status,
            },
            success: function(response) {
                if (!response.success) {
                    return createToast('Kesalahan Konfirmasi Transaksi', 'top-right', response.message, 'error', 3000);
                }
                $("#daftar_table_tindakan").DataTable().ajax.reload();
                return createToast('Kesalahan Penyimpanan', 'top-right', response.message, 'success', 3000);
            },
            complete: function() {
                $('#modalKonfimasiPendaftaran').modal('hide');
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
            },
        });
    });
});
$('#nominal_bayar').on('input', function(){
    hitungNominalBayar();
});
function hitungNominalBayar(){
    nominalKembalian.set((nominalBayarKonfirmasi.getNumber() - nominalBayar.getNumber()) * -1);
}