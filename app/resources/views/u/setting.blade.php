@extends('layout/sidebar')
@section('css')
<link rel="stylesheet" href="{{ asset('css/setting.css') }}">
@endsection
@section('content')
<div class="container">
    <div class="body d-flex align-items-center">
        <p>Tambahkan Akun</p>
        <a href="{{ route('akun') }}" class="btn btn-primary">Choose</a>
    </div>
    <div class="body d-flex align-items-center">
        <p>Tambahkan Data</p>
        <a href="{{ route('kelas') }}" class="btn btn-primary">Choose</a>
    </div>
    <div class="body d-flex align-items-center">
        <p>Tambahkan Tahun Ajaran</p>
        <a href="{{ route('tahun') }}" class="btn btn-primary">Choose</a>
    </div>
    <div class="body d-flex align-items-center">
        <p>Data Akun Murid</p>
        <a href="{{ route('murid') }}" class="btn btn-primary">Choose</a>
    </div>
    <div class="body d-flex align-items-center">
        <p>Data Akun Guru</p>
        <a href="{{ route('guru') }}" class="btn btn-primary">Choose</a>
    </div>
</div>
@endsection