@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Fitur Aplikasi Pengaturan Hak Akses</h4><span>Pada tampilan ini anda dapat mengatur hak akses pada sistem MCU Artha Medica. Jika pengguna lain saat login tidak dapat melihat menu yang seharusnya dapat diakses, maka dapat disesuaikan pada halaman ini.</span>
        </div>
        <div class="card-body">
          <div class="col-md-12 position-relative">
            <label class="form-label" for="nama_hakakses">Nama Hak Akses Sistem</label>
            <input class="form-control" type="text" placeholder="Ex: Administrator" id="nama_hakakses">
            <label class="form-label" for="keterangan">Keterangan Hak Akses</label>
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Ex: Hak akses membuka semua fitur pada aplikasi MCU Artha Medica. Harap berhati hati jikalau menggunakan hak akses ini untuk pengguna yang tidak bertanggung jawab" id="keterangan">
                <button class="btn btn-outline-success" id="simpan_hakakses" type="button">Simpan Data</button>
            </div>
          </div>
          <div class="title text-center mt-2">
            <h2 class="sub-title">Tabel Informasi Hak Akses Sistem</h2>
          </div>
          <div class="col-md-12">
            <div class="table theme-scrollbar">
              <input type="text" class="form-control" id="kotak_pencarian" placeholder="Ketikan Nama Hak Akses...">
              <table class="display" id="datatables_permission">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Hak Akses</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
@section('css_load')
@component('komponen.css.datatables')
@endcomponent
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script>
 $(document).ready(function () {
  tabel_hakakses();
});
function tabel_hakakses(){
  $.get('/generate-csrf-token', function(response) {
    $("#datatables_permission").DataTable({
        language:{
            "paginate": {
                "first": '<i class="ri-arrow-go-back-line"></i>', 
                "last": '<i class="ri-arrow-go-forward-line"></i>', 
                "next": '<i class="ri-arrow-right-circle-line"></i>', 
                "previous": '<i class="ri-arrow-left-circle-line"></i>',
            },
        },
        dom: '<"top"ip>rt<"clear">', 
        scrollCollapse: true,
        scrollX: true,
        bFilter: false,
        bInfo : true,
        ordering: false,
        bPaginate: true,
        bProcessing: true, 
        serverSide: true,
        ajax: {
            "url": baseurlapi + '/permission/daftarhakakses',
            "type": "GET",
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));},
            "data": function (d) {
                d._token = response.csrf_token;
                d.parameter_pencarian = $('#kotak_pencarian').val();
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
                title: "Nama Hak Akses",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return "<a href=\"javascript:void(0)\" id=\"detailinformasi"+row.id_user+"\" onclick=\"detailinformasi('"+row.username+"','"+row.id_user+"')\">"+row.nip+"</a>";
                    }
                    return data;
                }
            }, 
            {
                title: "Keterangan",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return "<a href=\"javascript:void(0)\" id=\"detailinformasi"+row.id_user+"\" onclick=\"detailinformasi('"+row.username+"','"+row.id_user+"')\">"+row.nama_lengkap+"</a>";
                    }
                    return '';
                }
            {
                title: "Aksi",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return "<div class=\"d-flex justify-content-between gap-2\"><button id=\"editinformasi"+row.id_user+"\" onclick=\"editinformasi('"+row.username+"','"+row.id_user+"')\" class=\"btn btn-outline-success w-100\"><i class=\"ri-shield-user-line\"></i> Ubah Data</button ></div>";
                    }
                    return '';
                }
            },
        ],
    });
});
}
$("#simpan_hakakses").click(function(){  
  if ($("#nama_hakakses").val() == "" || $("#keterangan").val() == "") {
    return createToast('Kesalahan Formulir','top-right', 'Nama Hak Akses dan Keterangan tidak boleh kosong. Silahkan isi terlebih dahulu. Contoh : Threadmill, Perawatan, Dll', 'error', 3000);
  }  
  Swal.fire({
    title: "Simpan Hak Akses <strong>" + $("#nama_hakakses").val() + "</strong>?",
    text: "Pastikan data yang anda masukkan sudah benar sesuai dengan kebutuhan sistem anda. Anda dapat merubah data hak akses pada halaman ini jika ada kesalahan dalam menentukan hak akses pada sistem. Hak akses yang sudah terpakai pada pengguna tidak bisa dihapus.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Ya, Simpan!",
    cancelButtonText: "Batal"
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
          url: "{{ url('api/v1/permission/tambahpermission') }}",
          type: "POST",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + localStorage.getItem('token_ajax')
          },
          data: {
              nama_hakakses: $("#nama_hakakses").val(),
              keterangan: $("#keterangan").val()
          },
          success: function(response){
            createToast('Sukses Eksekusi Proses','top-right', response.message, 'success', 3000);
            $("#datatables_permission").DataTable().ajax.reload();
          },
          error: function(xhr, status, error) {
            createToast('Gagal Eksekusi Proses','top-right', "Pesan Kesalahan : "+xhr.responseJSON.message, 'error', 3000);
          }
      });
    }
  });
});
</script>
@endsection