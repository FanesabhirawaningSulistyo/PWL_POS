{{-- @extends('m_user/template')
@section('content')
    <div class="row mt-5 mb-5">
        <div class="col-lg-12 margin-tb">
            <div class="float-left">
                <h2> Show User</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-secondary" href="{{ route('m_user.index') }}">
                    Kembali</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>User_id:</strong>
                {{ $useri->user_id }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Level_id:</strong>
                {{ $useri->level_id }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Username:</strong>
                {{ $useri->username }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nama:</strong>
                {{ $useri->nama }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Password:</strong>
                {{ $useri->password }}
            </div>
        </div>

    </div>
@endsection --}}



{{-- Tugas 2 --}}
@extends('layout.app')
@section('subtitle', 'User')
@section('content_header_title', 'User Detail')
@section('content')
    <div class="container">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">User Detail</h3>
            </div>

                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="user_id">User ID:</label>
                            <input type="text" id="user_id" name="user_id" class="form-control" value="{{ $useri->user_id }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="level_id">Level ID:</label>
                            <input type="text" id="level_id" name="level_id" class="form-control" value="{{ $useri->level_id }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" value="{{ $useri->username }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama:</label>
                            <input type="text" id="nama" name="nama" class="form-control" value="{{ $useri->nama }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" value="{{ $useri->password }}" readonly>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <a class="btn btn-secondary mr-3" href="{{ route('m_user.index') }}">
                                Kembali</a>
                        
                        </div>
                    </form>
            </div>
        </div>
    </div>
@endsection