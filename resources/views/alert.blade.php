@extends('layouts.headerNav')

@section('content')
<main id="main" class="main">
    <!-- Page Title -->
    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Alert History</h1>
        <!-- <button type="button" class="btn btn-primary" id="exportBtn">Export</button> -->
        <div>
            <button type="button" class="btn btn-success" id="exportBtn">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
            <button id="exportPdfBtn" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export to PDF
            </button>
        </div>
    </div>

    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-center mb-4 px-4">
        <div class="col-auto">
            <label for="alert_type" class="col-form-label">Alert Type</label>
        </div>
        <div class="col-auto">
            <select name="alert_type" id="alert_type" class="form-select">
                <option value="">All</option>
                <option value="low margin" {{ request('alert_type') == 'low margin' ? 'selected' : '' }}>Low Margin</option>
                <option value="price threshold" {{ request('alert_type') == 'price threshold' ? 'selected' : '' }}>Price Threshold</option>
                <option value="price update frequency" {{ request('alert_type') == 'price update frequency' ? 'selected' : '' }}>Price Update Frequency</option>
            </select>
        </div>


        <div class="col-auto">
            <label for="start_date" class="col-form-label">Start Date</label>
        </div>
        <div class="col-auto">
            <input type="date" name="start_date" id="start_date" class="form-control"
                value="{{ request('start_date') }}">
        </div>

        <div class="col-auto">
            <label for="end_date" class="col-form-label">End Date</label>
        </div>
        <div class="col-auto">
            <input type="date" name="end_date" id="end_date" class="form-control"
                value="{{ request('end_date') }}">
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>


    <!-- Column Selection Dropdown -->
    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">

    </div>

    <!-- Table Section -->
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="reportTable">
                            <thead class="custom-header">
                                <tr>
                                    <th scope="col" style="color:white;">S.NO</th>
                                    <th scope="col" style="color:white;">Item_Name</th>
                                    <th scope="col" style="color:white;">Item_code</th>
                                    <th scope="col" style="color:white;">Alert Type</th>
                                    <th scope="col" style="color:white;">Alert Medium</th>
                                    <th scope="col" style="color:white;">Alerted_at</th>
                                </tr>
                            </thead>
                            <tbody id="ReportTable">
                                @php $sn = ($alerts->currentPage() - 1) * $alerts->perPage() + 1; @endphp

                                @foreach($alerts as $index => $alert)
                                <tr>
                                    <td>{{ $sn++ }}</td>
                                    <td>{{ $alert['name'] }}</td>
                                    <td>{{ $alert['code'] }}</td>
                                    <td>{{ $alert['alert_type'] }}</td>
                                    <td>{{ $alert['channel'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($alert['alerted_at'])->format('d.m.Y \a\t h:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>


                        </table>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div id="showingEntries">
                                Showing {{ $alerts->firstItem() }} to {{ $alerts->lastItem() }} of {{ $alerts->total() }} entries
                                <input type="hidden" id="currentPage" value="{{ $alerts->currentPage() }}">
                                <input type="hidden" id="perPage" value="{{ $alerts->perPage() }}">
                            </div>
                            <div id="paginationWrapper">
                                @if ($alerts->total() > $alerts->perPage())
                                {{ $alerts->links('pagination::bootstrap-5') }}
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Attach event listeners to all checkboxes
        document.querySelectorAll('.column-toggle').forEach((checkbox) => {
            checkbox.addEventListener('change', toggleColumn);
        });

        document.getElementById('exportBtn').addEventListener('click', function() {
            const params = new URLSearchParams(window.location.search).toString();
            fetch(`/alerts/export?${params}`)
                .then(response => response.json())
                .then(result => {
                    const data = result.data;
                    const rows = [
                        ["S.NO", "Item_Name", "Item_code", "Alert Type", "Alert Medium", "Alerted_at"]
                    ];

                    data.forEach((alert, index) => {
                        rows.push([
                            index + 1,
                            alert.name,
                            alert.code,
                            alert.alert_type,
                            alert.channel,
                            new Date(alert.alerted_at).toLocaleString()
                        ]);
                    });

                    const ws = XLSX.utils.aoa_to_sheet(rows);
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, 'Alert_history');
                    XLSX.writeFile(wb, 'Alert_history.xlsx');
                });
        });

        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            const params = new URLSearchParams(window.location.search).toString();
            fetch(`/alerts/export?${params}`)
                .then(response => response.json())
                .then(result => {
                    const data = result.data;
                    const {
                        jsPDF
                    } = window.jspdf;
                    const doc = new jsPDF();

                    const tableData = data.map((alert, index) => [
                        index + 1,
                        alert.name,
                        alert.code,
                        alert.alert_type,
                        alert.channel,
                        new Date(alert.alerted_at).toLocaleString()
                    ]);

                    doc.autoTable({
                        head: [
                            ["S.NO", "Item_Name", "Item_code", "Alert Type", "Alert Medium", "Alerted_at"]
                        ],
                        body: tableData,
                        startY: 20,
                        theme: 'striped',
                    });

                    doc.save('Alert_history.pdf');
                });
        });
    });
</script>