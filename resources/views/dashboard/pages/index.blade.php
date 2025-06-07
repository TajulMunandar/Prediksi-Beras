@extends('dashboard.partials.main')

@section('content')
    <div class="row">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Data Aktual</p>
                                <h5 class="font-weight-bolder">
                                    {{ $beras }} Beras
                                </h5>

                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Data Users</p>
                                <h5 class="font-weight-bolder">
                                    {{ $user }} Users
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Data Prediksi User</p>
                                <h5 class="font-weight-bolder">
                                    Prediksi
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize">Data Prediksi Beras</h6>

                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const prediksiPerBeras = @json($prediksiPerBeras);
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var labels = Object.values(prediksiPerBeras)[0].map(item => item.bulan); // ambil label dari beras pertama

        var colors = ['#5e72e4', '#f5365c', '#2dce89', '#11cdef', '#fb6340']; // kamu bisa tambah warna sesuai jumlah beras

        var datasets = Object.entries(prediksiPerBeras).map(([key, data], index) => {
            // key contoh: "Beras A|Premium"
            var [namaBeras, kualitas] = key.split('|');

            return {
                label: `${namaBeras} (${kualitas})`, // tampilkan kualitas di label
                data: data.map(item => item.harga),
                borderColor: colors[index % colors.length],
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.4,
                fill: false,
            };
        });

        new Chart(ctx1, {
            type: "line",
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#444'
                        },
                        grid: {
                            borderDash: [5, 5]
                        }
                    },
                    x: {
                        ticks: {
                            color: '#444'
                        },
                        grid: {
                            borderDash: [5, 5]
                        }
                    }
                }
            }
        });
    </script>
@endsection
