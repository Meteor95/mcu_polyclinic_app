let formValidasi = $("#formulir_tambah_partner_amc_baru");let isedit = false;let idmembermcu = "";
let selectedCompanies = [];
$(document).ready(function(){
    daftarpartneramc();
    dafatarperusahaan();
    flatpickr("#tanggal_lahir", {
        dateFormat: "d-m-Y",
        maxDate: moment().subtract(15, 'years').format('DD-MM-YYYY'),
    });
});
function daftarpartneramc() {
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_daftarpartneramc").DataTable({
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
            pageLength: 10,
            lengthChange: true,
            ajax: {
                "url": baseurlapi + '/masterdata/daftarpartneramc',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_daftarpartneramc").val();
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
                    title: "ID Perusahaan",
                    data: "username"
                },
                {
                    title: "Email Perusahaan",
                    data: "email"
                },
                {
                    title: "Perusahaan Tergabung",
                    data: "json_perusahaan",
                    render: function(data, type, row) {
                        if (!data) return "-";
                        let listPerusahaan = (typeof data === 'string') ? JSON.parse(data) : data;
                        let html = '<div class="d-flex flex-wrap gap-1">';
                        listPerusahaan.forEach(function(item) {html += `<button type="button" class="btn btn-primary btn-xs" style="margin-right: 5px; margin-bottom: 5px;"><i class="fa fa-building"></i> ${item.nama}</button>`;});
                        html += '</div>';
                        return html;
                    }
                },
                {
                    title: "Aksi",
                    className: "dtfc-fixed-right_header",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<div class=\"d-flex justify-content-between gap-2 background_fixed_right_row\"><button class=\"btn btn-primary w-100\" onclick=\"editdaftarpartneramc('" + row.id + "','"+row.username+"','"+row.email+"','"+btoa(row.json_perusahaan)+"')\"><i class=\"fa fa-edit\"></i> Ubah</button><button class=\"btn btn-danger w-100\" onclick=\"hapusdaftarpartneramc('" + row.id + "','"+row.username+"','"+row.email+"')\"><i class=\"fa fa-trash-o\"></i> Hapus</button></div>";
                        }       
                        return data;
                    }
                }
            ]
        });
    }); 
}
$("#kotak_pencarian_daftarmembermcu").on("keyup", debounce(function() {
    $("#datatables_daftarmembermcu").DataTable().ajax.reload();
}, 500));
$('#formulir_tambah_partner_amc_baru').on('shown.bs.modal', function () {
    if ($.fn.DataTable.isDataTable('#datatables_listcompany')) {
        let table = $('#datatables_listcompany').DataTable();
        table.columns.adjust().draw(); 
    }
});

$("#tambah_partner_amc_baru").on("click", function() {
    isedit = false;
    clearformulirtambahpartneramc();
    $('#formulir_tambah_partner_amc_baru').modal('show');
    dafatarperusahaan(); 
});
$("#simpan_partner_baru").on("click", function(event) {
    event.preventDefault();
    formValidasi.addClass('was-validated');
    let dataTerpilih = getSelectedCompanies();
    if ($("#nomor_identitas").val() == "" || $("#id_perusahaan").val() == "") {
        return createToast('Kesalahan Formulir', 'top-right', 'Silahkan isi semua formulir terlebih dahulu sebelum anda menyimpan data partner AMC agar informasi tersebut dianggap benar dan akurat', 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penyimpanan Data Partner AMC</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi partner AMC <strong>'+$("#id_perusahaan").val()+'</strong> ?. Jika sudah silahkan tentukan paket MCU',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/masterdata/'+(isedit ? 'ubahpartneramc' : 'simpanpartneramc'),
                    type: 'POST',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: {
                        _token: response.csrf_token,
                        id_perusahaan: $("#id_perusahaan").val(),
                        katasandi: $("#katasandi").val(),
                        email: $("#email_member_mcu").val(),
                        json_perusahaan: JSON.stringify(dataTerpilih)
                    },
                    success: function(response) {
                        clearformulirtambahpartneramc();
                        createToast('Informasi Partner AMC', 'top-right', response.message, 'success', 3000);
                        $("#datatables_daftarpartneramc").DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Penyimpanan Data', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
});
function getSelectedCompanies() {
    let selectedData = [];
    let table = $('#datatables_listcompany').DataTable();
    $('#datatables_listcompany tbody .row-checkbox:checked').each(function() {
        let rowData = table.row($(this).closest('tr')).data();
        selectedData.push({
            id: rowData.id,
            nama: rowData.company_name
        });
    });

    return selectedData;
}
function clearformulirtambahpartneramc() {
    isedit = false;
    formValidasi.removeClass('was-validated');
    const fields = ['id_perusahaan', 'katasandi', 'email_member_mcu'];
    fields.forEach(field => $(`#${field}`).val(''));
}
function hapusdaftarpartneramc(idperusahaan,username,email) {
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Penghapusan Data Partner AMC '+username+'</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi partner AMC <strong>'+username+'</strong> dengan Email Perusahaan <strong>'+email+'</strong> ? Data tidak akan dihapus dari sistem tetapi informasi tidak ditampilkan ke aplikasi terhadap yang terhubung dengan member ini',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/masterdata/hapuspartneramc',
                    type: 'GET',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                    },
                    data: {
                        _token: response.csrf_token,
                        id: idperusahaan,
                        username: username,
                        email: email,
                    },
                    success: function(response) {
                        clearformulirtambahpartneramc()
                        $("#datatables_daftarpartneramc").DataTable().ajax.reload();
                        createToast('Informasi Partner AMC', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Penghapusan Data', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
}
function editdaftarpartneramc(id,username,email,json_perusahaan) {
    isedit = true;
    idmembermcu = id;
    const fields = {
        'id_perusahaan': username,
        'email_member_mcu': email,
    };
    Object.entries(fields).forEach(([id, value]) => {
        $(`#${id}`).val(value);
    });
   try {
        selectedCompanies = JSON.parse(atob(json_perusahaan));
    } catch (e) {
        selectedCompanies = [];
    }
    if ($.fn.DataTable.isDataTable('#datatables_listcompany')) {
        $('#datatables_listcompany').DataTable().ajax.reload();
    }
    $("#formulir_tambah_partner_amc_baru").modal("show");
}
function dafatarperusahaan() {
    $.get('/generate-csrf-token', function(response) {
        let table = $("#datatables_listcompany").DataTable({
            destroy: true,
            autoWidth: false,
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
            ajax: {
                "url": baseurlapi + '/masterdata/daftarperusahaan',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian_perusahaan_tergabung').val();
                    d.start = 0;
                    d.length = 100000;
                },
                "dataSrc": function(json) {
                    return json.data;
                }
            },
            columnDefs: [
                { targets: "_all", className: "py-1" }
            ],
            columns: [
                {
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "company_code",
                },
                {
                    render: function(data, type, row) {
                        return row.company_name + " " + (row.company_alias_name ? "(" + row.company_alias_name + ")" : "");
                    }
                },
                {
                    render: function(data, type, row) {
                        let isChecked = selectedCompanies.some(comp => comp.id === row.id) ? 'checked' : '';
                        return '<div class="text-center"><input type="checkbox" class="form-check-input row-checkbox" ' + isChecked + '></div>';
                    }
                },
            ],
            "drawCallback": function(settings) {
                $('#datatables_listcompany tbody tr').each(function() {
                    let cb = $(this).find('.row-checkbox');
                    if (cb.prop('checked')) {
                        $(this).addClass('selected-row');
                    } else {
                        $(this).removeClass('selected-row');
                    }
                });
            }
        });
        table.on('change', '.row-checkbox', function() {
            $(this).closest('tr').toggleClass('selected-row', this.checked);
        });
        $('#datatables_listcompany tbody').off('click').on('click', 'tr', function(e) {
            if ($(e.target).is('input[type="checkbox"]')) return;
            
            let $cb = $(this).find('.row-checkbox');
            $cb.prop('checked', !$cb.prop('checked')).trigger('change');
        });
    });
}
$("#kotak_pencarian_perusahaan_tergabung").on("input", function() {
   $('#datatables_listcompany').DataTable().ajax.reload();
});
$('#toogleshowpassword').on('click', function() {
    var passwordInput = $('#katasandi');
    var passwordIcon = $('#toogleshowpassword i');
    
    if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text');
        passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        passwordInput.attr('type', 'password');
        passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
});
$("#generate_id_perusahaan").on("click", function(){
    $("#id_perusahaan").val((Math.random().toString(36).substring(2) + Math.random().toString(36).substring(2)).substring(0, 10).toUpperCase());
});
$("#generate_password").on("click", function(){
    $("#katasandi").val(Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15));
});