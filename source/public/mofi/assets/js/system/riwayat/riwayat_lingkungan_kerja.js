let table;
$(document).ready(function(){
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onloaddatatables();
});
function onloaddatatables(){
    $.get('/generate-csrf-token', function(response) {
        table = $("#datatables_riwayat_lingkungan_kerja").DataTable({
            dom: 'lfrtip',
            searching: false,
            lengthChange: false,
            ordering: false,
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            scrollCollapse: true,
            bFilter: false,
            bInfo: false,
            ordering: false,
            scrollX: true,
            bPaginate: false,
            bProcessing: true,
            serverSide: true,
            ajax: {
                "url": baseurlapi + '/atribut/lingkungankerja',
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
            pagingType: "full_numbers",
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            columns: [
                {
                    title: "ID",
                    render: function(data, type, row, meta) {
                        return `${row.id}`
                    }
                },
                {
                    title: "Paparan Kerja",
                    render: function(data, type, row, meta) {
                        return `${row.nama_atribut_lk}`
                    }
                },
                {
                    title: "Status",
                    render: function(data, type, row, meta) {
                        return `<select class="form-select"><option value="1" selected>Ya</option><option value="0" selected>Tidak</option></select>`
                    }
                },
                {
                    title: "Jam / Hari",
                    render: function(data, type, row, meta) {
                        return `<input type="text" class="form-control" placeholder="Harus Angka" id="jam_hari_bising_${row.id}">`
                    }
                },
                {
                    title: "Selama X Tahun",
                    render: function(data, type, row, meta) {
                        return `<input type="text" class="form-control" placeholder="Harus Angka" id="selama_tahun_bising_${row.id}">`
                    }
                },
                {
                    title: "Keterangan",
                    render: function(data, type, row, meta) {
                        return `<textarea rows=1 class="form-control" id="keterangan_bising_${row.id}"></textarea>`
                    }
                }

            ]
        }).on('key-focus', function ( e, datatable, cell, originalEvent ) {
            $('input', cell.node()).focus();
        }).on("focus", "td input", function(){
            $(this).select();
        });
        table.on('key', function (e, dt, code) {
            if (code === 13) {
              table.keys.move('down');
            }
        })
        table.on('draw', function() {
            $('#datatables_riwayat_lingkungan_kerja input[id^="jam_hari_bising_"]').each(function() {
                new AutoNumeric(this, { currencySymbol: '', decimalCharacter: ',', digitGroupSeparator: '.', minimumValue: 0});
            });

            $('#datatables_riwayat_lingkungan_kerja input[id^="selama_tahun_bising_"]').each(function() {
                new AutoNumeric(this, { currencySymbol: '', decimalCharacter: ',', digitGroupSeparator: '.', minimumValue: 0 });
            });
        });
    });
}
function clear_form(){
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
                let rowId = row.id;
                let status = $(`#status_bising_${rowId}`).val();
                let jamHariBising = new AutoNumeric(`#jam_hari_bising_${rowId}`).getNumber();
                let selamaTahunBising = new AutoNumeric(`#selama_tahun_bising_${rowId}`).getNumber();
                let keteranganBising = $(`#keterangan_bising_${rowId}`).val();
                let rowData = {
                    user_id: $("#pencarian_member_mcu").val(),
                    transaksi_id: $("#id_transaksi_mcu").text(),
                    id_atribut_lk: rowId,
                    nama_atribut_saat_ini:row.nama_atribut_lk,
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
                       
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
});