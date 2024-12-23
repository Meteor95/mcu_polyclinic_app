let table;
$(document).ready(function(){
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onload_datatables();
    onload_datatables_daftar_kebiasaan_hidup();
});
function onload_datatables(){
    $("#kebiasaan_hidup_perempuan").hide();
    table = $("#datatables_riwayat_kebiasaan_hidup, #datatables_kebiasaan_hidup_perempuan").DataTable({
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
function onload_datatables_daftar_kebiasaan_hidup(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_daftar_kebiasaan_hidup").DataTable({
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
                "url": baseurlapi + '/pendaftaran/daftarpasien_riwayatkebiasaanhidup',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#cari_data_kebiasaan_hidup").val();
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
                                <button onclick="detail_data_riwayat_kebiasaan_hidup('${row.user_id}','${row.transaksi_id}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> Lihat Data
                                </button>
                                <button onclick="detail_data_riwayat_kebiasaan_hidup_tabel('${row.user_id}','${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah Data
                                </button>
                                <button onclick="hapus_data_riwayat_kebiasaan_hidup('${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}','${row.user_id}')" class="btn btn-danger w-100">
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
function applyAutoNumeric() {
    $('#datatables_riwayat_kebiasaan_hidup input[id^="nilai_kebiasaan"]').each(function() {
        if (!$(this).data('autoNumeric')) {
            let autoNumericInstance = new AutoNumeric(this, {  modifyValueOnUpDownArrow: false, modifyValueOnWheel: false, currencySymbol: '', decimalCharacter: ',', digitGroupSeparator: '.', minimumValue: 0, decimalPlaces: 0 });
            $(this).data('autoNumeric', autoNumericInstance);
        }
    });
    $('#datatables_kebiasaan_hidup_perempuan input[id^="waktu_kebiasaan"]').each(function() {
        flatpickr(this, {
            dateFormat: "d-m-Y",
            maxDate: moment().add(1, 'month').format('DD-MM-YYYY'),
        });
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
    $('#datatables_riwayat_kebiasaan_hidup input').each(function() {
        if ($(this).data('autoNumeric')) {
            $(this).data('autoNumeric').set('');
        }
        $(this).val('');
    });
    $('#datatables_kebiasaan_hidup_perempuan input').each(function() {
        $(this).val('');
    });
    $('#datatables_riwayat_kebiasaan_hidup select').val('0'); 
    $('#datatables_kebiasaan_hidup_perempuan select').val('0'); 
    $("#kebiasaan_hidup_perempuan").hide();
}
$("#bersihkan_data_riwayat_kebiasaan_hidup").on('click', function(){
    clear_form();
});
$("#cari_data_kebiasaan_hidup").on('keyup', debounce(function(){
    $("#datatables_daftar_kebiasaan_hidup").DataTable().ajax.reload();
}, 500));
$("#simpan_riwayat_kebiasaan_hidup").on('click', function(){
    if ($("#pencarian_member_mcu").val() == null){
        return createToast('Kesalahan Penyimpanan', 'top-right', 'Silahkan tentukan peserta MCU terlebih dahulu sebelum menyimpan data atas formulir bahaya riwayat lingkungan kerja diatas', 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Kebiasaan Hidup Pasien</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Kebiasaan Hidup Peserta MCU dengan atas nama : <strong>'+$("#nama_peserta_temp_1").text()+'</strong> pada nomor transaksi : <strong>'+$("#nomor_transaksi_temp").text()+'</strong> ?. Jika terjadi kesalahan silahakn ubah sesuai kebutuhan pada tabel dibawah ini',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            let bulkData = [], bulkDataPerempuan = [];
            $("#datatables_riwayat_kebiasaan_hidup").DataTable().rows().every(function() {
                let row = this.data();
                let rowId = row[0];
                let satuan_kebiasaan = row[4];
                let status = $(`#status_${rowId}`).val();
                let nilai_kebiasaan = $(`#nilai_kebiasaan_${rowId}`).data('autoNumeric');
                if (nilai_kebiasaan) {
                    nilai_kebiasaan = nilai_kebiasaan.getNumber();
                } else {
                    nilai_kebiasaan = 0;
                }
                let keterangan = $(`#keterangan_${rowId}`).val();
                let jenis_kebiasaan = 1;
                let rowData = {
                    user_id: $("#user_id_temp").text(),
                    transaksi_id: $("#id_transaksi_mcu").text(),
                    id_atribut_kb: rowId,
                    nama_kebiasaan:row[1],
                    status_kebiasaan:status,
                    nilai_kebiasaan:nilai_kebiasaan,
                    waktu_kebiasaan:"",
                    satuan_kebiasaan:satuan_kebiasaan,
                    jenis_kebiasaan:jenis_kebiasaan,
                    keterangan:keterangan
                };
                bulkData.push(rowData);
            });
            if ($("#kebiasaan_hidup_perempuan").is(":visible")){
                $("#datatables_kebiasaan_hidup_perempuan").DataTable().rows().every(function() {
                    let row = this.data();
                    let rowId = row[0];
                    let satuan_kebiasaan = row[4];
                    let status = $(`#status_${rowId}`).val();
                    let waktu_kebiasaan = $(`#waktu_kebiasaan_perempuan_${rowId}`).val();
                    let keterangan = $(`#keterangan_perempuan_${rowId}`).val();
                    let jenis_kebiasaan = 2;
                    let rowData = {
                        user_id: $("#user_id_temp").text(),
                        transaksi_id: $("#id_transaksi_mcu").text(),
                        id_atribut_kb: rowId,
                        nama_kebiasaan:row[1],
                        status_kebiasaan:status,
                        nilai_kebiasaan:0,
                        waktu_kebiasaan:waktu_kebiasaan,
                        satuan_kebiasaan:satuan_kebiasaan,
                        jenis_kebiasaan:jenis_kebiasaan,
                        keterangan:keterangan
                    };
                    bulkDataPerempuan.push(rowData);
                });   
            }
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/pendaftaran/simpankebiasaanhidup',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token : response.csrf_token,
                        data : bulkData,
                        data_perempuan : $("#kebiasaan_hidup_perempuan").is(":visible") ? bulkDataPerempuan : 0
                    },
                    success: function(response) {
                        clear_form()
                        $("#datatables_daftar_kebiasaan_hidup").DataTable().ajax.reload();
                        return createToast('Data berhasil disimpan', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
});
function hapus_data_riwayat_kebiasaan_hidup(transaksi_id, nomor_identitas, nama_peserta, user_id){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json"  background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Kebiasaan Hidup Pasien</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus formulir informasi Kebiasaan Hidup Peserta MCU dengan atas nama : <strong>'+nama_peserta+'</strong> pada nomor transaksi : <strong>'+nomor_identitas+'</strong> ?. Jika terjadi kesalahan silahakn ubah sesuai kebutuhan pada tabel dibawah ini',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/pendaftaran/hapusriwayatkebiasaanhidup',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token : response.csrf_token,
                        transaksi_id : transaksi_id,
                        nomor_identitas : nomor_identitas,
                        nama_peserta : nama_peserta,
                        user_id : user_id
                    },
                    success: function(response) {
                        clear_form()
                        $("#datatables_daftar_kebiasaan_hidup").DataTable().ajax.reload();
                        return createToast('Data berhasil dihapus', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penghapusan', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
}
function fill_form_riwayat_kebiasaan_hidup(user_id, transaksi_id, detail = false){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pendaftaran/riwayatkebiasaanhidup',
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
                    $('#datatables_riwayat_kebiasaan_hidup_modal tbody').empty();
                    $('#datatables_riwayat_kebiasaan_hidup_perempuan_modal tbody').empty();
                    $("#modal_nama_peserta_parameter").text(response.data[0].nama_peserta);
                    for (let i = 0; i < response.data.length; i++) {
                        let item = response.data[i];
                        let row = $('#datatables_riwayat_kebiasaan_hidup_modal tbody tr').filter(function() {
                            return $(this).find('td:eq(0)').text() == item.id_atribut_kb; // Adjust based on key
                        });
                        if (row.length === 0 && item.jenis_kebiasaan == 1) {
                            $('#datatables_riwayat_kebiasaan_hidup_modal tbody').append(`
                                <tr>
                                    <td>${item.nama_kebiasaan}</td>
                                    <td>${item.status_kebiasaan == 0 ? 'Tidak' : 'Ya'}</td>
                                    <td>${item.nilai_kebiasaan}</td>
                                    <td>${item.satuan_kebiasaan}</td>
                                    <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                                </tr>
                            `);
                        }
                        if (row.length === 0 && item.jenis_kebiasaan == 2) {
                            $('#datatables_riwayat_kebiasaan_hidup_perempuan_modal tbody').append(`
                                <tr>
                                    <td>${item.nama_kebiasaan}</td>
                                    <td>${item.status_kebiasaan == 0 ? 'Tidak' : 'Ya'}</td>
                                    <td>${moment(item.waktu_kebiasaan).format('DD-MM-YYYY HH:mm:ss')}</td>
                                    <td>${item.satuan_kebiasaan}</td>
                                    <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                                </tr>
                            `);
                        }
                    }                    
                    $("#modalLihatParameter").modal('show');
                }else{
                    createToast('LKP MCU', 'top-right', response.message, 'success', 3000);
                    response.data.forEach(function(item) {
                        let row = $('#datatables_riwayat_kebiasaan_hidup tbody tr').filter(function() {
                            return $(this).find('td:eq(0)').text() == item.id_atribut_kb;
                        });
                        let rowPerempuan = $('#datatables_kebiasaan_hidup_perempuan tbody tr').filter(function() {
                            return $(this).find('td:eq(0)').text() == item.id_atribut_kb;
                        });
                        if (response.data.length > 0 && item.jenis_kebiasaan == 1) {
                            row.find('#status_' + item.id_atribut_kb).val(item.status_kebiasaan).trigger('change');
                            const nilai_kebiasaan_element = row.find(`#nilai_kebiasaan_${item.id_atribut_kb}`);
                            if (nilai_kebiasaan_element.data('autoNumeric')) {
                                nilai_kebiasaan_element.data('autoNumeric').set(item.nilai_kebiasaan || 0);
                            } else {
                                nilai_kebiasaan_element.val(item.nilai_kebiasaan);
                            }
                            row.find('#keterangan_' + item.id_atribut_kb).val(item.keterangan ? item.keterangan : "");
                            row.find('td:eq(1)').text(item.nama_atribut_saat_ini);
                        }
                        if (response.data.length > 0 && item.jenis_kebiasaan == 2) {
                            rowPerempuan.find('#status_' + item.id_atribut_kb).val(item.status_kebiasaan).trigger('change');
                            rowPerempuan.find('#waktu_kebiasaan_perempuan_' + item.id_atribut_kb).val(item.waktu_kebiasaan);
                            rowPerempuan.find('#keterangan_perempuan_' + item.id_atribut_kb).val(item.keterangan ? item.keterangan : "");
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
function detail_data_riwayat_kebiasaan_hidup(user_id, transaksi_id){
    fill_form_riwayat_kebiasaan_hidup(user_id, transaksi_id,true);
}
function detail_data_riwayat_kebiasaan_hidup_tabel(user_id, transaksi_id, nomor_identitas, nama_peserta){
    let newOption = new Option('['+nomor_identitas+'] - '+nama_peserta, nomor_identitas, true, false);
    $("#pencarian_member_mcu").append(newOption).trigger('change');
    $("#pencarian_member_mcu").val(nomor_identitas).trigger('change');
    fill_form_riwayat_kebiasaan_hidup(user_id, transaksi_id);
}
