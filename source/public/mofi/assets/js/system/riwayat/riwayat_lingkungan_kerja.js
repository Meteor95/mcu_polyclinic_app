let table;
$(document).ready(function(){
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onload_datatables();
});
function onload_datatables(){
    table = $("#datatables_riwayat_lingkungan_kerja").DataTable({
        paging: false,
        ordering: false,
        searching: false,
        info: false,
        keys: true,
        "drawCallback": function(settings) {
            console.log('draw');
            applyAutoNumeric();
        },
    }).on('key-focus', function ( e, datatable, cell, originalEvent ) {
        $('input', cell.node()).focus();
    }).on("focus", "td input", function(){
        $(this).select();
    }) 
    table.on('key', function(e, dt, code) {
        if (code === 13) {
            table.keys.move('down');
        }
    });  
}
function applyAutoNumeric() {
    $('#datatables_riwayat_lingkungan_kerja input[id^="jam_hari"]').each(function() {
        if (!$(this).data('autoNumeric')) {
            let autoNumericInstance = new AutoNumeric(this, {  modifyValueOnUpDownArrow: false, modifyValueOnWheel: false, currencySymbol: '', decimalCharacter: ',', digitGroupSeparator: '.', minimumValue: 0, decimalPlaces: 0 });
            $(this).data('autoNumeric', autoNumericInstance);
        }
    });
    $('#datatables_riwayat_lingkungan_kerja input[id^="selama_tahun"]').each(function() {
        if (!$(this).data('autoNumeric')) {
            let autoNumericInstance = new AutoNumeric(this, {  modifyValueOnUpDownArrow: false, modifyValueOnWheel: false,currencySymbol: '', decimalCharacter: ',', digitGroupSeparator: '.', minimumValue: 0, decimalPlaces: 0 });
            $(this).data('autoNumeric', autoNumericInstance);
        }
    });
}
function clear_form(){
    $("#user_id_temp").text("");
    $("#nomor_identitas_temp").text("");
    $("#id_transaksi_mcu").text("");
    $("#nama_peserta_temp_1").text("");
    $("#nama_peserta_temp").text("");
    $("#no_telepon_temp").text("");
    $("#jenis_kelamin_temp").text("");
    $("#nomor_transaksi_temp").text("");
    $("#email_temp").text("");
    $("#tempat_lahir_temp").text("");
    $("#status_kawin_temp").text("");
    $("#pencarian_member_mcu").val(null).trigger('change'); 
    $('#datatables_riwayat_lingkungan_kerja input').each(function() {
        if ($(this).data('autoNumeric')) {
            $(this).data('autoNumeric').set('');
        }
    });
    $('#datatables_riwayat_lingkungan_kerja select').val('0'); 
}
$("#pencarian_member_mcu").on('change', function() {
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pendaftaran/getdatapasien',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token : response.csrf_token,
                nomor_identitas : $("#pencarian_member_mcu").val()
            },
            success: function(response) {
                if (!response.success) {
                    clear_form();
                    return createToast('Data tidak ditemukan', 'top-right', response.message, 'error', 3000);
                }
                $("#id_transaksi_mcu").text(response.data.id_transaksi);
                $("#user_id_temp").text(response.data.user_id);
                $("#nomor_identitas_temp").text(response.data.nomor_identitas);
                $("#nama_peserta_temp_1").text(response.data.nama_peserta);
                $("#nama_peserta_temp").text(response.data.nama_peserta);
                $("#no_telepon_temp").text(response.data.no_telepon);
                $("#jenis_kelamin_temp").text(response.data.jenis_kelamin);
                $("#nomor_transaksi_temp").text(response.data.no_transaksi);
                $("#email_temp").text(response.data.email);
                $("#tempat_lahir_temp").text(response.data.tempat_lahir);
                $("#status_kawin_temp").text(response.data.status_kawin);
            }
        });
    });
});

$("#simpan_riwayat_lingkungan_kerja").on('click', function(){
    if ($("#pencarian_member_mcu").val() == null){
        return createToast('Kesalahan Penyimpanan', 'top-right', 'Silahkan tentukan peserta MCU terlebih dahulu sebelum menyimpan data atas formulir bahaya riwayat lingkungan kerja diatas', 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Formulir LKP MCU</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Lingkungan Kerja Peserta MCU dengan atas nama : <strong>'+$("#nama_peserta_temp_1").text()+'</strong> ?. Jika terjadi kesalahan silahakn ubah sesuai kebutuhan',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            let bulkData = [];
            table.rows().every(function() {
                let row = this.data();
                let rowId = row[0];
                let status = $(`#status_${rowId}`).val();
                let jamHariBising = $(`#jam_hari_${rowId}`).data('autoNumeric');
                let selamaTahunBising = $(`#selama_tahun_${rowId}`).data('autoNumeric');
                if (jamHariBising) {
                    jamHariBising = jamHariBising.getNumber();
                } else {
                    jamHariBising = 0;
                }
                if (selamaTahunBising) {
                    selamaTahunBising = selamaTahunBising.getNumber();
                } else {
                    selamaTahunBising = 0;
                }
                let keteranganBising = $(`#keterangan_${rowId}`).val();
                let rowData = {
                    user_id: $("#user_id_temp").text(),
                    transaksi_id: $("#id_transaksi_mcu").text(),
                    id_atribut_lk: rowId,
                    nama_atribut_saat_ini:row[1],
                    status:status,
                    nilai_jam_per_hari:jamHariBising,
                    nilai_selama_x_tahun:selamaTahunBising,
                    keterangan:keteranganBising
                };
                bulkData.push(rowData);
            });
            $.get('/generate-csrf-token', function(response) {
                let data = {
                    _token: response.csrf_token,
                    informasi_riwayat_lingkungan_kerja: bulkData
                };
                $.ajax({
                    url: baseurlapi + '/pendaftaran/simpanriwayatlingkungankerja',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: data,
                    success: function(response) {
                       if (response.success){
                            clear_form();
                            return createToast('LKP MCU', 'top-right', response.message, 'success', 3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
});
$("#btnCekDataIni").on('click', function(){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pendaftaran/riwayatlingkungankerja',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                user_id: $("#user_id_temp").text()
            }
        });
    });
});