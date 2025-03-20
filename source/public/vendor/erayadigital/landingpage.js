document.addEventListener('DOMContentLoaded', function () {
const steps = document.querySelectorAll('.step');
const nextButtons = document.querySelectorAll('.next-btn');
const prevButtons = document.querySelectorAll('.prev-btn');
const stepIndicators = document.querySelectorAll('.step-indicator');
let currentStep = 0;
$(document).ready(function() {
    let selectedGender = $("#jenis_kelamin_temp").val();
    $('.kebiasaan-hidup-wrapper').each(function() {
        if (selectedGender === "Laki-Laki") {
            $(".status_2").hide();
            $(".table_status_2").addClass('d-none');
        } else {
            $(".status_2").show();
            $(".table_status_2").removeClass('d-none');
        }
    });
    $('.status_2 .nilai-atribut-kebiasaan-hidup').each(function() {
        flatpickr(this, {
            dateFormat: 'd-m-Y',
            maxDate: new Date(),
            allowInput: true
        });
    });
})
function showStep(stepIndex) {
    steps.forEach((step, index) => {
    if (index === stepIndex) {
        step.classList.add('active');
    } else {
        step.classList.remove('active');
    }
    });
    stepIndicators.forEach((indicator, index) => {
    if (index === stepIndex) {
        indicator.classList.add('active');
    } else {
        indicator.classList.remove('active');
    }
    });
    const activeIndicator = document.querySelector('.step-indicator.active');
    if (activeIndicator) {
    activeIndicator.scrollIntoView({
        behavior: 'smooth',
        block: 'nearest',
        inline: 'center'
    });
    }
    currentStep = stepIndex;
}
function focuskestep(offset) {
    const elementPosition = document.querySelector('.step-indicators').getBoundingClientRect().top + window.scrollY;
    window.scrollTo({
        top: elementPosition - offset,
        behavior: 'smooth'
    });
}
nextButtons.forEach((button) => {
    button.addEventListener('click', () => {
    if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
    }
    focuskestep(100);
    });
});
prevButtons.forEach((button) => {
    button.addEventListener('click', () => {
    if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
    }
    focuskestep(100);
    });
});
stepIndicators.forEach((indicator) => {
    indicator.addEventListener('click', () => {
    const stepIndex = parseInt(indicator.getAttribute('data-step'));
    showStep(stepIndex);
    });
});
showStep(currentStep);
});
let selectedTexts = [];
$('input[name="proses_kerja_temp"]').on('change', function() {
    selectedTexts = [];
    $('input[name="proses_kerja_temp"]:checked').each(function() {
        selectedTexts.push($('label[for="'+$(this).attr('id')+'"]').text());
    });
});
$("#setuju_data_benar").click(function() {
    if ($(this).is(':checked')) {
        $("#btn_kirim_formulir").removeAttr('disabled');
    } else {
        $("#btn_kirim_formulir").attr('disabled', 'disabled');
    }
})
$("#pratinjau_halaman").click(function() {
    $("#modal_nomor_identitas").text($("#nomor_identitas_temp").val());
    $("#modal_nama_peserta").text($("#nama_peserta_temp").val());
    $("#modal_tempat_lahir").text($("#tempat_lahir_temp").val());
    $("#modal_tanggal_lahir").text($("#tanggal_lahir_peserta_temp").val());
    $("#modal_tipe_identitas").text($("#tipe_identitas_temp").val());
    $("#modal_jenis_kelamin").text($("#jenis_kelamin_temp").val());
    $("#modal_status_perkawinan").text($("#status_perkawinan_temp").val());
    $("#modal_no_telepon").text($("#no_telepon_temp").val());
    $("#modal_alamat_surel").text($("#alamat_surel_temp").val());
    $("#modal_alamat_tempat_tinggal").text($("#alamat_tempat_tinggal_temp").val());   
    $("#modal_proses_kerja").text(selectedTexts.join(', '));
    /*LINGKUNGAN KERJA*/
    let hasilLingkunganKerja = '';
    let hasilKebiasaanHidup = '';
    let hasilPenyakitTerdahulu = '';
    let hasilPenyakitKeluarga = '';
    let hasilImunisasi = '';
    $("#tabel_ini").html("");
    $("#tabel_ini_kebiasaan_hidup").html("");
    $("#tabel_ini_penyakit_terdahulu").html("");
    $("#tabel_ini_penyakit_keluarga").html("");
    $("#tabel_ini_imunisasi").html("");
    $('.nama-atribut-lingkungan-kerja').each(function() {
        let index = $(this).data('index');
        let namaAtribut = $(this).text();
        let status = $('.status-atribut-lingkungan-kerja[data-index="' + index + '"]').val();
        let jamPerHari = $('.jamperhari-atribut[data-index="' + index + '"]').val();
        let selamaXTahun = $('.selamaxtahun-atribut[data-index="' + index + '"]').val();
        hasilLingkunganKerja += '<tr>' +
            '<td>' + namaAtribut + '</td>' +
            '<td>' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
            '<td>' + (jamPerHari == "" ? 0 : jamPerHari) + ' Jam Per Hari</td>' +
            '<td> Selama ' + (selamaXTahun == "" ? 0 : selamaXTahun) + ' Tahun</td>' +
        '</tr>';
    });
    $("#tabel_ini").append(hasilLingkunganKerja);
    /*END LINGKUNGAN KERJA*/
    /*FORMULIR KECELAKAAN KERJA*/
    $("#modal_informasi_kecelakaan_kerja").text($("#informasi_kecelakaan_kerja_temp").val() == "" ? "Tidak Ada Riwayat Kecelakaan Kerja" : $("#informasi_kecelakaan_kerja_temp").val());
    /*END FORMULIR KECELAKAAN KERJA*/
    /*KEBIASAAN HIDUP*/
    $('.nama-atribut-kebiasaan-hidup').each(function() {
        let index = $(this).data('index');
        let namaAtribut = $(this).text();
        let status_parameter = $('.status-parameter-wrapper-' + index).text();
        let status = $('.status-atribut-kebiasaan-hidup[data-index="' + index + '"]').val();
        let berapakali = $('.nilai-atribut-kebiasaan-hidup[data-index="' + index + '"]').val();
        let infoAtribut = $('.info-atribut-kebiasaan-hidup[data-index="' + index + '"]').text();
        hasilKebiasaanHidup += '<tr class="table_status_' + status_parameter + '">' +
            '<td style="text-align:left;width:50%;">' + namaAtribut + '</td>' +
            '<td style="width:25%;">' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
            '<td style="width:25%;">' + (berapakali == "" ? 0 : berapakali)  + ' '+infoAtribut+'</td>' +
        '</tr>';
    });
    $("#tabel_ini_kebiasaan_hidup").html(hasilKebiasaanHidup);
    let selectedGender = $('#jenis_kelamin_temp').val();
    if (selectedGender === "Laki-Laki") {
        $("#tabel_ini_kebiasaan_hidup .table_status_2").addClass('d-none');
    } else {
        $("#tabel_ini_kebiasaan_hidup .table_status_2").removeClass('d-none');
    }
    /*END FORMULIR KEBIASAAAN HIDUP*/
    /*FORMULIR PENYAKIT TERDAHULU*/
    $('.nama-atribut-penyakit-terdahulu').each(function() {
        let index = $(this).data('index');
        let namaAtribut = $(this).text();
        let status = $('.status-atribut-penyakit-terdahulu[data-index="' + index + '"]').val();
        let keterangan_penyakit_terdaulu = $('.keterangan-atribut-penyakit-terdahulu[data-index="' + index + '"]').val();
        hasilPenyakitTerdahulu += '<tr>' +
            '<td style="text-align:left;width:50%;">' + namaAtribut + '</td>' +
            '<td style="width:25%;">' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
            '<td style="width:25%;">' + (keterangan_penyakit_terdaulu == "" ? " " : keterangan_penyakit_terdaulu)  + '</td>' +
        '</tr>';
    });
    $("#tabel_ini_penyakit_terdahulu").append(hasilPenyakitTerdahulu);
    /*END FORMULIR PENYAKIT TERDAHULU*/
    /*FORMULIR PENYAKIT KELUARGA*/
    $('.nama-atribut-penyakit-keluarga').each(function() {
        let index = $(this).data('index');
        let namaAtribut = $(this).text();
        let status = $('.status-atribut-penyakit-keluarga[data-index="' + index + '"]').val();
        let keterangan_penyakit_keluarga = $('.keterangan-atribut-penyakit-keluarga[data-index="' + index + '"]').val();
        hasilPenyakitKeluarga += '<tr>' +
            '<td style="text-align:left;width:50%;">' + namaAtribut + '</td>' +
            '<td style="width:25%;">' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
            '<td style="width:25%;">' + (keterangan_penyakit_keluarga == "" ? " " : keterangan_penyakit_keluarga)  + '</td>' +
        '</tr>';
    })
    $("#tabel_ini_penyakit_keluarga").append(hasilPenyakitKeluarga);
    /*END FORMULIR PENYAKIT KELUARGA*/
    /*FORMULIR IMUNISASI*/
    $('.nama-atribut-imunisasi').each(function() {
        let index = $(this).data('index');
        let namaAtribut = $(this).text();
        let status = $('.status-atribut-imunisasi[data-index="' + index + '"]').val();
        let keterangan_imunisasi = $('.keterangan-atribut-imunisasi[data-index="' + index + '"]').val();
        hasilImunisasi += '<tr>' +
            '<td style="text-align:left;width:50%;">' + namaAtribut + '</td>' +
            '<td style="width:25%;">' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
            '<td style="width:25%;">' + (keterangan_imunisasi == "" ? " " : keterangan_imunisasi)  + '</td>' +
        '</tr>';
    })
    $("#tabel_ini_imunisasi").append(hasilImunisasi);
    /*END FORMULIR IMUNISASI*/
    $("#modalPratinjau").modal("show");
});
$("#btn_kirim_formulir").click(function() {
    if (!$("#setuju_data_benar").is(':checked')) {
        createToast('Kesalahan', 'top-right', "Centang terlebih dahulu jikalau anda ingin mengirimkan formulir ini untuk mendapatkan nomor antrian atau kode pemesanan", 'error', 3000);
    }
    if ($("#modal_nomor_identitas").text() == "" || $("#modal_nama_peserta").text() == "") {
        createToast('Kesalahan', 'top-right', "Nama serta Nomor Identitas tidak boleh kosong karena dibutuhkan untuk kode unik pendaftaran peserta", 'error', 3000);
        return false;
    }
    const formDataDataDiri = {
        nomor_identitas_temp: document.getElementById('nomor_identitas_temp').value,
        nama_peserta_temp: document.getElementById('nama_peserta_temp').value,
        tempat_lahir_temp: document.getElementById('tempat_lahir_temp').value,
        tanggal_lahir_peserta_temp: document.getElementById('tanggal_lahir_peserta_temp').value,
        tipe_identitas_temp: document.getElementById('tipe_identitas_temp').value,
        jenis_kelamin_temp: document.getElementById('jenis_kelamin_temp').value,
        status_perkawinan_temp: document.getElementById('status_perkawinan_temp').value,
        no_telepon_temp: document.getElementById('no_telepon_temp').value,
        alamat_surel_temp: document.getElementById('alamat_surel_temp').value,
        proses_kerja_temp: Array.from(document.querySelectorAll('input[name="proses_kerja_temp"]:checked')).map((el) => el.value),
        alamat_tempat_tinggal_temp: document.getElementById('alamat_tempat_tinggal_temp').value
    };
    const lingkunganKerjaData = [];
    document.querySelectorAll('.nama-atribut-lingkungan-kerja').forEach((el) => {
        const index = el.getAttribute('data-index');
        lingkunganKerjaData.push({
            nama_atribut_lk: el.textContent,
            status: document.querySelector(`.status-atribut-lingkungan-kerja[data-index="${index}"]`).value,
            jam_per_hari: document.querySelector(`.jamperhari-atribut[data-index="${index}"]`).value,
            selama_x_tahun: document.querySelector(`.selamaxtahun-atribut[data-index="${index}"]`).value
        });
    });
    const informasiKecelakaanKerja = document.getElementById('informasi_kecelakaan_kerja_temp').value;
    let kebiasaanHidupData = [];
    $('.status-atribut-kebiasaan-hidup').each(function() {
        const index = $(this).data('index');
        const status = $(this).val();
        const nilai = $(`.nilai-atribut-kebiasaan-hidup[data-index="${index}"]`).val();
        const info = $(`.info-atribut-kebiasaan-hidup[data-index="${index}"]`).text();
        const nama_atribut_kb = $(`.nama-atribut-kebiasaan-hidup[data-index="${index}"]`).text();

        kebiasaanHidupData.push({
            index: index,
            status: status,
            nilai: nilai,
            nama_atribut_kb: nama_atribut_kb,
            info: info
        });
    });
    let penyakitTerdahuluData = [];
    $('.status-atribut-penyakit-terdahulu').each(function() {
        const index = $(this).data('index');
        const status = $(this).val();
        const keterangan = $(`.keterangan-atribut-penyakit-terdahulu[data-index="${index}"]`).val();
        const info = $(`.nama-atribut-penyakit-terdahulu[data-index="${index}"]`).text();

        penyakitTerdahuluData.push({
            index: index,
            status: status,
            keterangan: keterangan,
            info: info
        });
    });

    let penyakitKeluargaData = [];
    $('.status-atribut-penyakit-keluarga').each(function() {
        const index = $(this).data('index');
        const status = $(this).val();
        const keterangan = $(`.keterangan-atribut-penyakit-keluarga[data-index="${index}"]`).val();
        const info = $(`.nama-atribut-penyakit-keluarga[data-index="${index}"]`).text();

        penyakitKeluargaData.push({
            index: index,
            status: status,
            keterangan: keterangan,
            info: info
        });
    });

    let imunisasiData = [];
    $('.status-atribut-imunisasi').each(function() {
        const index = $(this).data('index');
        const status = $(this).val();
        const keterangan = $(`.keterangan-atribut-imunisasi[data-index="${index}"]`).val();
        const info = $(`.nama-atribut-imunisasi[data-index="${index}"]`).text();
    
        imunisasiData.push({
            index: index,
            status: status,
            keterangan: keterangan,
            info: info
        });
    });
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Akhir Verif Data</h4><p class="text-muted mx-4 mb-0">Kurang satu langkah lagi untuk mendapatkan nomor pesanan atas nama <strong>' + $("#modal_nama_peserta").text() + '</strong> dengan nomor identitas <strong>' + $("#modal_nomor_identitas").text() + '</strong> ?. Pastikan anda mengingat dan menginformasian kepada CS ketika mengunjukin Clinic</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Dapatkan No Pesanan',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/enduser/formulir/1',
                    type: 'POST',
                    beforeSend: function(xhr){
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: {
                        _token: response.csrf_token,
                        formDataDataDiri: JSON.stringify(formDataDataDiri),
                        formDataLingkunganKerja: JSON.stringify({ lingkungan_kerja: lingkunganKerjaData }),
                        formDataKecelakaanKerja: JSON.stringify({ informasi_kecelakaan_kerja: informasiKecelakaanKerja }),
                        formDataKebiasaanHidup: JSON.stringify({ kebiasaan_hidup: kebiasaanHidupData }),
                        formDataPenyakitTerdahulu: JSON.stringify({ penyakit_terdahulu: penyakitTerdahuluData }),
                        formDataPenyakitKeluarga: JSON.stringify({ penyakit_keluarga: penyakitKeluargaData }),
                        formDataImunisasi: JSON.stringify({ imunisasi: imunisasiData })
                    },
                    success: function(response){
                        if(!response.success){
                            createToast('Terjadi Kesalahan', 'top-right', response.message, 'error', 3000);
                        }
                        createToast('Berhasil', 'top-right', response.message, 'success', 3000);
                        setTimeout(function() {
                            window.location.href = '/no_antrian/'+response.data.no_pemesanan;
                        }, 1000);
                    },
                    error: function(xhr, status, error){
                        createToast('Kesalahan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            })
        }
    });
})
$('#jenis_kelamin_temp').on('change', function() {
    let selectedGender = $(this).val();
    if (selectedGender === "Laki-Laki") {
        $(".status_2").hide();
        $("#tabel_ini_kebiasaan_hidup .table_status_2").addClass('d-none');
    } else {
        $(".status_2").show();
        $("#tabel_ini_kebiasaan_hidup .table_status_2").removeClass('d-none');
    }
}).trigger('change');

document.getElementById('jenis_kelamin_temp').dispatchEvent(new Event('change'));