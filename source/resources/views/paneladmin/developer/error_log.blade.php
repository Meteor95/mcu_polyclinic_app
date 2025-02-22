@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header" style="text-align: center">
            <h4>Error Log Area</h4>
            <span style="font-size: 16px;">
            <img src="{{ asset('mofi/assets/images/gif/animasi_kucing_error_week.gif') }}" style="float: left; width: 150px; height: auto; margin-right: 10px;">
            Halaman error_log itu ibarat catatan harian yang ngejelasin segala macam masalah atau error yang terjadi di sistem atau aplikasi. Biasanya, error log ini muncul kalau ada sesuatu yang gak beres, misalnya server error, bug, atau kesalahan lainnya.

            Di halaman error_log, kita bisa lihat informasi penting, kayak jenis kesalahan, waktu terjadinya, dan kadang-kadang detail teknis lainnya yang ngebantu developer buat ngeliat akar masalahnya. Jadi, buat para developer, ini semacam petunjuk atau clue biar bisa benerin masalah yang muncul.

            Bisa dibilang, error log ini kayak alarm atau tanda bahaya buat ngingetin kalau ada sesuatu yang gak jalan, dan developer bisa langsung perbaikin deh.
            </span>
            <div class="row">
            </div>
        </div>
        <div class="card-body">
          <table class="table table-striped table-bordered table-hover table-padding-sm" id="tabel_error_log_app"></table>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modal_trace_error_log_app" tabindex="-1" role="dialog" aria-labelledby="modal_trace_error_log_appLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Silahkan Trace Secara Detail Error Log</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-body">
                      <textarea id="trace_error_log_app" class="form-control"></textarea>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{ asset('vendor/erayadigital/developer/error_log.js') }}"></script>
@endsection