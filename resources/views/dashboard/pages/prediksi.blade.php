@extends('dashboard.partials.main')

@section('content')
    <div class="row mt-2 p-2">
        <!-- Nav Tabs -->
        <ul class="nav nav-pills" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="bulanan-tab" data-bs-toggle="tab" href="#bulanan" role="tab"
                    aria-controls="bulanan" aria-selected="true">Prediksi Bulanan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="data-baru-tab" data-bs-toggle="tab" href="#data-baru" role="tab"
                    aria-controls="data-baru" aria-selected="false">Prediksi Data Baru</a>
            </li>
        </ul>
        <!-- Tab content -->
        <div class="tab-content mt-2" id="myTabContent">
            <!-- Tab 1: Prediksi Bulanan -->
            <div class="tab-pane fade show active" id="bulanan" role="tabpanel" aria-labelledby="bulanan-tab">
                <div class="row">


                    <div class="col-md-12 mb-3">
                        <button id="btnPrediksi" class="btn btn-primary float-end">
                            <i class="fas fa-plus me-2"></i>Prediksi
                        </button>
                    </div>

                    <!-- Card Prediksi Bulanan -->
                    <div class="card mb-3">
                        <div class="card-header">Data Prediksi Bulanan</div>
                        <div class="card-body">
                            <table class="table table-striped w-100" id="tablePrediksi">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Nama Beras</th>
                                        <th>Harga Aktual</th>
                                        <th>Harga Prediksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataPrediksi as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->bulan }}-{{ $item->tahun }}</td>
                                            <td>{{ $item->beras->nama_beras }}</td>
                                            <td>Rp {{ number_format($item->harga_aktual, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->harga_prediksi, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Card Evaluasi Model -->
                    <div class="card">
                        <div class="card-header">Evaluasi Model</div>
                        <div class="card-body">
                            @if ($evaluasi)
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>MAE:</strong> {{ number_format($evaluasi->mae, 2) }}
                                    </li>
                                    <li class="list-group-item"><strong>MSE:</strong> {{ number_format($evaluasi->mse, 2) }}
                                    </li>
                                    <li class="list-group-item"><strong>RMSE:</strong>
                                        {{ number_format($evaluasi->rmse, 2) }}
                                    </li>
                                    <li class="list-group-item"><strong>R2 Score:</strong>
                                        {{ number_format($evaluasi->r2_score, 2) }}</li>
                                    <li class="list-group-item"><strong>Akurasi:</strong>
                                        {{ number_format($evaluasi->akurasi, 2) }}%</li>
                                </ul>
                            @else
                                <p>Belum ada evaluasi model.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Prediksi Data Baru -->
            <div class="tab-pane fade" id="data-baru" role="tabpanel" aria-labelledby="data-baru-tab">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Form Prediksi Harga Beras</h4>
                        <form id="prediksiForm">
                            <div class="mb-3">
                                <label for="nama_beras" class="form-label">Nama Beras</label>
                                <select class="form-select" id="nama_beras" required>
                                    <option value="" disabled selected>Pilih Nama Beras</option>
                                    @foreach ($berasList as $beras)
                                        <option value="{{ $beras->nama_beras }}" data-id="{{ $beras->id }}">
                                            {{ $beras->nama_beras }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" class="form-control" id="tahun" required>
                            </div>

                            <div class="mb-3">
                                <label for="bulan" class="form-label">Bulan</label>
                                <select class="form-select" id="bulan" required>
                                    <option value="Januari">Januari</option>
                                    <option value="Februari">Februari</option>
                                    <option value="Maret">Maret</option>
                                    <option value="April">April</option>
                                    <option value="Mei">Mei</option>
                                    <option value="Juni">Juni</option>
                                    <option value="Juli">Juli</option>
                                    <option value="Agustus">Agustus</option>
                                    <option value="September">September</option>
                                    <option value="Oktober">Oktober</option>
                                    <option value="November">November</option>
                                    <option value="Desember">Desember</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="hari_besar" class="form-label">Hari Besar</label>
                                <select class="form-select" id="hari_besar" required>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="curah_hujan" class="form-label">Curah Hujan (mm)</label>
                                <input type="number" step="0.1" class="form-control" id="curah_hujan" required>
                            </div>

                            <div class="mb-3">
                                <label for="suhu" class="form-label">Suhu (°C)</label>
                                <input type="number" step="0.1" class="form-control" id="suhu" required>
                            </div>

                            <div class="mb-3">
                                <label for="kelembaban" class="form-label">Kelembaban (%)</label>
                                <input type="number" step="0.1" class="form-control" id="kelembaban" required>
                            </div>

                            <button type="submit" class="btn btn-primary" id="btnPrediksi">Prediksi</button>
                        </form>
                    </div>
                </div>

                <!-- Notifikasi sukses atau error -->
                <div id="message" class="mt-3"></div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('prediksiForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah form submit default

            // Ambil data dari form
            const namaBeras = document.getElementById('nama_beras').value;
            const tahun = document.getElementById('tahun').value;
            const bulan = document.getElementById('bulan').value;
            const hariBesar = document.getElementById('hari_besar').value;
            const curahHujan = document.getElementById('curah_hujan').value;
            const suhu = document.getElementById('suhu').value;
            const kelembaban = document.getElementById('kelembaban').value;

            const berasId = document.querySelector('#nama_beras option:checked').getAttribute('data-id');

            // Matikan tombol prediksi agar tidak bisa diklik lagi
            const btnPrediksi = document.getElementById('btnPrediksi');
            btnPrediksi.disabled = true;
            btnPrediksi.textContent = 'Memproses...';

            // Kirim data ke API Flask
            fetch('http://localhost:5000/predict', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        beras_id: berasId,
                        nama_beras: namaBeras,
                        tahun: tahun,
                        bulan: bulan,
                        hari_besar: hariBesar,
                        curah_hujan: curahHujan,
                        suhu: suhu,
                        kelembaban: kelembaban,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Tampilkan hasil prediksi
                    const messageDiv = document.getElementById('message');
                    if (data.prediksi_harga) {
                        messageDiv.innerHTML =
                            `<div class="alert alert-success">Prediksi harga beras '${data.nama_beras}' adalah Rp ${data.prediksi_harga}</div>`;
                    } else {
                        messageDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    }

                    // Aktifkan kembali tombol prediksi
                    btnPrediksi.disabled = false;
                    btnPrediksi.textContent = 'Prediksi';
                })
                .catch(error => {
                    const messageDiv = document.getElementById('message');
                    messageDiv.innerHTML =
                        `<div class="alert alert-danger">Terjadi kesalahan: ${error.message}</div>`;

                    // Aktifkan kembali tombol prediksi
                    btnPrediksi.disabled = false;
                    btnPrediksi.textContent = 'Prediksi';
                });
        });
        $(document).ready(function() {
            $('#tablePrediksi').DataTable({
                autoWidth: false,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Cari...",
                    decimal: ",",
                    thousands: ".",
                }
            });

            $('.dataTables_filter input[type="search"]').css({
                marginBottom: "10px"
            });

            $('#btnPrediksi').click(function() {
                $(this).prop('disabled', true).text('Memproses...');
                fetch('http://localhost:5000/train', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal memproses prediksi');
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message || 'Prediksi berhasil!');
                        location.reload();
                    })
                    .catch(error => {
                        alert('Terjadi kesalahan: ' + error.message);
                        $('#btnPrediksi').prop('disabled', false).text('Prediksi');
                    });
            });
        });
    </script>
@endsection
