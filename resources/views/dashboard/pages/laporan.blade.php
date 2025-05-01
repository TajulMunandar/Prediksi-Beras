@extends('dashboard.partials.main')

@section('content')
    <div class="row mt-2 p-2">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped w-100" id="myTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Beras</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Hari Besar</th>
                            <th>Curah Hujan</th>
                            <th>Suhu</th>
                            <th>Kelembaban</th>
                            <th>Harga Prediksi</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataPrediksi as $index => $prediksi)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $prediksi->beras->nama_beras }}</td>
                                <!-- Assuming 'beras' relationship is defined in your model -->
                                <td>{{ $prediksi->tahun }}</td>
                                <td>{{ $prediksi->bulan }}</td>
                                <td>{{ $prediksi->hari_besar ? 'Ya' : 'Tidak' }}</td>
                                <td>{{ $prediksi->curah_hujan }}</td>
                                <td>{{ $prediksi->suhu }}</td>
                                <td>{{ $prediksi->kelembaban }}</td>
                                <td>{{ $prediksi->harga_prediksi }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
