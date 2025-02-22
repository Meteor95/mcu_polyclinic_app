let table;
$(document).ready(function(){
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onload_datatables();
    onload_datatables_daftar_bahaya_riwayat_lingkungan_kerja();
    onloadfromnavigation(param_nomor_identitas, param_nama_peserta);
});
function onload_datatables(){
    table = $("#datatables_riwayat_lingkungan_kerja").DataTable({
        paging: false,
        ordering: false,
        searching: false,
        info: false,
        keys: true,
        "drawCallback": function(settings) {
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
$("#kotak_pencarian_daftar_bahaya_riwayat_lingkungan_kerja").on('keyup', debounce(function() {
    $("#datatables_daftar_bahaya_riwayat_lingkungan_kerja").DataTable().ajax.reload();
}, 300));
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
        $(this).val('');
    });
    $('#datatables_riwayat_lingkungan_kerja select').val('0'); 
}
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
                    parameter_pencarian: $("#kotak_pencarian_daftar_bahaya_riwayat_lingkungan_kerja").val(),
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
                            $('#datatables_daftar_bahaya_riwayat_lingkungan_kerja').DataTable().ajax.reload();    
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
function fill_form_riwayat_lingkungan_kerja(user_id, transaksi_id,detail = false){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pendaftaran/riwayatlingkungankerja',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                user_id: user_id,
                transaksi_id: transaksi_id
            },
            success: function(response) {
                if (!response.success){
                    return createToast('LKP MCU', 'top-right', response.message, 'error', 3000);
                }
                if (detail){
                    $('#datatables_riwayat_lingkungan_kerja_modal tbody').empty();
                    $("#modal_nama_peserta_parameter").text(response.data[0].nama_peserta);
                    for (let i = 0; i < response.data.length; i++) {
                        let item = response.data[i];
                        let row = $('#datatables_riwayat_lingkungan_kerja_modal tbody tr').filter(function() {
                            return $(this).find('td:eq(0)').text() == item.id_atribut_lk; // Adjust based on key
                        });
                        if (row.length === 0) {
                            $('#datatables_riwayat_lingkungan_kerja_modal tbody').append(`
                                <tr>
                                    <td>${item.nama_atribut_saat_ini}</td>
                                    <td>${item.status == 0 ? 'Tidak' : 'Ya'}</td>
                                    <td>${item.nilai_jam_per_hari}</td>
                                    <td>${item.nilai_selama_x_tahun}</td>
                                    <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                                </tr>
                            `);
                        }
                    }                    
                    $("#modalLihatParameter").modal('show');
                }else{
                    createToast('LKP MCU', 'top-right', response.message, 'success', 3000);
                    response.data.forEach(function(item) {
                        let row = $('#datatables_riwayat_lingkungan_kerja tbody tr').filter(function() {
                            return $(this).find('td:eq(0)').text() == item.id_atribut_lk;
                        });
                        if (response.data.length > 0) {
                            row.find('#status_' + item.id_atribut_lk).val(item.status).trigger('change');
                            const jamHariElement = row.find(`#jam_hari_${item.id_atribut_lk}`);
                            if (jamHariElement.data('autoNumeric')) {
                                jamHariElement.data('autoNumeric').set(item.nilai_jam_per_hari || 0);
                            } else {
                                jamHariElement.val(item.nilai_jam_per_hari);
                            }
                            const selamaTahunElement = row.find(`#selama_tahun_${item.id_atribut_lk}`);
                            if (selamaTahunElement.data('autoNumeric')) {
                                selamaTahunElement.data('autoNumeric').set(item.nilai_selama_x_tahun || 0);
                            } else {
                                selamaTahunElement.val(item.nilai_selama_x_tahun);
                            }
                            row.find('#keterangan_' + item.id_atribut_lk).val(item.keterangan ? item.keterangan : "");
                            row.find('td:eq(1)').text(item.nama_atribut_saat_ini);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
}
function fill_form_riwayat_lingkungan_kerja_tabel(user_id, transaksi_id, nomor_identitas, nama_peserta){
    let newOption = new Option('['+nomor_identitas+'] - '+nama_peserta, nomor_identitas, true, false);
    $("#pencarian_member_mcu").append(newOption).trigger('change');
    $("#pencarian_member_mcu").val(nomor_identitas).trigger('change');
    fill_form_riwayat_lingkungan_kerja(user_id, transaksi_id);
}

$("#btnCekDataIni").on('click', function(){
    if ($("#pencarian_member_mcu").val() == null){
        return createToast('Kesalahan Penyimpanan', 'top-right', 'Silahkan tentukan peserta MCU terlebih dahulu sebelum membaca data atas formulir bahaya riwayat lingkungan kerja tersebut. Silahkan tambahkan informasi bahaya paparan kerja terlebih dahulu', 'error', 3000);
    }
    fill_form_riwayat_lingkungan_kerja($("#user_id_temp").text(), $("#id_transaksi_mcu").text());
});
$("#bersihkan_data_riwayat_lingkungan_kerja").on('click', function(){
    $('#datatables_riwayat_lingkungan_kerja input').each(function() {
        if ($(this).data('autoNumeric')) {
            $(this).data('autoNumeric').set('');
        }
        $(this).val('');
    });
    $('#datatables_riwayat_lingkungan_kerja select').val('0'); 
});
function onload_datatables_daftar_bahaya_riwayat_lingkungan_kerja(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_daftar_bahaya_riwayat_lingkungan_kerja").DataTable({
            searching: false,
            bProcessing: true,
            serverSide: true,
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
                "url": baseurlapi + '/pendaftaran/daftarpasien_riwayatlingkungankerja',
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
                    title: "No",
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: "Informasi Peserta MCU",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Nomor MCU : ${row.no_transaksi}<br>
                            Nama Peserta : ${row.nama_peserta} (${row.umur}Th)<br>
                            Tanggal Transaksi : ${row.tanggal_transaksi}<br>
                            `;
                        }
                        return data;
                    }
                },
                {
                    title: "Informasi Pekerjaan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Nama Perusahaan : ${row.company_name}<br>
                            Departemen : ${row.nama_departemen}<br>
                            Alamat Perusahaan: ${row.alamat}<br>
                            `;
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: "dtfc-fixed-right_header",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-between gap-2">
                                <button onclick="detail_data_riwayat_lingkungan_kerja('${row.user_id}','${row.transaksi_id}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> ${row.jumlah_data} Parameter 
                                </button>
                                <button onclick="fill_form_riwayat_lingkungan_kerja_tabel('${row.user_id}','${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah Data
                                </button>
                                <button onclick="hapus_data_riwayat_lingkungan_kerja('${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}','${row.user_id}')" class="btn btn-danger w-100">
                                    <i class="fa fa-trash-o"></i> Hapus Data
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
function hapus_data_riwayat_lingkungan_kerja(transaksi_id,nomor_identitas,nama_peserta,user_id){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penghapusan LKP MCU</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi LPKP MCU dengan Nomor MCU <strong>'+nomor_identitas+'</strong> atas nama <strong>'+nama_peserta+'</strong>. Formulir ini bersifat wajib diisi oleh peserta MCU. Jadi silahkan isi kembali formulir tersebut jikalau dibutuhkan pada laporan MCU',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
   }).then((result) => {
    if (result.isConfirmed) {
        $.get('/generate-csrf-token', function(response) {
            $.ajax({
                url: baseurlapi + '/pendaftaran/hapusriwayatlingkungankerja',
                type: 'GET',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                data: {
                    _token: response.csrf_token,
                    transaksi_id: transaksi_id,
                    nomor_identitas: nomor_identitas,
                    nama_peserta: nama_peserta,
                    user_id: user_id
                },
                success: function(response) {
                    if (response.success){
                        clear_form();
                        $('#datatables_daftar_bahaya_riwayat_lingkungan_kerja').DataTable().ajax.reload();
                        return createToast('LKP MCU', 'top-right', response.message, 'success', 3000);
                    }
                },
                error: function(xhr, status, error) {
                    return createToast('Kesalahan Penyimpanan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                }
            });
        });
    }
   });
}
function detail_data_riwayat_lingkungan_kerja(user_id, transaksi_id){
    fill_form_riwayat_lingkungan_kerja(user_id, transaksi_id,true);
}
