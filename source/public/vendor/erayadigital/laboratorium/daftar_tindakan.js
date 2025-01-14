$(document).ready(function(){
    document.querySelectorAll('[data-state]').forEach(element => {
        element.classList.add('close_icon');
    });
    load_datatables_tindakan();
});
function load_datatables_tindakan(){
    $.get('/generate-csrf-token', function(response) {
        $("#daftar_table_tindakan").DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            bFilter: false,
            bProcessing: true,
            serverSide: true,
            scrollX: $(window).width() < 768 ? true : false,
            pageLength: $('#data_ditampilkan').val(),
            pagingType: "full_numbers",
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            ajax: {
                "url": baseurlapi + '/laboratorium/daftar_tindakan',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian').val();
                    d.length = $('#data_ditampilkan').val();
                    d.jenis_item_tampilkan = $('#jenis_item_tampilkan').val();
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
                    const infoString = 'Hal ke: ' + currentPage + ' Ditampilkan: ' + ($('#data_ditampilkan').val() < 0 ? 'Semua' : $('#data_ditampilkan').val()) + ' Baris dari Total : ' + recordsFiltered + ' Data';
                    return infoString;
                }
            },
            columns: [
                {
                    title: "No",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: "No Transaksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            let parts = row.no_nota.split('/');
                            let no_trx = parts.slice(0, 3).join('/');
                            let no_mcu = parts.slice(3).join('/');
                            return "No Nota : "+no_trx+"<br>No MCU : "+no_mcu;
                        }
                        return data;
                    }
                },
                {
                    title: "Waktu Transaksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Trx : ${moment(row.waktu_trx).format('DD-MM-YYYY HH:mm:ss')}<br>Sample : ${moment(row.waktu_trx_sample).format('DD-MM-YYYY HH:mm:ss')}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Total Transaksi",
                    render: function(data, type, row, meta) {
                        return `Pendapatan : ${row.total_transaksi.toLocaleString('id-ID')}<br>Tindakan : ${row.total_tindakan.toLocaleString('id-ID')}`;
                    }
                },
                {
                    title: "Jenis Layanan",
                    render: function(data, type, row, meta) {
                        return `${row.jenis_layanan}<br><span class="badge badge-primary">${capitalizeFirstLetter(row.status_pembayaran)}</span>`;
                    }
                },
                {
                    title: "Entitas",
                    render: function(data, type, row, meta) {
                        return `Nama Pasien : ${row.nama_peserta}<br>
                        Nama Dokter : ${row.nama_dokter}<br>
                        Nama PJ : ${row.nama_pj}<br>
                        Operator : ${capitalizeFirstLetter(row.username)}
                        `
                    }
                },
                {
                    title: "Aksi",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-between gap-2">
                                <button onclick="detail_informasi_tarif('${row.kode_item}','${row.nama_item}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> Detail
                                </button>
                                <button onclick="hapus_informasi_tarif('${row.kode_item}','${row.nama_item}')" class="btn btn-danger w-100">
                                    <i class="fa fa-trash-o"></i> Hapus
                                </button>
                            </div>`;
                        }       
                        return data;
                    }
                }
            ]
        });
    });
}