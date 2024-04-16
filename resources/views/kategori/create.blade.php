{{-- @extends('layout.app')

@section('subtitle', 'Kategori')
@section('content_header_title', 'Kategori')
@section('content_header_subtitle', 'Create')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Buat kategori baru</h3>
        </div>
        <form method="post" action="../kategori">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="kodeKategori">Kode Kategori</label>
                    {{-- <input type="text" class="form-control" id="kategori_kode" name="kategori_kode" placeholder="Kode Kategori"> --}}
                    {{-- <input type="text" class="form-control @error('kategori_kode') is-invalid @enderror" id="kodeKategori" name="kodeKategori" placeholder="Kode Kategori">
                    @error('kategori_kode')
                    <div class="alert alert-danger">{{$message}}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="namaKategori">Nama Kategori</label>
                    <input type="text" class="form-control" id="namaKategori" name="namaKategori" placeholder="Nama Kategori">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection  --}}

{{-- Tugas Praktikum --}}
@extends('layout.template')
@section('content')
<div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form action="{{url('kategori')}}" method="POST" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Kode Kategori</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="kategori_kode" name="kategori_kode" value="{{old('kategori_kode')}}" required>
                        @error('kategori_kode')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Nama Kategori</label>
                    <div class="col-11">
                        <input type="text" class="form-control" id="kategori_nama" name="kategori_nama" value="{{old('kategori_nama')}}" required>
                        @error('kategori_nama')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label"></label>
                    <div class="col-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a href="{{url('kategori')}}" class="btn btn-sm btn-default ml-1">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endsection
@push('css')

@endpush
@push('js')

@endpush