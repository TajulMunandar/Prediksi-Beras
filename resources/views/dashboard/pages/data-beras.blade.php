@extends('dashboard.partials.main')

@section('content')
    <div class="row mt-2 p-2">
        <div class="col">
            <button class="btn btn-primary float-end mb-3" data-bs-toggle="modal" data-bs-target="#tambahBerasModal">
                <i class="fas fa-plus me-2"></i>Tambah
            </button>
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
                    <a class="nav-link" href="/dashboard/data-aktual">Data Aktual</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link active" href="/dashboard/data-beras">Data Beras</a>
                </li>
            </ul>
            <div class="card-body">
                <table class="table table-striped w-100" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Beras</th>
                            <th>Kualitas</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($beras as $index => $berasItem)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $berasItem->nama_beras }}</td>
                                <td>{{ $berasItem->kualitas }}</td>
                                <td>
                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editBerasModal{{ $berasItem->id }}">Edit</button>
                                    <form action="{{ route('data-beras.destroy', $berasItem->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger"
                                            onclick="return confirm('Yakin hapus data beras ini?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @foreach ($beras as $index => $berasItem)
            <!-- Modal Edit -->
            <div class="modal fade" id="editBerasModal{{ $berasItem->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('data-beras.update', $berasItem->id) }}" method="POST" class="modal-content">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Beras</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <label>Nama Beras</label>
                                <input type="text" name="nama_beras" value="{{ $berasItem->nama_beras }}"
                                    class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Kualitas</label>
                                <input type="text" name="kualitas" value="{{ $berasItem->kualitas }}"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <!-- Modal Tambah -->
        <div class="modal fade" id="tambahBerasModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('data-beras.store') }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Beras</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label>Nama Beras</label>
                            <input type="text" name="nama_beras" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Kualitas</label>
                            <input type="text" name="kualitas" class="form-control" required>
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
