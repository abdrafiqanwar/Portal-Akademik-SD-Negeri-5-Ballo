@extends('layout/sidebar')
@section('css')
<link rel="stylesheet" href="{{ asset('css/u/rekap.css') }}">
@endsection

@section('content')
<div class="container">
  <form id="filterForm" method="POST" action="{{ route('submitguru') }}">
    @csrf
    <div class="row">
      <div class="col-md-3">
        Tahun Ajaran/Semester<i class="fa-solid fa-asterisk fa-2xs" style="color: #f60000;"></i> 
        <select name='school_year' class="form-select btn-primary border-secondary" aria-label="Default select example" >
          <option selected disabled>Pilih Tahun Pelajaran</option>
          @foreach ($schoolYears as $school_year)
          <option value="{{ $school_year }}"> {{ $school_year }} </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        Kelas<i class="fa-solid fa-asterisk fa-2xs" style="color: #f60000;"></i>
        <select name='grade' class="form-select btn-primary border-secondary" aria-label="Default select example" >
          <option selected disabled>Pilih Kelas</option>
          @foreach ($grades as $grade)
          <option value="{{ $grade }}">{{ $grade }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-5">
    </div>
    <div class="col-md-2">
        <br>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
    <div>
      <center>
        <img src="{{ asset('img/no_data_guru.svg') }}" alt="" class="mt-4">
      </center>
    </div>
</div>
@endsection