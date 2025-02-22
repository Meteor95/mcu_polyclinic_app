$(document).ready(function() {
    tabel_error_log_app();
});
function tabel_error_log_app(){
    $.get('/generate-csrf-token', function(response) {
        $("#tabel_error_log_app").DataTable({
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
                "url": baseurlapi + '/developer/error_log_app',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
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
                            return `${row.id}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Pesan Error",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${row.messages}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Waktu Error",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `${moment(row.created_at).format('DD-MM-YYYY HH:mm:ss')}`;
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-center gap-2 background_fixed_right_row">
                                <button onclick="aksi_error_log('${row.id}',0,'${btoa(row.context)}')" class="btn btn-success"><i class="fa fa-search"></i> Trace</button>
                                <button onclick="aksi_error_log('${row.id}',1,'')" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                            </div>`;
                        }       
                        return data;
                    }
                }
            ]
        });
    });
}
function autoResize(el) {
    $(el).css("height", "auto");
    $(el).css("height", el.scrollHeight + "px");
}
function aksi_error_log(id, aksi, pesan) {
    if (aksi == 0) {
        let pesan_error_trace = JSON.parse(atob(pesan));
        $("#trace_error_log_app").val(pesan_error_trace.trace || "");
        $("#modal_trace_error_log_app").modal("show");
        setTimeout(() => {
            autoResize($("#trace_error_log_app").get(0));
        }, 500);   
    } else if (aksi == 1) {
        $.get('/generate-csrf-token', function(response) {
            Swal.fire({
                html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Hapus Log Error</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin menghapus log error ini ?</p></div></div>',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: 'orange',
                confirmButtonText: 'Oke. Hapus',
                cancelButtonText: 'Nanti Dulu!!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: baseurlapi + '/developer/error_log_app/' + id,
                        type: "DELETE",
                        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                        data: {
                            _token: response.csrf_token
                        },
                        success: function(response) {
                            if (response.success){
                                $("#modal_trace_error_log_app").modal("hide");
                                $("#tabel_error_log_app").DataTable().ajax.reload();
                                return createToast('Hapus Log Error', 'top-right', response.message, 'success', 3000);
                            }
                        },
                        error: function(xhr, status, error) {
                            return createToast('Kesalahan Log Error', 'top-right', error, 'error', 3000);
                        }
                    });
                }
            })
        })
    }   
}