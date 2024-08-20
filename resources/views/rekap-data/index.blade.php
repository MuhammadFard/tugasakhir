@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Rekap Data</h2>
    <h3>Rawat Inap</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal Masuk</th>
                <th>Tanggal Keluar</th>
                <th>Nama Petugas</th>
                <th>No. Pol</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rawatInaps as $rawatInap)
            <tr>
                <td>{{ $rawatInap->tanggal_masuk }}</td>
                <td>{{ $rawatInap->tanggal_keluar }}</td>
                <td>{{ $rawatInap->nama_petugas }}</td>
                <td>{{ $rawatInap->no_pol }}</td>
                <td>{{ $rekapData->lokasi }}</td>
                <td>
                    <form action="{{ route('rawat-inap.destroy', $rawatInap->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Titip Kunci</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal Masuk</th>
                <th>Nama Petugas</th>
                <th>No. Pol</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($titipKuncis as $titipKunci)
            <tr>
                <td>{{ $titipKunci->tanggal_masuk }}</td>
                <td>{{ $titipKunci->nama_petugas }}</td>
                <td>{{ $titipKunci->no_pol }}</td>
                <td>{{ $rekapData->lokasi }}</td>
                <td>
                    <form action="{{ route('titip-kunci.destroy', $titipKunci->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection