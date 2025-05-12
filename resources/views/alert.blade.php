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

    <!-- Filter Section -->
    <!-- Filter Section -->
    <!-- <div class="d-flex align-items-center px-4 py-3">
        <span class="me-3" style="color: gray;">
            <i class="bi bi-filter"></i> Filters
        </span>

        <!-- Combined Search Field with Dropdown -->
    <!-- <div class="me-2 align-items-center d-flex">
            <div class="input-group" style="width: 250px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by" style="flex: 1;">
                <select class="form-select me-2" id="searchCategory" style="width: 30px;">
                    <option value="product">Product</option>
                    <option value="rm">Raw Material</option>
                    <option value="pm">Packing Material</option>
                </select>
            </div>
        </div> -->


    <!-- RM% Range Filter -->
    <!-- <div class="d-flex align-items-center me-2">
            <div class="input-group" style="width: 200px;">
                <input type="number" id="rmValue" class="form-control" placeholder="RM%" style="flex: 1;">
                <select class="form-select" id="rmRangeType" style="width: 40px;">
                    <option value="above">Above</option>
                    <option value="below">Below</option>
                </select>
            </div>
        </div> -->


    <!-- PM% Range Filter -->
    <!-- <div class="d-flex align-items-center me-2">
            <div class="input-group" style="width: 200px;">
                <input type="text" id="pmValue" class="form-control" placeholder="PM%" style="flex: 1;">
                <select class="form-select" id="pmRangeType" style="width: 40px;">
                    <option value="above">Above</option>
                    <option value="below">Below</option>
                </select>
            </div>
        </div> -->


    <!-- Margin Dropdown -->
    <!-- <select
            class="form-select me-2"
            id="marginFilter"
            style="width: 160px; background: white; border: 1px solid #ccc; border-radius: 5px;"
            onchange="filterMargin(this.value)">
            <option value="" disabled selected>Margin %</option>
            <option value="low" style="color:rgb(183, 18, 34);"><i class="bi bi-filter"></i>⬤ Low</option>
            <!-- <option value="medium" style="color:rgb(186, 141, 6);"><i class="bi bi-filter"></i> Medium</option> -->
    <!-- <option value="high" style="color: #155724;">⬤ High</option>
            <option value="asc">Sort Ascending</option>
            <option value="desc">Sort Descending</option>
        </select>
    </div> -->

    <!-- Column Selection Dropdown -->
    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">

    </div>

    <!-- Table Section -->
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered" id="reportTable">
                            <thead class="custom-header">
                                <tr>
                                    <th scope="col" style="color:white;">S.NO</th>
                                    <th scope="col" style="color:white;">Item_Name</th>
                                    <th scope="col" style="color:white;">Item_code</th>
                                    <th scope="col" style="color:white;">Alert Type</th>
                                    <th scope="col" style="color:white;">Alerted_at</th>
                                </tr>
                            </thead>
                            <tbody id="ReportTable">
                                <tr data-rm="" data-pm="">
                                    <td>1</td>
                                    <td>Samosa</td>
                                    <td>PD0001</td>
                                    <td>Low Margin</td>
                                    <td>12.05.2025 at 9.30</td>
                                </tr>

                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
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

        // PDF Export Function
        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            const table = document.getElementById('reportTable');
            if (!table) {
                console.error('Table with ID "exportRm" not found.');
                return;
            }

            const rows = Array.from(table.querySelectorAll('tr'));
            const tableData = [];
            let serialNumber = 1;

            rows.forEach((row, rowIndex) => {
                if (row.style.display !== 'none') {
                    const cells = Array.from(row.children);
                    const rowData = [];

                    if (rowIndex > 0) {
                        rowData.push(serialNumber++);
                    } else {
                        rowData.push("S.NO");
                    }

                    cells.forEach((cell, index) => {
                        if (index !== 0) { // Skip checkboxes column
                            rowData.push(cell.innerText.trim());
                        }
                    });

                    tableData.push(rowData);
                }
            });

            // Add Table to PDF
            doc.autoTable({
                head: [tableData[0]], // Header row
                body: tableData.slice(1), // Table content
                startY: 20,
                theme: 'striped',
            });

            doc.save('report.pdf');
        });

        document.getElementById('exportBtn').addEventListener('click', function() {
            const table = document.getElementById('reportTable'); // Ensure this ID exists in your table
            if (!table) {
                console.error('Table with ID "exportRm" not found.');
                return;
            }
            console.log('Table with ID "exportRm" not found.');

            const rows = Array.from(table.querySelectorAll('tr')); // Get all rows
            const visibleData = [];
            let serialNumber = 1; // Initialize serial number

            // Iterate through each row
            rows.forEach((row, rowIndex) => {
                if (row.style.display !== 'none') { // Only include visible rows
                    const cells = Array.from(row.children);
                    const rowData = [];

                    if (rowIndex > 0) {
                        rowData.push(serialNumber++); // Auto-increment serial number
                    } else {
                        rowData.push("S.NO"); // Add "S.NO" to the header row
                    }

                    cells.forEach((cell, index) => {
                        if (index !== 0) { // Skip checkboxes column
                            rowData.push(cell.innerText.trim());
                        }
                    });

                    visibleData.push(rowData);
                }
            });

            // Convert data to workbook
            const ws = XLSX.utils.aoa_to_sheet(visibleData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Report');

            // Export as an Excel file
            XLSX.writeFile(wb, 'report.xlsx');
        });

    });
</script>