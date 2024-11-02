$(document).ready(function() {
    loadDataPeserta();
});
function loadDataPeserta() {
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_daftarpeserta").DataTable({
            dom: 'lfrtip',
            searching: false,
            lengthChange: false,
            ordering: false,
            language:{
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
            bInfo : true,
            ordering: false,
            bPaginate: true,
            bProcessing: true, 
            serverSide: true,
            ajax: {
                "url": baseurlapi + '/pendaftaran/daftarpeserta',
                "type": "GET",
                "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));},
                "data": function (d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian_daftarpeserta').val();
                    d.start = 0;
                    d.length = 10;
                },
                "dataSrc": function (json) {
                    let detailData = json.data;
                    let mergedData = detailData.map(item => {
                        return {
                            ...item,
                            recordsFiltered: json.recordsFiltered,
                        };
                    });
                    return mergedData;
                }
            },
            infoCallback: function (settings) {
                if (typeof settings.json !== "undefined"){
                    const currentPage = Math.floor(settings._iDisplayStart / settings._iDisplayLength) + 1;
                    const recordsFiltered = settings.json.recordsFiltered;
                    const infoString = 'Halaman ke: ' + currentPage + ' Ditampilkan: ' + 10 + ' Jumlah Data: ' + recordsFiltered+ ' data';
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
                        if (type === 'display') {
                            return meta.row + 1 + meta.settings._iDisplayStart;
                        }
                        return data;
                    }
                },
                {
                    title: "Nama Perizinan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.name;
                        }
                        return data;
                    }
                },
                {
                    title: "Group Izin",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.group;
                        }
                        return data;
                    }
                }, 
                {
                    title: "Keterangan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.description;
                        }
                        return data;
                    } 
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<div class=\"d-flex justify-content-between gap-2\"><button class=\"btn btn-primary w-100\" onclick=\"edithakakses('" + row.id + "','" + row.name + "', '" + row.description + "','"+ row.group+"')\"><i class=\"fa fa-edit\"></i> Edit Izin</button><button class=\"btn btn-danger w-100\" onclick=\"hapushakakses('" + row.id + "','" + row.name + "')\"><i class=\"fa fa-trash-o\"></i> Hapus Izin</button></div>";
                        }
                        return data;
                    }
                },
            ]
        });
    });
}