@extends('layouts.main')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Data Voting</h4>
        <a href="{{ route('admin.voting.create') }}" class="btn btn-primary">
            + Tambah Voting
        </a>
    </div>

    <div class="row">
        @forelse($voting as $v)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded-3">
                    
                    <div class="card-body">
                        <h5 class="card-title fw-bold">
                            {{ $v->judul }}
                        </h5>

                        <p class="mb-1">
                            <strong>Mulai:</strong> {{ $v->mulai }}
                        </p>
                        <p class="mb-2">
                            <strong>Selesai:</strong> {{ $v->selesai }}
                        </p>

                        {{-- Status --}}
                        <span class="badge 
                            {{ $v->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $v->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>

                        <hr>

                        <div class="d-flex justify-content-between">
                            
                            {{-- Tombol Buka --}}
                            <form method="POST" action="{{ url('/admin/voting/buka/'.$v->id_voting) }}">
                                @csrf
                                <button class="btn btn-success btn-sm"
                                    {{ $v->is_active ? 'disabled' : '' }}>
                                    Buka
                                </button>
                            </form>

                            {{-- Tombol Tutup --}}
                            <form method="POST" action="{{ url('/admin/voting/tutup/'.$v->id_voting) }}">
                                @csrf
                                <button class="btn btn-danger btn-sm"
                                    {{ !$v->is_active ? 'disabled' : '' }}>
                                    Tutup
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Belum ada data voting
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection