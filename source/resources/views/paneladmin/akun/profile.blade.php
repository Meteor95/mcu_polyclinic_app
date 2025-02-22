@php
use Carbon\Carbon;
@endphp
@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="container-fluid">
    <div class="user-profile">
        <div class="row">
        <!-- user profile first-style start-->
        <div class="col-sm-12">
            <div class="card hovercard text-center">
            <div class="cardheader"></div>
            <div class="user-image">
                <div class="avatar"><img alt="" src="{{ asset('mofi/assets/images/logo/logo-icon.png')}}"></div>
                <div class="icon-wrapper" onclick="ubahFoto()"><i class="icofont icofont-pencil-alt-5"></i></div>
            </div>
            <div class="info">
                <div class="row">
                <div class="col-sm-6 col-lg-4 order-sm-1 order-xl-0">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="ttl-info text-start">
                        <h6><i class="fa fa-envelope"></i> Surel Aktiv</h6><span>{{ $data['user_details']->email }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="ttl-info text-start">
                        <h6><i class="fa fa-calendar"></i> Tanggal Lahir</h6><span>{{ date('d-m-Y', strtotime($data['user_details']->tanggal_lahir)) }} 
                        @php
                            $tanggalLahir = Carbon::parse($data['user_details']->tanggal_lahir);
                            $daysPassed = round($tanggalLahir->diffInYears(Carbon::now()));
                        @endphp
                        ({{ $daysPassed }} Tahun)</span>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4 order-sm-0 order-xl-1">
                    <div class="user-designation">
                    <div class="title"><a target="_blank" href="">{{ $data['user_details']->nama_pegawai }}</a></div>
                    <div class="desc">{{ $data['user_details']->jabatan }}</div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 order-sm-2 order-xl-2">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="ttl-info text-start">
                        <h6><i class="fa fa-phone"></i> No. Telepon</h6><span>{{ $data['user_details']->no_telepon }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="ttl-info text-start">
                        <h6><i class="fa fa-location-arrow"></i> Status Pegawai</h6><span>{{ $data['user_details']->status_pegawai }}</span>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                <hr>
                <div class="social-media">
                
                </div>
                <div class="follow">
                <div class="row">
                    <div class="col-6 text-md-end border-right">
                    <div class="follow-num counter">25869</div><span>Follower</span>
                    </div>
                    <div class="col-6 text-md-start">
                    <div class="follow-num counter">659887</div><span>Following</span>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <!-- user profile first-style end-->
        </div>
    </div>
</div>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{ asset('mofi/assets/js/system/profile/profile.js') }}"></script>
@endsection
