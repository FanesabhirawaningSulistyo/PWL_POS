@extends('layout.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('penjualan/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group-row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">- Semua -</option>
                                @foreach ($user as $item)
                                    @if (($item->level_id == 1) | ($item->level_id == 2) | ($item->level_id == 3))
                                        <option value="{{ $item->user_id }}">{{ $item->username }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Username Staff</small>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username Staff</th>
                        <th>Pembeli</th>
                        <th>Kode Penjualan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            var dataPenjualan = $('#table_penjualan').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('penjualan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.user_id = $('#user_id').val();
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "user.username", className: "", orderable: false, searchable: true },
                    { data: "pembeli", className: "", orderable: true, searchable: true },
                    { data: "penjualan_kode", className: "", orderable: true, searchable: true },
                    { data: "penjualan_tanggal", className: "", orderable: true, searchable: true },
                    { data: "aksi", className: "", orderable: false, searchable: false }
                ],
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        $('<input type="text" placeholder="Search"/>')
                            .on('keyup change', function () {
                                if (column.search() !== this.value) {
                                    column
                                        .search(this.value)
                                        .draw();
                                }
                            })
                            .appendTo($(column.footer()).empty())
                            .css('width', '100%');
                    });
                }
            });

            $('#user_id').on('change', function() {
                dataPenjualan.ajax.reload();
            });
        });
    </script>
@endpush
