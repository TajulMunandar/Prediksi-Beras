@extends('dashboard.partials.main')

@section('content')
    <div class="row mt-2 p-2">
        <div class="col">
            <button class="btn btn-primary float-end mb-3" data-bs-toggle="modal" data-bs-target="#tambahAktualModal">
                <i class="fas fa-plus me-2"></i>Tambah
            </button>
            <button class="btn btn-success float-end me-2" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                <i class="fas fa-download me-2"></i>Impor CSV
            </button>
        </div>
        <div class="modal fade" id="importCsvModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('data-aktual.import') }}" method="POST" enctype="multipart/form-data"
                    class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Impor Data CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Pilih File CSV</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Impor</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="/dashboard/data-aktual">Data Aktual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/dashboard/data-beras">Data Beras</a>
                </li>
            </ul>
            <div class="card-body">
                <table class="table table-striped w-100" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Beras</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Harga</th>
                            <th>Hari Besar</th>
                            <th>Curah Hujan (mm)</th>
                            <th>Suhu (°C)</th>
                            <th>Kelembaban (%)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aktuals as $index => $aktual)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $aktual->beras->nama_beras }}</td> <!-- Menampilkan Nama Beras -->
                                <td>{{ $aktual->bulan }}</td>
                                <td>{{ $aktual->tahun }}</td>
                                <td>{{ number_format($aktual->harga, 2, ',', '.') }}</td>
                                <!-- Format harga dengan desimal -->
                                <td>{{ $aktual->hari_besar ? 'Ya' : 'Tidak' }}</td> <!-- Menampilkan Hari Besar -->
                                <td>{{ number_format($aktual->curah_hujan, 2, ',', '.') }}</td>
                                <!-- Menampilkan Curah Hujan -->
                                <td>{{ number_format($aktual->suhu, 2, ',', '.') }}</td> <!-- Menampilkan Suhu -->
                                <td>{{ number_format($aktual->kelembaban, 2, ',', '.') }}</td>
                                <!-- Menampilkan Kelembaban -->
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editAktualModal{{ $aktual->id }}">
                                        Edit
                                    </button>
                                    <!-- Tombol Delete -->
                                    <form action="{{ route('data-aktual.destroy', $aktual->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger"
                                            onclick="return confirm('Yakin hapus data aktual ini?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        @foreach ($aktuals as $aktual)
            <!-- Modal Edit -->
            <div class="modal fade" id="editAktualModal{{ $aktual->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('data-aktual.update', $aktual->id) }}" method="POST" class="modal-content">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Aktual</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Nama Beras -->
                            <div class="mb-3">
                                <label for="beras_id" class="form-label">Nama Beras</label>
                                <select name="beras_id" id="beras_id" class="form-control" required>
                                    @foreach ($beras as $berasItem)
                                        <option value="{{ $berasItem->id }}"
                                            {{ $berasItem->id == $aktual->beras_id ? 'selected' : '' }}>
                                            {{ $berasItem->nama_beras }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Bulan -->
                            <div class="mb-3">
                                <label for="bulan" class="form-label">Bulan</label>
                                <input type="number" id="bulan" name="bulan" value="{{ $aktual->bulan }}"
                                    class="form-control" required>
                            </div>

                            <!-- Tahun -->
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" id="tahun" name="tahun" value="{{ $aktual->tahun }}"
                                    class="form-control" required>
                            </div>

                            <!-- Harga -->
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="number" step="0.01" name="harga" id="harga"
                                    value="{{ $aktual->harga }}" class="form-control" required>
                            </div>

                            <!-- Hari Besar -->
                            <div class="mb-3">
                                <label for="hari_besar" class="form-label">Hari Besar</label>
                                <select name="hari_besar" id="hari_besar" class="form-select" required>
                                    <option value="1" {{ $aktual->hari_besar == 1 ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ $aktual->hari_besar == 0 ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>

                            <!-- Curah Hujan -->
                            <div class="mb-3">
                                <label for="curah_hujan" class="form-label">Curah Hujan (mm)</label>
                                <input type="number" step="0.01" name="curah_hujan" id="curah_hujan"
                                    value="{{ $aktual->curah_hujan }}" class="form-control" required>
                            </div>

                            <!-- Suhu -->
                            <div class="mb-3">
                                <label for="suhu" class="form-label">Suhu (°C)</label>
                                <input type="number" step="0.01" name="suhu" id="suhu"
                                    value="{{ $aktual->suhu }}" class="form-control" required>
                            </div>

                            <!-- Kelembaban -->
                            <div class="mb-3">
                                <label for="kelembaban" class="form-label">Kelembaban (%)</label>
                                <input type="number" step="0.01" name="kelembaban" id="kelembaban"
                                    value="{{ $aktual->kelembaban }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <!-- Modal Tambah -->
        <div class="modal fade" id="tambahAktualModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('data-aktual.store') }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Aktual</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Beras</label>
                            <select name="beras_id" class="form-control" required>
                                @foreach ($beras as $berasItem)
                                    <option value="{{ $berasItem->id }}">{{ $berasItem->nama_beras }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Bulan</label>
                            <input type="number" name="bulan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="tahun">Tahun</label>
                            <input type="number" name="tahun" id="tahun" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="harga">Harga</label>
                            <input type="number" step="0.01" name="harga" id="harga" class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="hari_besar">Hari Besar</label>
                            <select name="hari_besar" id="hari_besar" class="form-select" required>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="curah_hujan">Curah Hujan (mm)</label>
                            <input type="number" step="0.01" name="curah_hujan" id="curah_hujan"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="suhu">Suhu (°C)</label>
                            <input type="number" step="0.01" name="suhu" id="suhu" class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="kelembaban">Kelembaban (%)</label>
                            <input type="number" step="0.01" name="kelembaban" id="kelembaban" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                autoWidth: false,
                scrollX: true, // penting untuk full width dengan scroll

                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    decimal: ",",
                    thousands: ".",
                }
            });

            $('.dataTables_filter input[type="search"]').css({
                marginBottom: "10px"
            });
        });
    </script>
@endsection
