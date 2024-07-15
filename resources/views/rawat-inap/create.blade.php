@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Data Rawat Inap</h2>
    <form action="{{ route('rawat-inap.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
            <input type="datetime-local" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="{{ now()->format('Y-m-d\TH:i') }}" readonly>
        </div>
        <div class="mb-3">
            <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
            <input type="datetime-local" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required>
        </div>
        <div class="mb-3">
            <label for="nama_petugas" class="form-label">Nama Petugas</label>
            <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" value="{{ Auth::user()->username }}" readonly>
        </div>
        <div class="mb-3">
            <label for="no_pol" class="form-label">No. Pol</label>
            <input type="text" class="form-control" id="no_pol" name="no_pol" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection