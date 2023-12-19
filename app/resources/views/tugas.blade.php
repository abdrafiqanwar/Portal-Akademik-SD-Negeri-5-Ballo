@extends('layout/sidebar')
@section('css')
<link rel="stylesheet" href="{{ asset('css/u/rekap.css') }}">
@endsection
@section('content')
<div class="container">
    <form id="filterForm" method="POST" action="{{ route('stugas') }}">
        @csrf
    <div class="row">
        <div class="col-md-2">
            Kelas<i class="fa-solid fa-asterisk fa-2xs" style="color: #f60000;"></i>
            <select name='grade' class="form-select btn-primary border-secondary" aria-label="Default select example" >
              <option selected disabled>Pilih Kelas</option>
              @foreach ($dataClass as $item)
              <option value="{{ $item }}">Kelas {{ $item }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            Semester<i class="fa-solid fa-asterisk fa-2xs" style="color: #f60000;"></i>
            <select name='semester' class="form-select btn-primary border-secondary" aria-label="Default select example" >
              <option selected disabled>Pilih Semester</option>
              <option value="Ganjil">Ganjil</option>
              <option value="Genap">Genap</option>
            </select>
          </div>
          <div class="col-md-2">
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
  </form>

    <table class="table mt-2">
        <thead>
            <tr>
                <th scope="col" class="text-center">No.</th>
                <th scope="col" class="text-center">Tugas</th>
                <th scope="col" class="text-center">Mata Pelajaran</th>
                <th scope="col" class="text-center">Deadline</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataScore as $item)
            <tr>
                <td scope="row" class="text-center">{{ $loop->iteration }}</td>
                <td scope="row" class="text-center">{{ strip_tags($item->task_description) }}</td>
                <td scope="row" class="text-center">{{ $item->subject->subject }}</td>
                <td scope="row" class="text-center">{{ $item->deadline }}</td>
            </tr>
            @endforeach
        </tbody>
        </table>
</div>
@endsection
