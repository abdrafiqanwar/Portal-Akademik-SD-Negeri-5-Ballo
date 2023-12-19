@extends('layout/sidebar')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/setting/akun.css') }}">
@endsection

@section('content')
    <div class="container">
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
        <form action="{{ route('addTahun') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    Tahun Ajaran / Semester <i class="fa-solid fa-asterisk fa-2xs" style="color: #f60000;"></i>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <input name='school_year' type="text" class="form-control border-secondary"
                            placeholder="Masukkan Tahun Ajaran dan Semester" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-8"></div>
                <div class="col-md-1">
                    <a href="{{ route('setting') }}" type="button" class="btn btn-light"
                        style="box-shadow: 0px 4px 4px 0px #00000040; border-radius: 12px; color:#3182FB">Batal</a>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        style="box-shadow: 0px 4px 4px 0px #00000040; border-radius: 12px">Simpan</button>
                </div>
            </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <center>
                    <div class="mt-3">
                        <img src="{{ asset('img/alert_biru.svg') }}" alt="">
                    </div>
                    <div class="modal-body p-0">
                        <p class="m-0 mt-1" style="font-weight: bold; font-size: 15px">Are you sure want to saving?</p>
                        <p class="m-0 mt-1" style="font-size: 12px">Are you sure want to saving?</p>
                    </div>
                    <div class="p-3">
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            style="border: 1px #3182FB solid">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </center>
            </div>
        </div>
    </div>
</form>
</div>
@endsection
