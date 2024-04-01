@extends('layout.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Level')
@section('content_header_title', 'Level')
@section('content_header_subtitle', 'Add Level')

{{-- Content body: main page content --}}
@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Level</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form method="post" action="tambah_simpan">
            {{ csrf_field() }}
            <!-- text input -->
            <div class="form-group">
                <label>Level Kode</label>
                <input type="text" class="form-control" name="level_kode" placeholder="Masukkan Level Kode">
            </div>
            <div class="form-group">
                <label>Level Nama</label>
                <input type="text" class="form-control" name="level_nama" placeholder="Masukkan Level Nama">
            </div>
            <div class="card-footer">
                <a href="../level" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Add Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
