$(document).ready(function() {
    loadDataPasien();
});

function loadDataPasien() {
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_daftarpeserta").DataTable({
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
            fixedColumns: true,
            scrollCollapse: true,
            fixedColumns: {
                right: 1,
                left: 0
            },
            bFilter: false,
            bInfo: true,
            ordering: false,
            scrollX: true,
            bPaginate: true,
            bProcessing: true,
            serverSide: true,
            ajax: {
                "url": baseurlapi + '/pendaftaran/daftarpasien',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_daftarpasien").val();
                    d.start = 0;
                    d.length = 200;
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
                    title: "Nomor MCU",
                    data: "no_transaksi"
                },
                {
                    title: "Nama Peserta",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.nama_peserta} (${row.umur}Th)`;
                        }
                        return data;
                    }
                },
                {
                    title: "Informasi MCU",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Tanggal: ${row.tanggal_transaksi}<br>Nama Paket: <span onclick="lihatDetailPaket('${row.akses_poli}')" style="cursor: pointer;color: blue;">${row.nama_paket}</span><br>`;
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
                    title: "Petugas",
                    data: "nama_pegawai"
                },
                {
                    title: "Aksi",
                    className: "dtfc-fixed-right_header",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<div class=\"d-flex justify-content-between gap-2 background_fixed_right_row\"><button class=\"btn btn-success w-100\"><i class=\"fa fa-edit\"></i></button><button class=\"btn btn-danger w-100\"><i class=\"fa fa-trash-o\"></i></button></div>";
                        }       
                        return data;
                    }
                }
            ]
        });
    });
}
function lihatDetailPaket(akses_poli) {
    const poliList = akses_poli.split(',');
    let buttons = '';
    poliList.forEach(poli => {
        buttons += `<button class="btn btn-success m-1"><i class="fa fa-check"></i> ${poli.trim()}</button>`;
    });
    $("#detail_paket_mcu").html(`<div class="text-center">${buttons}</div>`);
    $("#modal_detail_paket_mcu").modal("show");
}