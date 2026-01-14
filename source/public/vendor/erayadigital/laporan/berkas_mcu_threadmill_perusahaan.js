let id_mcu_let = '', nomor_mcu_let = '';
const pemeriksaanConfig = [
    { id: 'resume_berkas_threadmill', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan Threadmill peserta ini' },
];
let quillInstances = {};
$(document).ready(function() {
    // Initialize Quill editors
    pemeriksaanConfig.forEach(item => {
        quillInstances[item.id] = new Quill(`#${item.id}_quill`, {
            placeholder: item.placeholder,
            readOnly: true
        });
    });
    loadDataPasien();
});
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
                    d.from_query = "berkas_mcu";
                    d.status_peserta = "selesai";
                    d.jenis_laporan = 'threadmill';
                    d.id_perusahaan = id_perusahaan;
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
                            return `<div class="d-flex justify-content-between gap-2"><button onclick="lihat_berkas_mcu('${row.no_transaksi}', '${row.nama_peserta}', '${row.id}',this)" class="btn btn-success w-100"><i class="fa fa-print"></i> Lihat Berkas Threadmill</button></div>`;
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
function renderRow(item, level = 0, tbody, datadiri) {
    let paddingLeft = level * 2;
    let nilai_rujukan = '';
    let nilai_rujukan_object = [];
    let is_kuantitatif = false;
    let status = 'Nilai Tidak Terdefinisi';
    let semuajeniskelaminFound = false;
    if (item.meta_data_kuantitatif === '[]' && item.meta_data_kualitatif === '[]') {
        nilai_rujukan_object = "Meta Data Tidak Dikonfigurasi";
        nilai_rujukan = nilai_rujukan_object;
    }else{
        if (item.meta_data_kuantitatif !== '[]') {
            nilai_rujukan_object = JSON.parse(item.meta_data_kuantitatif);
            is_kuantitatif = true;
        }else{
            nilai_rujukan_object = JSON.parse(item.meta_data_kualitatif);
        }
    }
    if (nilai_rujukan_object.length > 0 && Array.isArray(nilai_rujukan_object)) {
        nilai_rujukan_object.forEach(item_nilai_rujukan => {
            let gender = item_nilai_rujukan.nama_nilai_kenormalan.split(" - ")[0].toLowerCase().replace(/\s+/g, '');
            let genderFromData = datadiri.jenis_kelamin.toLowerCase().replace(/\s+/g, '');
            if (item_nilai_rujukan.batas_umur == -1) {
                if (gender === "semuajeniskelamin")
                {
                    semuajeniskelaminFound = true
                }
                if (is_kuantitatif) {
                    if ((gender === genderFromData && !semuajeniskelaminFound) || gender === "semuajeniskelamin") {
                        const nilaiTindakan = parseFloat(item.nilai_tindakan); 
                        const batasBawah = parseFloat(item_nilai_rujukan.batas_bawah); 
                        const batasAtas = parseFloat(item_nilai_rujukan.batas_atas); 
                        if (nilaiTindakan >= batasBawah && nilaiTindakan <= batasAtas) {
                            status = "NORMAL";
                        } else {
                            status = "ABNORMAL";
                        }
                    }
                    nilai_rujukan += `${item_nilai_rujukan.nama_nilai_kenormalan} : ${item_nilai_rujukan.batas_bawah} ${item_nilai_rujukan.antara} ${item_nilai_rujukan.batas_atas}<br>`;
                } else {
                    if ((gender === genderFromData && !semuajeniskelaminFound) || gender === "semuajeniskelamin") {
                        if (item.nilai_tindakan.toLowerCase().replace(/\s+/g, '') === item_nilai_rujukan.keterangan_positif.toLowerCase().replace(/\s+/g, '')) {
                            status = "NORMAL";
                        } else if(item.nilai_tindakan.toLowerCase().replace(/\s+/g, '') === item_nilai_rujukan.keterangan_negatif.toLowerCase().replace(/\s+/g, '')) {
                            status = "ABNORMAL";
                        }else{
                            status = "TIDAK TERDEFINISI"
                        }
                    }
                    nilai_rujukan += `${item_nilai_rujukan.nama_nilai_kenormalan} : (+) <strong>${item_nilai_rujukan.keterangan_positif}</strong>, (-) <strong>${item_nilai_rujukan.keterangan_negatif}</strong><br>`;
                }
            }
        });
    }
    let row = `
        <tr>
            <td style="padding-left: ${paddingLeft}px;">➤${item.nama_item}</td>
            <td>${item.nilai_tindakan || 'Nilai Tidak Terdefinisi'}</td>
            <td>${nilai_rujukan}</td>
            <td>${item.satuan || ''}</td>
            <td>${status}</td>
        </tr>
    `;
    tbody.append(row);

    // Jika ada subkategori, rekursif panggil renderRow untuk setiap subkategori
    if (item.subkategori && item.subkategori.length > 0) {
        item.subkategori.forEach(subItem => renderRow(subItem, level + 1, tbody));
    }

    // Jika ada sub-item, lakukan hal yang sama
    if (item.sub && item.sub.length > 0) {
        item.sub.forEach(subItem => renderRow(subItem, level + 1, tbody));
    }
}
function clear_berkas_mcu(){
    
}
function lihat_berkas_mcu(no_transaksi, nama_peserta, id_mcu, button) {
    let button_element = $(button);
    button_element.prop('disabled', true);
    button_element.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laporan/informasi_mcu',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                id_mcu: id_mcu,
                nomor_mcu: no_transaksi,
                nama_peserta: nama_peserta,
            },
            success: function(response) {
                if (!response.riwayat_informasi_foto) {
                    button_element.prop('disabled', false);
                    button_element.html('<i class="fa fa-print"></i> Lihat Berkas');
                    return createToast('Kesalahan', 'top-right', nama_peserta+' belum melakukan foto data diri, silahkan lakukan foto data diri terlebuh dahulu agar dapat mengakses laporan ini', 'error', 3000);
                }
                if (!response.kesimpulan_tindakan) {
                    button_element.prop('disabled', false);
                    button_element.html('<i class="fa fa-print"></i> Lihat Berkas');
                    return createToast('Kesalahan', 'top-right', nama_peserta+' belum diberikan kesimpulan tindakan, konsultasikan dengan dokter atau admin untuk permasalahan ini', 'error', 3000);
                }
                $("#id_mcu_berkas_mcu").html(id_mcu);
                $("#riwayat_khusus_wanita_section").hide();
                $("#berkas_mcu_foro_peserta").html(`<img src="${response.riwayat_informasi_foto.data_foto}" class="img-fluid rounded scaled-image_0_3">`);
                $("#berkas_mcu_nomor_mcu").html(no_transaksi);
                $("#berkas_mcu_nama_peserta").html(nama_peserta);
                $("#berkas_mcu_nik").html(response.informasi_data_diri.nomor_identitas);
                $("#berkas_mcu_tempat_tanggal_lahir").html(response.informasi_data_diri.tempat_lahir+", "+moment(response.informasi_data_diri.tanggal_lahir).format('DD-MM-YYYY')+" / "+response.informasi_data_diri.umur+" Tahun");
                $("#berkas_mcu_jenis_kelamin").html(response.informasi_data_diri.jenis_kelamin);
                $("#berkas_mcu_alamat").html(response.informasi_data_diri.alamat);
                $("#berkas_mcu_perusahaan").html(response.informasi_data_diri.company_name);
                $("#berkas_mcu_jabatan").html(response.informasi_data_diri.nama_departemen);
                $("#berkas_mcu_tanggal_mcu").html(moment(response.informasi_data_diri.tanggal_mcu).format('DD-MM-YYYY')+" / "+response.informasi_data_diri.jenis_transaksi_pendaftaran.toUpperCase().replace('_', ' '));
                quillInstances['resume_berkas_threadmill'].setContents(JSON.parse(response.threadmill[0].detail_kesimpulan));
                $('#kesimpulan_berkas_threadmill_quill').html(response.threadmill[0].kesimpulan);
                $('#saran_catatan_kaki_threadmill_quill').html(response.threadmill[0].catatan_kaki);
                button_element.prop('disabled', false);
                button_element.html('<i class="fa fa-print"></i> Lihat Berkas');
                $("#modal_lihat_berkas_mcu").modal('show');
            },
            error: function() {
                button_element.prop('disabled', false);
                button_element.html('<i class="fa fa-print"></i> Lihat Berkas');
                createToast('Kesalahan Validasi Kesimpulan', 'top-right', error, 'error', 3000);
            }
        });
    });
}
$("#konfirmasi_validasi_rekap_kesimpulan").click(function(){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Validasi Akhir Dokumen Ini</h4><p class="text-muted mx-4 mb-0">Apakah ingin mencetak file berkas PDF MCU dari peserta Nama : <strong>'+$("#berkas_mcu_nama_peserta").text()+'</strong> dengan No.MCU : <strong>'+$("#berkas_mcu_nomor_mcu").text()+'</strong> ?. Untuk melanjukan ini kelihatannya membutuhkan proses yang lumayan lama, Usahakan jangan tutup tampilan ini dan pastikan terkoneksi dengan internet selama proses pengambilan data. </p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Cetak PDF',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            let id_mcu = $("#id_mcu_berkas_mcu").text();
            let nomor_mcu = $("#berkas_mcu_nomor_mcu").text();
            let nik_peserta = $("#berkas_mcu_nik").text();
            let dataparameter = btoa(JSON.stringify({id_mcu: id_mcu, nomor_mcu: nomor_mcu, nik_peserta: nik_peserta}));
            window.location.href = baseurl + '/laporan/berkas/mcu_threadmill/cetak?data='+dataparameter;
        }
    })
})