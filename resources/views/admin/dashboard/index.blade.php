@extends('layouts.main')

@section('content')
<h4 class="mb-4 fw-bold">Dashboard</h4>

<div class="row g-4">

    <div class="col-md-4">
        <div class="dashboard-card bg-primary">
            <div class="card-body">
                <div>
                    <h6 class="card-title">Jumlah Anggota</h6>
                    <h2>{{ $jumlahAnggota }}</h2>
                </div>
                <i class="bi bi-people-fill card-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="dashboard-card bg-success">
            <div class="card-body">
                <div>
                    <h6 class="card-title">Jumlah Instruktur</h6>
                    <h2>{{ $jumlahInstruktur }}</h2>
                </div>
                <i class="bi bi-person-badge-fill card-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="dashboard-card bg-warning">
            <div class="card-body">
                <div>
                    <h6 class="card-title">Total Jadwal Wisata</h6>
                    <h2>{{ $totalWisata }}</h2>
                </div>
                <i class="bi bi-geo-alt-fill card-icon"></i>
            </div>
        </div>
    </div>

</div>

<style>
.dashboard-card {
    border-radius: 15px;
    color: white;
    height: 120px;
    position: relative;
    overflow: hidden;
    transition: 0.3s;
}

.dashboard-card .card-body {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
}

.dashboard-card h2 {
    font-weight: 700;
    margin: 5px 0 0;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.card-icon {
    font-size: 45px;
    opacity: 0.25;
}
</style>

@endsection