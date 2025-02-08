let daftar_table_tindakan_modal, daftar_table_fee_modal,daftar_table_berkas_apotek;
let nominal_apotek = new AutoNumeric('#nominal_apotek', {
    decimal: '.',
    digit: ',',
    allowDecimalPadding: false,
    minimumValue: '0',
    modifyValueOnUpDownArrow: false,
    modifyValueOnWheel: false,
});
$(document).ready(function(){
    document.querySelectorAll('[data-state]').forEach(element => {
        element.classList.add('close_icon');
    });
    load_datatables_tindakan();
    daftar_table_tindakan_modal = initDataTable("#table_tindakan_lab_modal");
    daftar_table_fee_modal = initDataTable("#table_fee_lab_modal");
    daftar_table_berkas_apotek = initDataTable("#daftar_table_berkas_apotek");
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
                    title: "Waktu Transaksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Trx : ${moment(row.waktu_trx).format('DD-MM-YYYY HH:mm:ss')}<br>Sample : ${moment(row.waktu_trx_sample).format('DD-MM-YYYY HH:mm:ss')}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Total Transaksi",
                    render: function(data, type, row, meta) {
                        return `Pendapatan : ${row.total_transaksi.toLocaleString('id-ID')}<br>Tindakan : ${row.total_tindakan.toLocaleString('id-ID')}<br>Apotek : ${row.nominal_apotek.toLocaleString('id-ID')}`;
                    }
                },
                {
                    title: "Jenis Layanan",
                    render: function(data, type, row, meta) {
                        return `${row.jenis_layanan.replace('_',' ')}<br><span class="badge badge-primary">${capitalizeFirstLetter(row.status_pembayaran)}</span>`;
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
                    title: "Aksi",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            let parts = row.no_nota.split('/');
                            return `<div class="d-flex justify-content-between gap-2">
                                <button onclick="detail_tindakan('${row.id_transaksi}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> Detail
                                </button>
                                <button onclick="hapus_tindakan('${row.id_transaksi}','${btoa(parts)}','${row.nama_peserta}')" class="btn btn-danger w-100">
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
function hapus_tindakan(id,parts,nama_peserta){
    Swal.fire({ 
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Informasi Transaksi Tindakan</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi transaksi tindakan dengan atas nama : <strong>' + nama_peserta + '</strong> dengan Nota <strong>' + atob(parts).split(',').slice(0, 3).join('/') + '</strong> pada MCU <strong>' + atob(parts).split(',').slice(3).join('/') + '</strong></p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laboratorium/hapus_tindakan',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token:response.csrf_token,
                        id_transaksi:id,
                        nama_peserta:nama_peserta,
                        no_nota:atob(parts).split(',').slice(0, 3).join('/'),
                        no_mcu:atob(parts).split(',').slice(3).join('/'),
                    },
                    success: function(response) {
                        if (response.success == false){
                            return createToast('Kesalahan Penyimpanan', 'top-right', response.message, 'error', 3000);
                        }
                        $("#daftar_table_tindakan").DataTable().ajax.reload();
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
                let detail_transaksi_code = encodeURIComponent(btoa(response.transaksi[0].id_transaksi+'|'+response.transaksi[0].no_mcu+'|'+response.transaksi[0].nomor_identitas+'|'+response.transaksi[0].nama_peserta));
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
                nominal_apotek.set(response.transaksi[0].nominal_apotek);
                daftar_table_tindakan_modal.rows().clear().draw();
                daftar_table_fee_modal.rows().clear().draw();
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
                response.transaksi_fee.forEach((item, index) => {
                    daftar_table_fee_modal.row.add([
                        index + 1,
                        item.nama_tindakan,
                        item.kode_jasa,
                        item.nama_petugas,
                        `<div class="text-end">${item.nominal_fee.toLocaleString('id-ID')}</div>`,
                        `<div class="text-end">${item.besaran_fee.toLocaleString('id-ID')}</div>`,
                    ]).draw();
                });
                $("#button_edit_apotek").html(`<button onclick="ubah_data_apotek('${response.transaksi[0].id_transaksi}','${no_trx}')" class="btn btn-amc-orange"><i class="fa fa-edit"></i> Ubah Data Apotek</button>`);
                $("#button_edit_transaksi").html(`<a href="/laboratorium/tindakan?paramter_tindakan=${detail_transaksi_code}" target="_blank" class="btn btn-amc-orange"><i class="fa fa-edit"></i> Ubah Data Transaksi</a>`);
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
            },
        });
    });
    $('#modalDetailTindakan').modal('show');
}
function ubah_data_apotek(id, nomor_trx){
    $('#modalUbahDataApotek').modal('show');
    setTimeout(() => {
        $("#nominal_apotek").focus();
    }, 500);
    $("#id_transaksi_apotek").html(id);
    $("#nomor_trx_apotek").html(nomor_trx);
    daftar_table_berkas_apotek.rows().clear().draw();
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laboratorium/ubah_data_apotek',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token:response.csrf_token,
                id_transaksi:id,
            },
            success: function(response) {
                response.data.forEach((item, index) => {
                    daftar_table_berkas_apotek.row.add([
                        `<div class="text-center">${index + 1}</div>`,
                        item.nama_file,
                        `<div class="text-center">${item.ekstensi}</div>`,
                        item.keterangan == "" ? "-" : item.keterangan,
                        `<div class="d-flex justify-content-center gap-2">
                            <a class="btn btn-amc-orange w-100" href="${item.data_foto}" target="_blank">
                                <i class="fa fa-download"></i> Unduh Berkas
                            </a>
                            <a class="btn btn-danger w-100" href="javascript:void(0)" onclick="hapus_berkas_apotek('${item.id}','${nomor_trx}','${btoa(item.nama_file)}')">
                                <i class="fa fa-trash-o"></i> Hapus
                            </a>
                        </div>`,
                    ]).draw();
                });
                $('#modalDetailTindakan').modal('show');
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
            },
        });
    });
}
$("#data_ditampilkan, #status_pembayaran, #jenis_layanan").change(function(){
    $("#daftar_table_tindakan").DataTable().page.len($(this).val()).draw();
    $("#daftar_table_tindakan").DataTable().ajax.reload();
});
$("#kotak_pencarian").keyup(debounce(function(){
    $("#daftar_table_tindakan").DataTable().ajax.reload();
}, 300));
function hapus_berkas_apotek(id,no_trx,nama_file){
    Swal.fire({ 
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Berkas Apotek</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus berkas apotek dengan dengan untuk refrensi nomor transaksi : <strong>' + no_trx + '</strong> dengan Nota <strong>' + atob(nama_file) + '</strong></p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Berkas',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laboratorium/hapus_berkas_apotek',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token:response.csrf_token,
                        id_transaksi:id,
                        nomor_trx:no_trx,
                        nama_file:nama_file,
                    },
                    success: function(response) {
                        if (response.success == false){
                            return createToast('Kesalahan Penyimpanan', 'top-right', response.message, 'error', 3000);
                        }
                        $("#modalUbahDataApotek").modal('hide');
                        return createToast('Berkas Apotek Telah Terhapus', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
}
$("#simpan_data_apotek").click(function(){
    $.get('/generate-csrf-token', function(response) {
        let formData = new FormData();
        formData.append("_token", response.csrf_token);
        formData.append("transaksi_id", $("#id_transaksi_apotek").html());
        formData.append("nomor_trx", $("#nomor_trx_apotek").html());
        formData.append("is_add_transaksi", true);
        formData.append("nominal_apotek", nominal_apotek.get());
        dropzoneunggahberkas.files.forEach((file, index) => {
            if (file instanceof File) {
                formData.append(`fileUploadApotek[]`, file);
            }
            let inputKeterangan = document.querySelector(`[name="keterangan_${file.upload.uuid}"]`);
            if (inputKeterangan) {
                formData.append(`keterangan[]`, inputKeterangan.value);
            } else {
                formData.append(`keterangan[]`, "");
            }
        });
        $.ajax({
            url: baseurlapi + '/laboratorium/ubah_data_apotek',
            type: 'POST',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                createToast('Sukses', 'top-right', response.message, 'success', 3000);
                dropzoneunggahberkas.removeAllFiles();
                $("#daftar_table_tindakan").DataTable().ajax.reload();
                $("#total_pendapatan").html((response.updatetransaksi.total_transaksi + response.updatetransaksi.nominal_apotek).toLocaleString('id-ID'));
                $("#total_pendapatan_apotek_keterangan").html("Laboratorium : "+response.updatetransaksi.total_transaksi.toLocaleString('id-ID')+"<br>Apotek : "+response.updatetransaksi.nominal_apotek.toLocaleString('id-ID'));
                $('#modalUbahDataApotek').modal('hide');
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
            },
        });
    });
});

Dropzone.autoDiscover = false;
let dropzoneunggahberkas = new Dropzone("#singleFileUploadApotek", {
    url: 'kesurga',
    paramName: "fileUploadApotek",
    maxFiles: 3,
    maxFilesize: 1024,
    multiple: true,
    acceptedFiles: "image/png, image/jpeg, application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    autoProcessQueue: false, // Tetap manual
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token_ajax')
    },
    accept: function (file, done) {
        let preview = file.previewElement.querySelector(".dz-image img");
        if (file.type === "application/pdf") {
            preview.src = "https://www.svgrepo.com/show/144578/pdf.svg";
        } else if (file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
            preview.src = "https://www.svgrepo.com/show/374188/word2.svg";
        }
        done();
    },
});

dropzoneunggahberkas.on("maxfilesexceeded", function (file) {
    this.removeFile(file);
    createToast('File melebihi batas maksimal', 'top-right', 'Batas file maksimal hanya ' + this.options.maxFiles+' dalam 1 proses. Lakukan proses penyimpanan terlebih dahulu kemudian tambahkan file lainnya', 'success', 3000);
});

dropzoneunggahberkas.on("addedfile", function (file) {
    let previewElement = file.previewElement;
    let defaultFileName = previewElement.querySelector(".dz-filename");
    if (defaultFileName) {
        defaultFileName.style.display = "none";
    }
    let fileName = document.createElement("span");
    fileName.className = "dz-filename text-center";
    fileName.style.width = "100%";
    fileName.style.textAlign = "center";
    fileName.style.fontWeight = "bold";
    fileName.style.marginTop = "5px";
    fileName.style.whiteSpace = "nowrap";
    fileName.style.overflow = "hidden";
    fileName.style.textOverflow = "ellipsis";
    fileName.textContent = file.name;

    let textInput = document.createElement("input");
    textInput.type = "text";
    textInput.className = "form-control mt-2";
    textInput.placeholder = "Masukkan keterangan file...";
    textInput.style.zIndex = "1000";
    textInput.name = "keterangan_" + file.upload.uuid;

    let removeButton = document.createElement("button");
    removeButton.textContent = "Batalkan File Ini";
    removeButton.style.zIndex = "1000";
    removeButton.className = "btn btn-danger w-100 mt-2";
    removeButton.style.cursor = "pointer";
    
    removeButton.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropzoneunggahberkas.removeFile(file);
    });

    previewElement.style.display = "flex";
    previewElement.style.flexDirection = "column";
    previewElement.style.alignItems = "center";
    previewElement.style.justifyContent = "center";
    previewElement.style.marginRight = "10px"; // Jarak antar elemen

    // Tambahkan elemen ke dalam preview file
    previewElement.appendChild(fileName);
    previewElement.appendChild(textInput);
    previewElement.appendChild(removeButton);
});