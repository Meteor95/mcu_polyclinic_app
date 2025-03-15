const pemeriksaanConfig = [
    { id: 'riwayat_medis', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Riwayat Medis peserta ini' },
    { id: 'pemeriksaan_fisik', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Fisik peserta ini' },
    { id: 'pemeriksaan_laboratorium', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Laboratorium peserta ini' },
    { id: 'pemeriksaan_threadmill', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Threadmill peserta ini' },
    { id: 'pemeriksaan_rontgen_thorax', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Rontgen Thorax peserta ini' },
    { id: 'pemeriksaan_rontgen_lumbosacral', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Rontgen Lumbosacral peserta ini' },
    { id: 'pemeriksaan_usg_ubdomain', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan USG Ubdomain peserta ini' },
    { id: 'pemeriksaan_farmingham_score', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Farmingham Score peserta ini' },
    { id: 'pemeriksaan_ekg', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan EKG peserta ini' },
    { id: 'pemeriksaan_audiometri_kiri', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Audiometri Kiri peserta ini' },
    { id: 'pemeriksaan_audiometri_kanan', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Audiometri Kanan peserta ini' },
    { id: 'pemeriksaan_spirometri_restriksi', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Spirometri Restriksi peserta ini' },
    { id: 'pemeriksaan_spirometri_obstruksi', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Spirometri Obstruksi peserta ini' },
    { id: 'pemeriksaan_tindakan_saran', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Tindakan Saran peserta ini' }
];
const choicesConfig = [
    { id_quill: 'pemeriksaan_fisik', kondisi: 'pemeriksaan_fisik', id: 'pemeriksaan_fisik_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Fisik' },
    { id_quill: 'pemeriksaan_laboratorium', kondisi: 'pemeriksaan_laboratorium', id: 'pemeriksaan_laboratorium_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Laboratorium' },
    { id_quill: 'pemeriksaan_threadmill', kondisi: 'pemeriksaan_threadmill', id: 'pemeriksaan_threadmill_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Threadmill' },
    { id_quill: 'pemeriksaan_rontgen_thorax', kondisi: 'pemeriksaan_rontgen_thorax', id: 'pemeriksaan_rontgen_thorax_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Rontgen Thorax' },
    { id_quill: 'pemeriksaan_rontgen_lumbosacral', kondisi: 'pemeriksaan_rontgen_lumbosacral', id: 'pemeriksaan_rontgen_lumbosacral_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Rontgen Lumbosacral' },
    { id_quill: 'pemeriksaan_usg_ubdomain', kondisi: 'pemeriksaan_usg_ubdomain', id: 'pemeriksaan_usg_ubdomain_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan USG Ubdomain' },
    { id_quill: 'pemeriksaan_farmingham_score', kondisi: 'pemeriksaan_farmingham_score', id: 'pemeriksaan_farmingham_score_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Farmingham Score' },
    { id_quill: 'pemeriksaan_ekg', kondisi: 'pemeriksaan_ekg', id: 'pemeriksaan_ekg_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan EKG' },
    { id_quill: 'pemeriksaan_audiometri_kiri', kondisi: 'pemeriksaan_audiometri', id: 'pemeriksaan_audiometri_kiri_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Audiometri Kiri' },
    { id_quill: 'pemeriksaan_audiometri_kanan', kondisi: 'pemeriksaan_audiometri', id: 'pemeriksaan_audiometri_kanan_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Audiometri Kanan' },
    { id_quill: 'pemeriksaan_spirometri_restriksi', kondisi: 'pemeriksaan_spirometri', id: 'pemeriksaan_spirometri_restriksi_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Spirometri Restriksi' },
    { id_quill: 'pemeriksaan_spirometri_obstruksi', kondisi: 'pemeriksaan_spirometri', id: 'pemeriksaan_spirometri_obstruksi_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Spirometri Obstruksi' },
    { id_quill: 'pemeriksaan_restriksi', kondisi: 'pemeriksaan_restriksi', id: 'pemeriksaan_restriksi_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Restriksi' },
    { id_quill: 'pemeriksaan_obstruksi', kondisi: 'pemeriksaan_obstruksi', id: 'pemeriksaan_obstruksi_select', placeholder: 'Pilih Kesimpulan Untuk Pemeriksaan Obstruksi' },
];
let id_mcu_let = '', nomor_mcu_let = '';
let pemeriksaan_laboratorium_kondisi_select_id = document.getElementById('pemeriksaan_laboratorium_kondisi_select');
let pemeriksaan_kesimpulan_non_status_kesehatan_select_id = document.getElementById('pemeriksaan_kesimpulan_non_status_kesehatan_select');
let pemeriksaan_kesimpulan_tindakan_select_id = document.getElementById('pemeriksaan_kesimpulan_tindakan_select');
let choice_pemeriksaan_laboratorium_kondisi_select = new Choices(pemeriksaan_laboratorium_kondisi_select_id, {
    searchEnabled: true,
    shouldSort: false,
    placeholder: true,
    placeholderValue: 'Pilih Status Kesimpulan Laboratorium',
});
let choice_pemeriksaan_kesimpulan_non_status_kesehatan_select = new Choices(pemeriksaan_kesimpulan_non_status_kesehatan_select_id, {
    searchEnabled: true,
    shouldSort: false,
    placeholder: true,
    placeholderValue: 'Pilih Kesimpulan Kesehatan',
});
let choice_pemeriksaan_kesimpulan_tindakan_select = null;
let quillInstances = {};
$(document).ready(function() {
    pemeriksaanConfig.forEach(item => {
        quillInstances[item.id] = new Quill(`#${item.id}_quill`, {
            placeholder: item.placeholder,
            theme: 'snow'
        });
    });
    choicesConfig.forEach(item => {
        const element = document.getElementById(item.id);
        if (element) {
            load_select_tindakan_kesimpulan(item.kondisi, null, element, item.placeholder);
        }
    });
    loadDataPasien();
});
function aksi_onchange_tindakan_kesimpulan(kondisi, value, jenis_aksi) {
    const quillInstance = quillInstances[kondisi]; 
    if (quillInstance) {
        if (jenis_aksi == 'gantikan') {
            quillInstance.setText(value); 
        } else {
            quillInstance.insertText(
                quillInstance.getLength(),
                `${value}\n`,
                'list',
                'ordered'
            );
        }
    } else {
        console.error(`Instance Quill untuk kondisi "${kondisi}" tidak ditemukan.`);
    }
}
function load_select_tindakan_kesimpulan(kondisi, choice, id, placeholder) {
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laboratorium/tindakan_kesimpulan_pilihan',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                kondisi: kondisi,
            },
            success: function(response) {
                const hierarchicalData = buildHierarchy(response.data);
                id.innerHTML = '';
                if (choice) { choice.destroy(); }
                choice = new Choices(id, {
                    searchEnabled: true,
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: placeholder,
                    removeItemButton: true,
                });
                hierarchicalData.forEach(item => {
                    if (kondisi === 'pemeriksaan_kesimpulan_tindakan') {
                        choice.setChoices(
                            [{ value: item.id, label: item.status+" "+item.kategori+" ["+item.catatan+"]", selected: false }],
                            'value',
                            'label',
                            false
                        );
                    }else{
                        choice.setChoices(
                            [{ value: item.id, label: item.keterangan_kesimpulan, selected: false }],
                            'value',
                            'label',
                            false
                        );
                    }
                });
            },
        });
    });
}
function loadDataPasien() {
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_daftarpasien").DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            bFilter: false,
            bProcessing: true,
            serverSide: true,
            scrollX: $(window).width() < 768 ? true : false,
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
                "url": baseurlapi + '/pendaftaran/daftarpasien',
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
                    title: "Nomor MCU",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.no_transaksi}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Nama Peserta",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.nama_peserta} (${row.umur}Th)</span>`;
                        }
                        return data;
                    }
                },
                {
                    title: "Informasi MCU",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            let status_peserta = '';
                            if (row.status_peserta == 'proses') {
                                status_peserta = '<span class="badge bg-warning">Di Proses</span>';
                            }else if (row.status_peserta == 'dibatalkan') {
                                status_peserta = '<span class="badge bg-danger">Di Batalkan</span>';
                            }else if (row.status_peserta == 'selesai') {
                                status_peserta = '<span class="badge bg-success">Tervalidasi dan Selesai</span>';
                            }
                            return `Tanggal: ${row.tanggal_transaksi}<br>Status: ${status_peserta}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Informasi Perusahaan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Perusahaan: ${row.company_name}<br>Departemen: ${row.nama_departemen}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-between gap-2 background_fixed_right_row"><button onclick="validasi_rekap_kesimpulan('${row.no_transaksi}', '${row.nama_peserta}', '${row.id}')" class="btn btn-success w-100"><i class="fa fa-search"></i> Kesimpulan</button></div>`;
                        }       
                        return data;
                    }
                }
            ]
        });
    });
}
$("#kotak_pencarian_daftarpasien").on('keyup', debounce(function() {
    $("#datatables_daftarpasien").DataTable().ajax.reload();
}, 300));
function clear_pemeriksaan_kesimpulan() {
    $(".pemeriksaan_spirometri").hide();
    $(".pemeriksaan_ekg").hide();
    $(".pemeriksaan_threadmill").hide();
    $(".pemeriksaan_ronsen").hide();
    $(".pemeriksaan_audiometri").hide();
    quillInstances['riwayat_medis'].setText('');
    quillInstances['pemeriksaan_fisik'].setText('');
    quillInstances['pemeriksaan_laboratorium'].setText('');
    quillInstances['pemeriksaan_threadmill'].setText('');
    quillInstances['pemeriksaan_rontgen_thorax'].setText('');
    quillInstances['pemeriksaan_rontgen_lumbosacral'].setText('');
    quillInstances['pemeriksaan_usg_ubdomain'].setText('');
    quillInstances['pemeriksaan_farmingham_score'].setText('');
    quillInstances['pemeriksaan_ekg'].setText('');
    quillInstances['pemeriksaan_audiometri_kiri'].setText('');
    quillInstances['pemeriksaan_audiometri_kanan'].setText('');
    quillInstances['pemeriksaan_spirometri_restriksi'].setText('');
    quillInstances['pemeriksaan_spirometri_obstruksi'].setText('');
    quillInstances['pemeriksaan_tindakan_saran'].setText('');
}
function validasi_rekap_kesimpulan(no_transaksi, nama_peserta, id_mcu) {
    id_mcu_let = id_mcu;
    nomor_mcu_let = no_transaksi;
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laporan/validasi_rekap_kesimpulan',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                id_mcu_let: id_mcu_let,
                nomor_mcu_let: nomor_mcu_let,
            },
            success: function(response) {
                clear_pemeriksaan_kesimpulan();
                const hierarchicalData = response.data_kesimpulan_tindakan;
                pemeriksaan_kesimpulan_tindakan_select_id.innerHTML = '';
                hierarchicalData.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = `${category.kategori} - ${category.catatan}`;
                    pemeriksaan_kesimpulan_tindakan_select_id.appendChild(option);
                });
                if (choice_pemeriksaan_kesimpulan_tindakan_select) { choice_pemeriksaan_kesimpulan_tindakan_select.destroy(); }
                choice_pemeriksaan_kesimpulan_tindakan_select = new Choices(pemeriksaan_kesimpulan_tindakan_select_id, {
                    searchEnabled: true,
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: 'Silahkan Pilih Satuan',
                });
                if (response.data_poliklinik.count_poliklinik_spirometri > 0) {
                    $(".pemeriksaan_spirometri").show();
                }
                if (response.data_poliklinik.count_poliklinik_ekg > 0) {
                    $(".pemeriksaan_ekg").show();
                }
                if (response.data_poliklinik.count_poliklinik_threadmill > 0) {
                    $(".pemeriksaan_threadmill").show();
                }
                if (response.data_poliklinik.count_poliklinik_rontgen_thorax > 0) {
                    $(".pemeriksaan_rontgen_thorax").show();
                }
                if (response.data_poliklinik.count_poliklinik_rontgen_lumbosacral > 0) {
                    $(".pemeriksaan_rontgen_lumbosacral").show();
                }
                if (response.data_poliklinik.count_poliklinik_usg_ubdomain > 0) {
                    $(".pemeriksaan_usg_ubdomain").show();
                }
                if (response.data_poliklinik.count_poliklinik_farmingham_score > 0) {
                    $(".pemeriksaan_farmingham_score").show();
                }
                if (response.data_poliklinik.count_poliklinik_audiometri > 0) {
                    $(".pemeriksaan_audiometri").show();
                }
                if (response.data && Object.keys(response.data).length > 0) {
                    quillInstances['riwayat_medis'].setContents(JSON.parse(response.data.kesimpulan_riwayat_medis));
                    quillInstances['pemeriksaan_fisik'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_fisik));
                    choice_pemeriksaan_laboratorium_kondisi_select.setChoiceByValue(response.data.status_pemeriksaan_laboratorium);
                    quillInstances['pemeriksaan_laboratorium'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_laboratorum));
                    quillInstances['pemeriksaan_threadmill'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_threadmill));
                    quillInstances['pemeriksaan_rontgen_thorax'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_rontgen_thorax));
                    quillInstances['pemeriksaan_rontgen_lumbosacral'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_rontgen_lumbosacral));
                    quillInstances['pemeriksaan_usg_ubdomain'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_usg_ubdomain));
                    quillInstances['pemeriksaan_farmingham_score'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_farmingham_score));
                    quillInstances['pemeriksaan_ekg'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_ekg));
                    quillInstances['pemeriksaan_audiometri_kiri'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_audio_kiri));
                    quillInstances['pemeriksaan_audiometri_kanan'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_audio_kanan));
                    quillInstances['pemeriksaan_spirometri_restriksi'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_spiro_restriksi));
                    quillInstances['pemeriksaan_spirometri_obstruksi'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_spiro_obstruksi));
                    setTimeout(() => {
                        choice_pemeriksaan_kesimpulan_tindakan_select.setChoiceByValue(response.data.kesimpulan_keseluruhan.toString());
                    }, 100);
                    choice_pemeriksaan_kesimpulan_non_status_kesehatan_select.setChoiceByValue(response.data.kesimpulan_hasil_medical_checkup);
                    quillInstances['pemeriksaan_tindakan_saran'].setContents(JSON.parse(response.data.saran_keseluruhan));
                }
                $("#modal_validasi_rekap_kesimpulan_text").html('Validasi Kesimpulan Nama: '+nama_peserta+"<br>Nomor MCU: "+no_transaksi);
                $("#modal_validasi_rekap_kesimpulan").modal('show');
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Validasi Kesimpulan', 'top-right', error, 'error', 3000);
            }
        });
    });
}
$("#konfirmasi_validasi_rekap_kesimpulan").on('click', function() {
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Validasi Kesimpulan</h4><p class="text-muted mx-4 mb-0 text-center">Apakah anda yakin ingin validasi kesimpulan <strong>'+$("#modal_validasi_rekap_kesimpulan_text").html().replace('<br>', ' ')+'</strong>. Jika sudah benar silahkan klik tombol validasi agar dapat di validasi MCU oleh admin</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Validasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laporan/validasi_rekap_kesimpulan',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        id_mcu_let: id_mcu_let,
                        nomor_mcu_let: nomor_mcu_let,
                        hasil_kesimpulan_riwayat_medis: JSON.stringify(quillInstances['riwayat_medis'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_fisik: JSON.stringify(quillInstances['pemeriksaan_fisik'].getContents().ops),
                        status_pemeriksaan_laboratorium: $("#pemeriksaan_laboratorium_kondisi_select").val(),
                        hasil_kesimpulan_pemeriksaan_laboratorium: JSON.stringify(quillInstances['pemeriksaan_laboratorium'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_threadmill: JSON.stringify(quillInstances['pemeriksaan_threadmill'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_rontgen_thorax: JSON.stringify(quillInstances['pemeriksaan_rontgen_thorax'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_rontgen_lumbosacral: JSON.stringify(quillInstances['pemeriksaan_rontgen_lumbosacral'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_usg_ubdomain: JSON.stringify(quillInstances['pemeriksaan_usg_ubdomain'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_farmingham_score: JSON.stringify(quillInstances['pemeriksaan_farmingham_score'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_ekg: JSON.stringify(quillInstances['pemeriksaan_ekg'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_audio_kiri: JSON.stringify(quillInstances['pemeriksaan_audiometri_kiri'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_audio_kanan: JSON.stringify(quillInstances['pemeriksaan_audiometri_kanan'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_spirometri_restriksi: JSON.stringify(quillInstances['pemeriksaan_spirometri_restriksi'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_spirometri_obstruksi: JSON.stringify(quillInstances['pemeriksaan_spirometri_obstruksi'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_kesimpulan_tindakan: $("#pemeriksaan_kesimpulan_tindakan_select").val(),
                        kesimpulan_hasil_medical_checkup: $("#pemeriksaan_kesimpulan_non_status_kesehatan_select").val(),
                        hasil_kesimpulan_pemeriksaan_tindakan_saran: JSON.stringify(quillInstances['pemeriksaan_tindakan_saran'].getContents().ops),
                    },
                    success: function(response) {
                        createToast('Berhasil Validasi Kesimpulan', 'top-right', response.message, 'success', 3000);
                        $("#modal_validasi_rekap_kesimpulan").modal('hide');
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Validasi Kesimpulan', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
});