let id_mcu_let = '', nomor_mcu_let = '';
const pemeriksaanConfig = [
    { id: 'berkas_mcu_riwayat_medis', placeholder: 'Tidak ada penjelasan mengenai Riwayat Medis peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_fisik', placeholder: 'Tekanan Darah, Suhu, Nadi, Respirasi, dan Berat Badan' },
    { id: 'berkas_mcu_pemeriksaan_laboratorium', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan Laboratorium peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_threadmill', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan Threadmill peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_rontgen_thorax', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan Rontgen Thorax peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_rontgen_lumbosacral', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan Rontgen Lumbosacral peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_usg_ubdomain', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan USG Ubdomain peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_farmingham_score', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan Farmingham Score peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_ekg', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan EKG peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_audiometri_kiri', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan Audiometri Kiri peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_audiometri_kanan', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan Audiometri Kanan peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_spirometri_restriksi', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan Spirometri Restriksi peserta ini' },
    { id: 'berkas_mcu_pemeriksaan_spirometri_obstruksi', placeholder: 'Tidak ada penjelasan mengenai Pemeriksaan Spirometri Obstruksi peserta ini' },
    { id: 'berkas_mcu_kesimpulan_tindakan', placeholder: 'Tidak ada penjelasan mengenai Kesimpulan Tindakan peserta ini' },
    { id: 'berkas_mcu_tindakan_saran', placeholder: 'Tidak ada penjelasan mengenai Tindakan Saran peserta ini' },
    { id: 'editor_riwayat_kecelakaan_kerja', placeholder: 'Tidak ada penjelasan mengenai Riwayat Kecelakaan Kerja peserta ini' },
];
let quillInstances = {};
$(document).ready(function() {
    loadDataPasien();
    pemeriksaanConfig.forEach(item => {
        quillInstances[item.id] = new Quill(`#${item.id}_quill`, {
            placeholder: item.placeholder,
            readOnly: true
        });
    });
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
function renderRow(item, level = 0, tbody, datadiri) {
    let paddingLeft = level * 2;
    console.log(level);
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
            console.log(item_nilai_rujukan);
            let gender = item_nilai_rujukan.nama_nilai_kenormalan.split(" - ")[0].toLowerCase().replace(/\s+/g, '');
            let genderFromData = datadiri.jenis_kelamin.toLowerCase().replace(/\s+/g, '');
            if (item_nilai_rujukan.batas_umur == -1) {
                if (is_kuantitatif) {
                    nilai_rujukan += item_nilai_rujukan.nama_nilai_kenormalan + " : " + item_nilai_rujukan.batas_bawah + " " + item_nilai_rujukan.antara + " " + item_nilai_rujukan.batas_atas + "<br>";
                    if (gender === genderFromData) {
                        return;
                    }
                    if (gender === "semuajeniskelamin") {
                        if (!semuajeniskelaminFound) {
                            nilai_rujukan = '';
                            semuajeniskelaminFound = true;
                            nilai_rujukan += item_nilai_rujukan.nama_nilai_kenormalan + " : " + item_nilai_rujukan.batas_bawah + " " + item_nilai_rujukan.antara + " " + item_nilai_rujukan.batas_atas + "<br>";
                        }
                        if (semuajeniskelaminFound && gender !== "semuajeniskelamin") {
                            return;
                        }
                    }
                    const nilaiTindakan = parseFloat(item.nilai_tindakan); 
                    const batasBawah = parseFloat(item_nilai_rujukan.batas_bawah); 
                    const batasAtas = parseFloat(item_nilai_rujukan.batas_atas);                        
                    if (nilaiTindakan >= batasBawah && nilaiTindakan <= batasAtas) {
                        status = "NORMAL";
                    } else {
                        status = "ABNORMAL";
                    }
                } else {
                    nilai_rujukan += item_nilai_rujukan.nama_nilai_kenormalan + " : (+) <strong>" + item_nilai_rujukan.keterangan_positif + "</strong>, (-) <strong>" + item_nilai_rujukan.keterangan_negatif + "</strong><br>";
                    if (gender === genderFromData) {
                        return;
                    }
                    if (gender === "semuajeniskelamin") {
                        if (!semuajeniskelaminFound) {
                            nilai_rujukan = '';
                            semuajeniskelaminFound = true;
                            nilai_rujukan += item_nilai_rujukan.nama_nilai_kenormalan + " : (+) <strong>" + item_nilai_rujukan.keterangan_positif + "</strong>, (-) <strong>" + item_nilai_rujukan.keterangan_negatif + "</strong><br>";
                        }
                        if (semuajeniskelaminFound && gender !== "semuajeniskelamin") {
                            return;
                        }
                        if (item.nilai_tindakan.toLowerCase().replace(/\s+/g, '') === item_nilai_rujukan.keterangan_positif.toLowerCase().replace(/\s+/g, '')) {
                            status = "NORMAL";
                        } else if(item.nilai_tindakan.toLowerCase().replace(/\s+/g, '') === item_nilai_rujukan.keterangan_negatif.toLowerCase().replace(/\s+/g, '')) {
                            status = "ABNORMAL";
                        }
                    }
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

function lihat_berkas_mcu(no_transaksi, nama_peserta, id_mcu) {
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
                console.log(response.laboratorium);
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
                /*kesimpulan tindakan*/
                quillInstances['berkas_mcu_riwayat_medis'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_riwayat_medis));
                quillInstances['berkas_mcu_pemeriksaan_fisik'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_fisik));
                quillInstances['berkas_mcu_pemeriksaan_laboratorium'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_laboratorum));
                quillInstances['berkas_mcu_pemeriksaan_threadmill'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_threadmill));
                quillInstances['berkas_mcu_pemeriksaan_rontgen_thorax'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_rontgen_thorax));
                quillInstances['berkas_mcu_pemeriksaan_rontgen_lumbosacral'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_rontgen_lumbosacral));
                quillInstances['berkas_mcu_pemeriksaan_usg_ubdomain'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_usg_ubdomain));
                quillInstances['berkas_mcu_pemeriksaan_farmingham_score'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_farmingham_score));
                quillInstances['berkas_mcu_pemeriksaan_ekg'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_ekg));
                quillInstances['berkas_mcu_pemeriksaan_audiometri_kiri'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_audio_kiri));
                quillInstances['berkas_mcu_pemeriksaan_audiometri_kanan'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_audio_kanan));
                quillInstances['berkas_mcu_pemeriksaan_spirometri_restriksi'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_spiro_restriksi));
                quillInstances['berkas_mcu_pemeriksaan_spirometri_obstruksi'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_pemeriksaan_spiro_obstruksi));
                /* kesimpulan dan saran*/
                quillInstances['berkas_mcu_kesimpulan_tindakan'].setContents(JSON.parse(response.kesimpulan_tindakan.kesimpulan_keseluruhan));
                quillInstances['berkas_mcu_tindakan_saran'].setContents(JSON.parse(response.kesimpulan_tindakan.saran_keseluruhan));
                /* penyakit terdahulu*/
                $("#datatables_penyakit_terdahulu_modal tbody").empty();
                for (let i = 0; i < response.penyakit_terdahulu.length; i++) {
                    let item = response.penyakit_terdahulu[i];
                    let row = $('#datatables_penyakit_terdahulu_modal tbody tr').filter(function() {
                        return $(this).find('td:eq(0)').text() == item.id_atribut_pt; // Adjust based on key
                    });
                    if (row.length === 0) {
                        $('#datatables_penyakit_terdahulu_modal tbody').append(`
                            <tr>
                                <td>${item.nama_atribut_saat_ini}</td>
                                <td>${item.status == 0 ? 'Tidak' : 'Ya'}</td>
                                <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                            </tr>
                        `);
                    }
                }  
                /* Riwayat Penyakit Keluarga*/
                $('#datatables_riwayat_penyakit_keluarga_modal tbody').empty(); 
                for (let i = 0; i < response.riwayat_penyakit_keluarga.length; i++) {
                    let item = response.riwayat_penyakit_keluarga[i];
                    let row = $('#datatables_riwayat_penyakit_keluarga_modal tbody tr').filter(function() {
                        return $(this).find('td:eq(0)').text() == item.id_atribut_pk; // Adjust based on key
                    });
                    if (row.length === 0) {
                        $('#datatables_riwayat_penyakit_keluarga_modal tbody').append(`
                            <tr>
                                <td>${item.nama_atribut_saat_ini}</td>
                                <td>${item.status == 0 ? 'Tidak' : 'Ya'}</td>
                                <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                            </tr>
                        `);
                    }
                }
                if (response.informasi_data_diri.jenis_kelamin === 'Perempuan') {
                    $("#riwayat_khusus_wanita_section").show();
                    for (let i = 0; i < response.riwayat_kebiasaan_hidup.length; i++) {
                        let item = response.riwayat_kebiasaan_hidup[i];
                        if (item.jenis_kebiasaan == 2) {
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
                }
                quillInstances['editor_riwayat_kecelakaan_kerja'].setContents(JSON.parse(response.riwayat_kecelakaan_kerja[0].riwayat_kecelakaan_kerja));
                /* riwayat kebiasaan hidup*/
                $("#datatables_riwayat_kebiasaan_hidup_modal").show();
                for (let i = 0; i < response.riwayat_kebiasaan_hidup.length; i++) {
                    let item = response.riwayat_kebiasaan_hidup[i];
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
                }
                /* riwayat imunisasi*/
                $("#datatables_imunisasi_modal tbody").empty();
                for (let i = 0; i < response.riwayat_imunisasi.length; i++) {
                    let item = response.riwayat_imunisasi[i];
                    let row = $('#datatables_imunisasi_modal tbody tr').filter(function() {
                        return $(this).find('td:eq(0)').text() == item.id_atribut_pk; // Adjust based on key
                    });
                    if (row.length === 0) {
                        $('#datatables_imunisasi_modal tbody').append(`
                            <tr>
                                <td>${item.nama_atribut_saat_ini}</td>
                                <td>${item.status == 0 ? 'Tidak' : 'Ya'}</td>
                                <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                            </tr>
                        `);
                    }
                }   
                /* paparan kerja atau lingkungan*/
                $("#datatables_riwayat_lingkungan_kerja_modal tbody").empty();
                for (let i = 0; i < response.riwayat_lingkungan_kerja.length; i++) {
                    let item = response.riwayat_lingkungan_kerja[i];
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
                /* tanda vital*/
                $("#datatables_tanda_vital_modal_tanda_vital tbody").empty();
                let BMI = 0, TB = 0, BB = 0;
                for (let i = 0; i < response.tanda_vital.length; i++) {
                    let no = i + 1;
                    let item = response.tanda_vital[i];
                    let row = $('#datatables_tanda_vital_modal_tanda_vital tbody tr').filter(function() {
                        return $(this).find('td:eq(0)').text() == item.id_atribut_lv;
                    });
                    if (row.length === 0 && item.jenis_tanda_vital === 'tanda_vital') {
                        $('#datatables_tanda_vital_modal_tanda_vital tbody').append(`
                            <tr>
                                <td>${item.nama_atribut_saat_ini}</td>
                                <td>${item.nilai_tanda_vital} ${item.satuan_tanda_vital}</td>
                                <td>${item.keterangan_tanda_vital ? item.keterangan_tanda_vital : 'Tidak Ada Keterangan'}</td>
                            </tr>
                        `);
                    }
                    if (row.length === 0 && item.jenis_tanda_vital === 'tanda_gizi') {
                        $('#datatables_tanda_vital_modal_tanda_gizi tbody').append(`
                            <tr>    
                                <td>${item.nama_atribut_saat_ini}</td>
                                <td>${item.nilai_tanda_vital} ${item.satuan_tanda_vital}</td>
                                <td>${item.keterangan_tanda_vital ? item.keterangan_tanda_vital : 'Tidak Ada Keterangan'}</td>
                            </tr>
                        `);
                        if (item.nama_atribut_saat_ini.toLowerCase().replace(/\s+/g, '') === 'beratbadan') {
                            BB = item.nilai_tanda_vital;
                        }
                        if (item.nama_atribut_saat_ini.toLowerCase().replace(/\s+/g, '') === 'tinggibadan') {
                            TB = item.nilai_tanda_vital / 100;
                        }
                    }
                }  
                BMI = BB / (TB * TB);
                $("#modal_bmi_temp").text(BMI.toFixed(2));
                if (BMI < 18.5) {
                    $("#modal_status_gizi_temp").text('KEKURANGAN BERAT BADAN');
                }else if (BMI >= 18.5 && BMI <= 24.9) {
                    $("#modal_status_gizi_temp").text('NORMAL');
                }else if (BMI >= 25 && BMI <= 29.9) {
                    $("#modal_status_gizi_temp").text('KELEBIHAN BERAT BADAN');
                }
                /* tingkat kesadaran*/
                $("#modal_keadaan_umum_temp").text(response.tingkat_kesadaran.nama_atribut_tingkat_kesadaran);
                $("#modal_keterangan_keadaan_umum_temp").text(response.tingkat_kesadaran.keterangan_tingkat_kesadaran);
                $("#modal_status_kesadaran_temp").text(response.tingkat_kesadaran.nama_atribut_status_tingkat_kesadaran);
                $("#modal_keterangan_status_kesadaran_temp").text(response.tingkat_kesadaran.keterangan_status_tingkat_kesadaran);
                $("#modal_keluhan_temp").text(response.tingkat_kesadaran.keluhan);
                /* penglihatan*/
                $("#visus_os_tanpa_kacamata_jauh_modal").text(response.penglihatan.visus_os_tanpa_kacamata_jauh);
                $("#visus_od_tanpa_kacamata_jauh_modal").text(response.penglihatan.visus_od_tanpa_kacamata_jauh);
                $("#visus_os_kacamata_jauh_modal").text(response.penglihatan.visus_os_kacamata_jauh);
                $("#visus_od_kacamata_jauh_modal").text(response.penglihatan.visus_od_kacamata_jauh);
                $("#visus_os_tanpa_kacamata_dekat_modal").text(response.penglihatan.visus_os_tanpa_kacamata_dekat);
                $("#visus_od_tanpa_kacamata_dekat_modal").text(response.penglihatan.visus_od_tanpa_kacamata_dekat);
                $("#visus_os_kacamata_dekat_modal_modal").text(response.penglihatan.visus_os_kacamata_dekat);
                $("#visus_od_kacamata_dekat_modal_modal").text(response.penglihatan.visus_od_kacamata_dekat);
                $("#buta_warna_modal").text(response.penglihatan.buta_warna.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '));
                $("#lapang_pandang_superior_os_modal").text(response.penglihatan.lapang_pandang_superior_os);
                $("#lapang_pandang_inferior_os_modal").text(response.penglihatan.lapang_pandang_inferior_os);
                $("#lapang_pandang_temporal_os_modal").text(response.penglihatan.lapang_pandang_temporal_os);
                $("#lapang_pandang_nasal_os_modal").text(response.penglihatan.lapang_pandang_nasal_os);
                $("#lapang_pandang_keterangan_os_modal").text(response.penglihatan.lapang_pandang_keterangan_os);
                $("#lapang_pandang_superior_od_modal").text(response.penglihatan.lapang_pandang_superior_od);
                $("#lapang_pandang_inferior_od_modal").text(response.penglihatan.lapang_pandang_inferior_od);
                $("#lapang_pandang_temporal_od_modal").text(response.penglihatan.lapang_pandang_temporal_od);
                $("#lapang_pandang_nasal_od_modal").text(response.penglihatan.lapang_pandang_nasal_od);
                $("#lapang_pandang_keterangan_od_modal").text(response.penglihatan.lapang_pandang_keterangan_od);
                /* kondisi fisik*/
                $("#datatables_kondisi_fisik_log_modal tbody").empty();
                let groupedData = {};
                response.kondisi_fisik.forEach(item => {
                    if (!groupedData[item.kategori]) {
                        groupedData[item.kategori] = [];
                    }
                    groupedData[item.kategori].push(item);
                });
                Object.keys(groupedData).forEach(kategori => {
                    let html = `
                        <tr>
                            <th colspan="4" class="fw-bold text-center">${kategori.toUpperCase().replace(/_/g, ' ')}</th>
                        </tr>
                        <tr>
                            <th>JENIS PEMERIKSAAN</th>
                            <th style="text-align: center;">AB</th>
                            <th style="text-align: center;">N</th>
                            <th>KETERANGAN</th>
                        </tr>
                    `;
                    groupedData[kategori].forEach(item => {
                        html += `
                            <tr>
                                <td>${item.jenis_atribut}</td>
                                <td style="text-align: center;">${item.status_atribut === 'abnormal' ? '✅' : '❌'}</td>
                                <td style="text-align: center;">${item.status_atribut === 'normal' ? '✅' : '❌'}</td>
                                <td>${item.keterangan_atribut ? item.keterangan_atribut : 'Tidak Ada Keterangan'}</td>
                            </tr>
                        `;
                    });
                    $("#datatables_kondisi_fisik_log_modal tbody").append(html);
                });
                /* hasil laboratorium*/
                $("#datatables_hasil_laboratorium_modal tbody").empty();
                let tbody = $('#datatables_hasil_laboratorium_modal tbody');
                tbody.empty();
                function renderKategori(kategori, depth, datadiri) {
                    function hasValidItems(kategori) {
                        if (kategori.items.length > 0) return true;
                        for (let subkategori of kategori.subkategori) {
                            if (subkategori.items.length > 0 || hasValidItems(subkategori)) {
                                return true;
                            }
                        }
                        return false;
                    }
                    if (hasValidItems(kategori)) {
                        let paddingLeft = depth * 4;
                        let bgColor = '', textColor = '';
                        if (depth == 1) {paddingLeft = 0; bgColor = 'green'; textColor = 'white';}
                        let prefix = (depth > 1 && (kategori.items.length > 0 && kategori.subkategori.length > 0)) ? '➤' : '';
                        tbody.append(`
                            <tr class="kategori-${kategori.id}" style="margin-left: 100px; margin-right: 100px;">
                                <td colspan="5" style="padding-left: ${paddingLeft}px; background-color: ${bgColor}; color: ${textColor};">${kategori.nama_kategori}</td>
                            </tr>
                        `);
                        if (kategori.items && kategori.items.length > 0) {
                            kategori.items.forEach(item => renderRow(item, depth, tbody, datadiri));
                        }
                        if (kategori.subkategori && kategori.subkategori.length > 0) {
                            kategori.subkategori.forEach(subkategori => {
                                renderKategori(subkategori, depth + 1, datadiri);
                            });
                        }
                    }
                }
                response.laboratorium.forEach(kategori => {
                    renderKategori(kategori, 1, response.informasi_data_diri);
                });
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