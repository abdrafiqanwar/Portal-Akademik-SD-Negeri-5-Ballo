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
  </div>

  <table class="table mt-3">
    <thead>
      <tr>
        <th scope="col" class="text-center">No.</th>
        <th scope="col" class="text-center">Nama Guru</th>
        <th scope="col" class="text-center">NIP</th>
        <th scope="col" class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($filteredData as $index => $data)
      <tr>
        <td scope="row" class="text-center">{{ $index + 1 }}</td>
        <td scope="row" class="text-center">{{ $data->name }}</td>
        <td scope="row" class="text-center">{{ $data->user_number }}</td>
        <td scope="row" class="text-center">
          <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal_{{ $index }}" data-user-number="{{ $data->user_number }}" >
            <img src="{{ asset('img/trash.svg') }}" alt="">
          </button>
        </td>
      </tr>

      <div class="modal fade" id="exampleModal_{{ $index }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <center>
                    <div class="mt-3">
                        <img src="{{ asset('img/alert_merah.svg') }}" alt="">
                    </div>
                    <div class="modal-body p-0">
                        <p class="m-0 mt-1" style="font-weight: bold; font-size: 15px">Apakah Anda yakin ingin menghapus?</p>
                        <p class="m-0 mt-1" style="font-size: 12px">Data yang telah dihapus tidak dapat dikembalikan</p>
                    </div>
                    <div class="p-3">
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            style="border: 1px #3182FB solid">Tidak</button>
                        <button type="submit" form="delete-form_{{ $index }}" class="btn btn-danger">Ya</button>
                    </div>
                </center>
            </div>
        </div>
      </div>

      <form id="delete-form_{{ $index }}" method="POST" action="{{ route('delete-teacher') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="user_number" value="{{ $data->user_number }}">
      </form>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
