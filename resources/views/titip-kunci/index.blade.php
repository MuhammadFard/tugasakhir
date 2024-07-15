@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Titip Kunci</h2>
    <a href="{{ route('titip-kunci.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal Masuk</th>
                <th>Nama Petugas</th>
                <th>No. Pol</th>
            </tr>
        </thead>
        <tbody>
            @foreach($titipKuncis as $titipKunci)
            <tr>
                <td>{{ $titipKunci->tanggal_masuk }}</td>
                <td>{{ $titipKunci->nama_petugas }}</td>
                <td>{{ $titipKunci->no_pol }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection