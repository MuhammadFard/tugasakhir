@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Rawat Inap</h2>
    <a href="{{ route('rawat-inap.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal Masuk</th>
                <th>Tanggal Keluar</th>
                <th>Nama Petugas</th>
                <th>No. Pol</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rawatInaps as $rawatInap)
            <tr>
                <td>{{ $rawatInap->tanggal_masuk }}</td>
                <td>{{ $rawatInap->tanggal_keluar }}</td>
                <td>{{ $rawatInap->nama_petugas }}</td>
                <td>{{ $rawatInap->no_pol }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection