let formValidasi = $("#formulir_tambah_paket_mcu");let isedit = false;let idpaketmcu = "";
$(document).ready(function() {
    daftarpaketmcu();
    $("#tabel_pemeriksaan tr").click(function(event) {
        if (!$(event.target).is("input[type='checkbox']")) {
            let checkbox = $(this).find("input[type='checkbox']");
            checkbox.prop("checked", !checkbox.prop("checked"));
        }
        let hasRowspan = $(this).find("td[rowspan]").length > 0;
        let targetColumns = hasRowspan 
            ? $(this).find("td:nth-child(2), td:nth-child(3)") 
            : $(this).find("td:nth-child(1), td:nth-child(2)");
        let checked = $(this).find("input[type='checkbox']").is(":checked");
        targetColumns.css("background-color", checked ? "#add8e6" : "");

    });
});
const hargapaketmcu = new AutoNumeric('#hargapaketmcu', {
    digitGroupSeparator: '.',
    decimalCharacter: ',',
    decimalPlaces: 0,
    modifyValueOnUpDownArrow: false,
    modifyValueOnWheel: false
});

function daftarpaketmcu() {
    $.get('/generate-csrf-token', function(response) {
        $("#datatable_paketmcu").DataTable({
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
            scrollX: true,
            bFilter: false,
            bInfo: true,
            ordering: false,
            bPaginate: true,
            bProcessing: true,
            serverSide: true,
            ajax: {
                "url": baseurlapi + '/masterdata/daftarpaketmcu',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_paket_mcu").val();
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
                    const infoString = 'Halaman ke: ' + currentPage + ' Ditampilkan: ' + 10 + ' Jumlah Data: ' + recordsFiltered + ' data';
                    return infoString;
                }
            },
            pagingType: "full_numbers",
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            columns: [
                {
                    title: "No",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: "Kode Paket",
                    data: "kode_paket"
                },
                {
                    title: "Nama Paket",
                    data: "nama_paket"
                },
                {
                    title: "Harga",
                    render: function(data, type, row, meta) {
                       if (type === 'display'){
                        return row.harga_paket == null ? 0 : row.harga_paket.toLocaleString('id-ID');
                       }
                    }
                },
                {
                    title: "Keterangan",
                    data: "keterangan"
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            if (row.id > 1){
                                return "<div class=\"d-flex justify-content-between gap-2\"><button class=\"btn btn-primary w-100\" onclick=\"editpaketmcu('" + row.id + "','" + row.kode_paket + "','" + row.nama_paket + "','" + row.harga_paket + "','" + row.akses_poli + "','" + row.keterangan + "','" + btoa(row.akses_tindakan) + "')\"><i class=\"fa fa-edit\"></i> Edit Paket MCU</button><button class=\"btn btn-danger w-100\" onclick=\"hapuspaketmcu('" + row.id + "','" + row.kode_paket + "')\"><i class=\"fa fa-trash-o\"></i> Hapus Paket MCU</button></div>";
                            }else{
                                return "";
                            }
                        }
                        return data;
                    }
                }
            ]
        });
    }); 
}
$("#kotak_pencarian_paket_mcu").on("keyup change", debounce(function() {
    $("#datatable_paketmcu").DataTable().ajax.reload();
}, 300));
$("#tambah_paket_mcu_baru").click(function() {
    isedit = false;
    $("#formulir_tambah_paket_mcu").modal("show");
});
$("#simpan_paket_mcu").click(function(event) {
    event.preventDefault();
    formValidasi.addClass('was-validated');
    if ($("#kodepaketmcu").val() == "" || $("#namapaketmcu").val() == "" || $("#hargapaketmcu").val() == "" || $("#aksespolipaketmcu").val() == "" || $("#keteranganpaketmcu").val() == "") {
        return createToast('Kesalahan Formulir', 'top-right', 'Silahkan isi semua field pada formulir terlebih dahulu sebelum anda menyimpan data harga paket MCU.', 'error', 3000);
    }
    if (hargapaketmcu.get() == 0) {
        return createToast('Kesalahan Formulir', 'top-right', 'Harga paket MCU tidak boleh 0.', 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penyimpanan Data Paket MCU</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi paket MCU <strong>'+$(namapaketmcu).val()+'</strong> ?. Jika sudah silahkan tentukan paket MCU yang akan digunakan',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                let selectedItems = [];
                $("#tabel_pemeriksaan").find("input[type='checkbox']").each(function() {
                    let row = $(this).closest("tr");
                    selectedItems.push({
                        id: row.data("id"),
                        akses: $(this).attr("name"),
                        status: $(this).is(":checked")
                    });
                });
                $.ajax({
                    url: baseurlapi + '/masterdata/' + (isedit ? 'ubahpaketmcu' : 'simpanpaketmcu'),
                    type: 'POST',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: {
                        _token: response.csrf_token,
                        id: idpaketmcu,
                        kode_paket: $("#kodepaketmcu").val(),
                        nama_paket: $("#namapaketmcu").val(),
                        harga_paket: hargapaketmcu.get(),
                        akses_poli: $("#aksespolipaketmcu").val(),
                        keterangan: $("#keteranganpaketmcu").val(),
                        selected_items: selectedItems
                    },
                    success: function(response) {
                        clearFormulirTambahPaketMcu();
                        createToast('Informasi', 'top-right', response.message, 'success', 3000);
                        $("#formulir_tambah_paket_mcu").modal("hide");
                        $("#datatable_paketmcu").DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        createToast('Kesalahan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            });
        }
    });
});
function clearFormulirTambahPaketMcu() {
    formValidasi.removeClass('was-validated');
    isedit = false;
    idpaketmcu = "";
    hargapaketmcu.set(0);
    $("#kodepaketmcu").val("");
    $("#namapaketmcu").val("");
    $("#hargapaketmcu").val("");
    $("#aksespolipaketmcu").val("");
    $("#keteranganpaketmcu").val("");
}
function hapuspaketmcu(id, nama) {
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penghapusan Data Paket MCU <strong>'+nama+'</strong></h4><p class="text-muted mx-4 mb-0">Informasi yang terkait dengan paket MCU <strong>'+nama+'</strong> tidak akan hilang tetapi tidak ditampilkan di sistem. Apakah anda yakin ingin melanjutkan proses penghapusan ini ?</p></div>',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/masterdata/hapuspaketmcu',
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: {
                        _token: response.csrf_token,
                        id: id,
                        nama_paket: nama,
                    },
                    success: function(response) {
                        isedit = false;
                        createToast('Informasi', 'top-right', response.message, 'success', 3000);
                        $("#datatable_paketmcu").DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        createToast('Kesalahan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            });
        }
    });
}
function editpaketmcu(id, kode, nama, harga, akses, keterangan, akses_tindakan) {
    isedit = true;
    idpaketmcu = id;
    $("#kodepaketmcu").val(kode);
    $("#namapaketmcu").val(nama);
    hargapaketmcu.set(harga);
    $("#keteranganpaketmcu").val(keterangan);
    let akses_tindakan_decode = JSON.parse(atob(akses_tindakan));
    akses_tindakan_decode.forEach(item => {
        let checkbox = $("#tabel_pemeriksaan").find("input[name='" + item.akses + "']");
        if (checkbox.length) {
            checkbox.prop("checked", item.status === "true");
            let row = checkbox.closest("tr");
            let hasRowspan = row.find("td[rowspan]").length > 0;
            let targetColumns = hasRowspan 
                ? row.find("td:nth-child(2), td:nth-child(3)") 
                : row.find("td:nth-child(1), td:nth-child(2)");
            let checked = checkbox.is(":checked");
            targetColumns.css("background-color", checked ? "#add8e6" : "");
        }
    });
    
    $("#formulir_tambah_paket_mcu").modal("show");
}