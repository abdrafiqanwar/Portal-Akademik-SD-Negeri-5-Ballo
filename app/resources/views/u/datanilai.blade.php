@extends('layout/sidebar')
@section('css')
<link rel="stylesheet" href="{{ asset('css/u/rekap.css') }}">
<link rel="stylesheet" href="{{ asset('css/u/breadcrump.css') }}">
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
        <ul class="breadcrumb">
         <li>{{ $schoolYear->school_year }}<span class="separator"> | </span></li>
         <li>{{ $grade->grade }}<span class="separator"></span></li>
      </ul>
      </div>
      <div class="col-md-2"></div>
    <div class="col-md-2"></div>
    <div class="col-md-2">
        @can('admin')
      <a type="button" class="btn btn-primary ms-4" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Konfirmasi</a>
        @endcan
    </div>
  </div>

  <table class="table mt-3">
    <thead>
      <tr>
        <th scope="col" class="text-center">No.</th>
        <th scope="col" class="text-center">Nama Siswa</th>
        <th scope="col" class="text-center">Nilai Akhir</th>
        <th scope="col" class="text-center">Status</th>
      </tr>
    </thead>
    <tbody>
        <form action="{{ route('submit-graduation') }}" method="POST">
            @csrf
            @foreach($results as $index => $data)
                <tr>
                <td scope="row" class="text-center">{{ $index + 1 }}</td>
                <td scope="row">{{ $data->nama }}</td>
                <td scope="row" class="text-center">{{ $data->nilai_akhir}}</td>
                <td scope="row" class="text-center">{{ $data->status}}</td>
                <input type="hidden" value="{{ $data->id }}" name="std_id[]">
                </tr>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <center>
                            <div class="mt-3">
                              <img src="{{ asset('img/alert_biru.svg') }}" alt="">
                            </div>
                        <div class="modal-body p-0">
                            <p class="m-0 mt-1" style="font-weight: bold; font-size: 15px">Are you sure want to confirm?</p>
                            <p class="m-0 mt-1" style="font-size: 12px">Are you sure want to continue?</p>
                        </div>
                        <div class="p-3">
                                <button type="button" class="btn" data-bs-dismiss="modal" style="border: 1px #3182FB solid">No</button>
                                <button type="submit" class="btn btn-primary">Yes</button>
                            </div>
                        </center>
                      </div>
                    </div>
                  </div>
            @endforeach
        </form>
    </tbody>
  </table>
</div>
@endsection
