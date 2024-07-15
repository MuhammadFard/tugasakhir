@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard</h2>
    <div>
        <h3>Rawat Inap</h3>
        <form method="POST" action="{{ route('rawat-inap.store') }}">
            @csrf
            <div>
                <label for="tanggal_keluar">Tanggal Keluar:</label>
                <input type="datetime-local" name="tanggal_keluar" required>
            </div>
            <div>
                <label for="no_pol">Nomor Polisi:</label>
                <input type="text" name="no_pol" required>
            </div>
            <button type="submit">Simpan Rawat Inap</button>
        </form>
    </div>
    <div>
        <h3>Titip Kunci</h3>
        <form method="POST" action="{{ route('titip-kunci.store') }}">
            @csrf
            <div>
                <label for="no_pol">Nomor Polisi:</label>
                <input type="text" name="no_pol" required>
            </div>
            <button type="submit">Simpan Titip Kunci</button>
        </form>
    </div>
</div>
@endsection
