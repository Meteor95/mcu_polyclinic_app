$(document).ready(function() {
    loadDataPeserta();
});

function loadDataPeserta() {
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_daftarpeserta").DataTable({
            ordering: false,
            lengthChange: false,
            searching: false,
            bProcessing: true,
            serverSide: true,
            pagingType: "full_numbers",
            fixedColumns: true,
            scrollCollapse: true,
            fixedColumns: {
                right: 1,
                left: 0
            },
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            ajax: {
                "url": baseurlapi + '/pendaftaran/daftarpeserta',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_daftarpeserta").val();
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
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: "Nomor Pemesanan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.no_pemesanan
                        }
                        return data;
                    }
                },
                {
                    title: "Nomor Identifikasi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nomor_identifikasi
                        }
                        return data;
                    }
                },
                {
                    title: "Nama Peserta",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nama_peserta
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<div class=\"d-flex justify-content-between gap-2 background_fixed_right_row\"><button class=\"btn btn-success w-100\" onclick=\"validasi_peserta('" + row.no_pemesanan + "')\"><i class=\"fa fa-check\"></i> Validasi Peserta</button><button class=\"btn btn-danger w-100\" onclick=\"hapus_peserta('" + row.no_pemesanan + "','"+row.nama_peserta+"')\"><i class=\"fa fa-trash-o\"></i> Hapus Peserta</button></div>";
                        }       
                        return data;
                    }
                }
            ]
        });
    }); 
}
$("#kotak_pencarian_daftarpeserta").on('keyup', debounce(function() {
    $("#datatables_daftarpeserta").DataTable().ajax.reload();
}, 300));
function hapus_peserta(no_pemesanan, nama_peserta) {
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penghapusan Data Member '+nama_peserta+'</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi member MCU <strong>'+nama_peserta+'</strong> dengan ID <strong>'+no_pemesanan+'</strong> ? Peserta yang dihapus harus mendaftar ulang pada website jikalau ingin melanjutkan pendaftaran menjadi pasien MCU',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/pendaftaran/hapuspeserta',
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: {
                        _token: response.csrf_token,
                        nama_peserta: no_pemesanan,
                        nama_peserta: nama_peserta,
                    },
                    success: function(response) {
                        $("#datatables_daftarpeserta").DataTable().ajax.reload();
                        createToast('Informasi Peserta', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Penghapusan Data', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
}