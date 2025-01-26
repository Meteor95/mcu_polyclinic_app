let id_mcu_let = '', nomor_mcu_let = '';
$(document).ready(function() {
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
                            return `<div class="d-flex justify-content-between gap-2"><button onclick="lihat_berkas_mcu('${row.no_transaksi}', '${row.nama_peserta}', '${row.id}')" class="btn btn-danger w-100"><i class="fa fa-print"></i> Lihat Berkas</button></div>`;
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

function lihat_berkas_mcu(no_transaksi, nama_peserta, id_mcu) {
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
                console.log(response.data_poliklinik)
            },
            complete: function(xhr, status) {
                $("#modal_lihat_berkas_mcu").modal('show');
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
            console.log(id_mcu_let);
            console.log(nomor_mcu_let);
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laporan/validasi_rekap_kesimpulan',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        id_mcu_let: id_mcu_let,
                        nomor_mcu_let: nomor_mcu_let,
                        hasil_kesimpulan_pemeriksaan_fisik: JSON.stringify(quillInstances['pemeriksaan_fisik'].getContents().ops),
                        status_pemeriksaan_laboratorium: $("#pemeriksaan_laboratorium_kondisi_select").val(),
                        hasil_kesimpulan_pemeriksaan_laboratorium: JSON.stringify(quillInstances['pemeriksaan_laboratorium'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_threadmill: JSON.stringify(quillInstances['pemeriksaan_threadmill'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_ronsen: JSON.stringify(quillInstances['pemeriksaan_ronsen'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_ekg: JSON.stringify(quillInstances['pemeriksaan_ekg'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_audio_kiri: JSON.stringify(quillInstances['pemeriksaan_audiometri_kiri'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_audio_kanan: JSON.stringify(quillInstances['pemeriksaan_audiometri_kanan'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_spirometri_restriksi: JSON.stringify(quillInstances['pemeriksaan_spirometri_restriksi'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_spirometri_obstruksi: JSON.stringify(quillInstances['pemeriksaan_spirometri_obstruksi'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_kesimpulan_tindakan: JSON.stringify(quillInstances['pemeriksaan_kesimpulan_tindakan'].getContents().ops),
                        hasil_kesimpulan_pemeriksaan_tindakan_saran: JSON.stringify(quillInstances['pemeriksaan_tindakan_saran'].getContents().ops),
                    },
                    success: function(response) {
                        createToast('Berhasil Validasi Kesimpulan', 'top-right', response.message, 'success', 3000);
                        $("#modal_validasi_rekap_kesimpulan").modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        createToast('Kesalahan Validasi Kesimpulan', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
});