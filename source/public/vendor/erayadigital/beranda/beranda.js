let check_kosong_semua = false;
$(document).ready(function() {
    onload_tabel_antrian();
});
function onload_tabel_antrian(){
    $.get('/generate-csrf-token', function(response) {
        $("#daftar_status_peserta_beranda").DataTable({
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
                "url": baseurlapi + '/komponen/daftarantrian_beranda',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian_daftarpasien').val();
                    d.check_kosong_semua = check_kosong_semua;
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
                    title: "ID",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.id_antrian_peserta}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Nama Peserta",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.nama_peserta_antrian}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Tanda Vital",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.tanda_vital_status == null ? '<span class="badge bg-danger">-</span>' : 
                                (row.tanda_vital_status == 0 ? '<span class="badge bg-primary">Mengantri</span>' : 
                                (row.tanda_vital_status == 1 ? '<span class="badge bg-success">Selesai</span>' : 
                                '<span class="badge bg-warning">Proses</span>'))}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Spirometri",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.spirometri_status == null ? '<span class="badge bg-danger">-</span>' : 
                                (row.spirometri_status == 0 ? '<span class="badge bg-primary">Mengantri</span>' : 
                                (row.spirometri_status == 1 ? '<span class="badge bg-success">Selesai</span>' : 
                                '<span class="badge bg-warning">Proses</span>'))}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Audiometri",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.audiometri_status == null ? '<span class="badge bg-danger">-</span>' : 
                                (row.audiometri_status == 0 ? '<span class="badge bg-primary">Mengantri</span>' : 
                                (row.audiometri_status == 1 ? '<span class="badge bg-success">Selesai</span>' : 
                                '<span class="badge bg-warning">Proses</span>'))}`;
                        }
                        return data;
                    }
                },
                {
                    title: "EKG",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.ekg_status == null ? '<span class="badge bg-danger">-</span>' : 
                                (row.ekg_status == 0 ? '<span class="badge bg-primary">Mengantri</span>' : 
                                (row.ekg_status == 1 ? '<span class="badge bg-success">Selesai</span>' : 
                                '<span class="badge bg-warning">Proses</span>'))}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Threadmill",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.threadmill_status == null ? '<span class="badge bg-danger">-</span>' : 
                                (row.threadmill_status == 0 ? '<span class="badge bg-primary">Mengantri</span>' : 
                                (row.threadmill_status == 1 ? '<span class="badge bg-success">Selesai</span>' : 
                                '<span class="badge bg-warning">Proses</span>'))}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Rontgen Thorax",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.rontgen_thorax_status == null ? '<span class="badge bg-danger">-</span>' : 
                                (row.rontgen_thorax_status == 0 ? '<span class="badge bg-primary">Mengantri</span>' : 
                                (row.rontgen_thorax_status == 1 ? '<span class="badge bg-success">Selesai</span>' : 
                                '<span class="badge bg-warning">Proses</span>'))}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Rontgen Abdomen",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.rontgen_abdomen_status == null ? '<span class="badge bg-danger">-</span>' : 
                                (row.rontgen_abdomen_status == 0 ? '<span class="badge bg-primary">Mengantri</span>' : 
                                (row.rontgen_abdomen_status == 1 ? '<span class="badge bg-success">Selesai</span>' : 
                                '<span class="badge bg-warning">Proses</span>'))}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Farmingham Score",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.farmingham_score_status == null ? '<span class="badge bg-danger">-</span>' : 
                                (row.farmingham_score_status == 0 ? '<span class="badge bg-primary">Mengantri</span>' : 
                                (row.farmingham_score_status == 1 ? '<span class="badge bg-success">Selesai</span>' : 
                                '<span class="badge bg-warning">Proses</span>'))}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Poli Dokter",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.poli_dokter_status == null ? '<span class="badge bg-danger">-</span>' : 
                                (row.poli_dokter_status == 0 ? '<span class="badge bg-primary">Mengantri</span>' : 
                                (row.poli_dokter_status == 1 ? '<span class="badge bg-success">Selesai</span>' : 
                                '<span class="badge bg-warning">Proses</span>'))}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Kesimpulan",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.kesimpulan_status == null ? '<span class="badge bg-danger">-</span>' : 
                                (row.kesimpulan_status == 0 ? '<span class="badge bg-primary">Mengantri</span>' : 
                                (row.kesimpulan_status == 1 ? '<span class="badge bg-success">Selesai</span>' : 
                                '<span class="badge bg-warning">Proses</span>'))}`;
                        }
                        return data;
                    }
                },
            ]
        });
    }); 
}
$("#kotak_pencarian_daftarpasien").on("keyup", debounce(function() {
    $("#daftar_status_peserta_beranda").DataTable().ajax.reload();
}, 300));
$("#segarkan_antrian").on("click", function() {
    check_kosong_semua = false;
    $("#daftar_status_peserta_beranda").DataTable().ajax.reload();
});
$("#cek_kosong_semua").on("click", function() {
    check_kosong_semua = true;
    $("#daftar_status_peserta_beranda").DataTable().ajax.reload();
});