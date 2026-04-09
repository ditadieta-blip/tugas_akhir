@extends('layouts.anggota')

@section('content')

<!-- ✅ WAJIB ADA (FIX ERROR CSRF) -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
    <h3>Iuran Senam</h3>

    <table class="table">
        <tr>
            <th>Tanggal</th>
            <th>Tempat</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        
        @foreach($senam as $s)
        @php
            $bayar = $pembayaran->where('id_senam', $s->id_senam)->first();
        @endphp
        <tr>
            <td>{{ $s->tanggal }}</td>
            <td>{{ $s->tempat }}</td>
            <td>
                @if($bayar && $bayar->status == 'success')
                    <span class="badge bg-success">Lunas</span>
                @else
                    <span class="badge bg-warning">Belum Bayar</span>
                @endif
            </td>
            <td>
                {{-- ✅ PERBAIKAN LOGIKA --}}
                @if(!$bayar || $bayar->status != 'success')
                    <button onclick="bayar({{ $s->id_senam }})" class="btn btn-primary">
                        Bayar 2.500
                    </button>
                @else
                    ✔
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>

<!-- MIDTRANS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
function bayar(id) {

    // ✅ AMANKAN CSRF (BIAR TIDAK ERROR LAGI)
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');

    if (!tokenMeta) {
        alert('CSRF token tidak ditemukan!');
        return;
    }

    const csrfToken = tokenMeta.getAttribute('content');

    fetch('/anggota/iuran/bayar/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(res => {
        console.log("STATUS:", res.status);
        return res.json();
    })
    .then(data => {
        console.log("DATA:", data);

        if (data.snap_token) {
            snap.pay(data.snap_token, {
                onSuccess: function() {
                    alert('Pembayaran berhasil');
                    location.reload();
                },
                onPending: function() {
                    alert('Menunggu pembayaran');
                    location.reload();
                },
                onError: function() {
                    alert('Gagal bayar');
                }
            });
        } else {
            alert(data.error || 'Terjadi error dari server');
        }
    })
    .catch(err => {
        console.log(err);
        alert('Request gagal');
    });
}
</script>

@endsection