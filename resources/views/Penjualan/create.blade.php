@extends('layout.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('penjualan') }}">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ url('penjualan') }}">
                @csrf
                <div class="form-group">
                    <label for="user_id">User</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <option value="">Pilih User</option>
                        @foreach($user as $usr)
                            <option value="{{ $usr->user_id }}">{{ $usr->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="pembeli">Pembeli</label>
                    <input type="text" class="form-control" id="pembeli" name="pembeli" required>
                </div>
                <div class="form-group">
                    <label for="penjualan_kode">Kode Penjualan</label>
                    <input type="text" class="form-control" id="penjualan_kode" name="penjualan_kode" value="{{ $kodePenjualan }}" required readonly>

                </div>
                <div class="form-group">
                    <label for="penjualan_tanggal">Tanggal Penjualan</label>
                    <input type="date" class="form-control" id="penjualan_tanggal" name="penjualan_tanggal" value="{{ date('Y-m-d') }}" required readonly>
                </div>
                <div class="form-group">
                    <label for="barang_id">Barang</label>
                    @foreach($barang as $brg)
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ $brg->barang_nama }}</span>
                            </div>
                            <input type="number" class="form-control" name="jumlah[]" value="0" required> {{-- Nilai default 0 --}}
                            <input type="hidden" name="barang_id[]" value="{{ $brg->barang_id }}">
                        </div>
                    @endforeach
                </div>
                
                
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection

@push('js')
<script>
    // Ambil elemen input harga dan jumlah
    var inputHarga = document.getElementById('harga');
    var inputJumlah = document.getElementById('jumlah');
    
    // Fungsi untuk mengisi nilai harga berdasarkan pilihan barang
    function isiHarga() {
        var hargaBarang = document.getElementById('barang_id').selectedOptions[0].getAttribute('data-harga');
        inputHarga.value = hargaBarang;
    }

    // Event listener untuk memanggil fungsi isiHarga saat pilihan barang diubah
    document.getElementById('barang_id').addEventListener('change', isiHarga);
    
    // Event listener untuk mematikan input harga jual dan mengisi harga saat halaman dimuat
    window.addEventListener('load', function() {
        isiHarga();
        inputHarga.disabled = true;
    });
</script>
@endpush
