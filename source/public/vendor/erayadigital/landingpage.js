document.addEventListener('DOMContentLoaded', function () {
const steps = document.querySelectorAll('.step');
const nextButtons = document.querySelectorAll('.next-btn');
const prevButtons = document.querySelectorAll('.prev-btn');
const stepIndicators = document.querySelectorAll('.step-indicator');
let currentStep = 0;

// Fungsi untuk menampilkan step saat ini
function showStep(stepIndex) {
    // Sembunyikan semua step
    steps.forEach((step, index) => {
    if (index === stepIndex) {
        step.classList.add('active');
    } else {
        step.classList.remove('active');
    }
    });

    // Update indikator step
    stepIndicators.forEach((indicator, index) => {
    if (index === stepIndex) {
        indicator.classList.add('active');
    } else {
        indicator.classList.remove('active');
    }
    });
    // Auto-scroll ke step indicator yang aktif
    const activeIndicator = document.querySelector('.step-indicator.active');
    if (activeIndicator) {
    activeIndicator.scrollIntoView({
        behavior: 'smooth',
        block: 'nearest',
        inline: 'center'
    });
    }

    // Update currentStep
    currentStep = stepIndex;
}

// Event listener untuk tombol "Next"
nextButtons.forEach((button) => {
    button.addEventListener('click', () => {
    if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
    }
    });
});

// Event listener untuk tombol "Previous"
prevButtons.forEach((button) => {
    button.addEventListener('click', () => {
    if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
    }
    });
});

// Event listener untuk indikator step
stepIndicators.forEach((indicator) => {
    indicator.addEventListener('click', () => {
    const stepIndex = parseInt(indicator.getAttribute('data-step'));
    showStep(stepIndex);
    });
});

// Tampilkan step pertama saat halaman dimuat
showStep(currentStep);
});
let selectedTexts = [];
$('input[name="proses_kerja_temp"]').on('change', function() {
    selectedTexts = [];
    $('input[name="proses_kerja_temp"]:checked').each(function() {
        selectedTexts.push($('label[for="'+$(this).attr('id')+'"]').text());
    });
});
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
        console.log(status);
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
        let status = $('.status-atribut-kebiasaan-hidup[data-index="' + index + '"]').val();
        let berapakali = $('.nilai-atribut-kebiasaan-hidup[data-index="' + index + '"]').val();
        let infoAtribut = $('.info-atribut-kebiasaan-hidup[data-index="' + index + '"]').text();
        hasilKebiasaanHidup += '<tr>' +
            '<td style="text-align:left;width:50%;">' + namaAtribut + '</td>' +
            '<td style="width:25%;">' + (status == "" ? "Tidak" : status == 0 ? "Tidak" : "Ya") + '</td>' +
            '<td style="width:25%;">' + (berapakali == "" ? 0 : berapakali)  + ' '+infoAtribut+'</td>' +
        '</tr>';
    });
    $("#tabel_ini_kebiasaan_hidup").append(hasilKebiasaanHidup);
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
