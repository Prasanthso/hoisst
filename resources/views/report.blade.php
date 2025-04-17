@extends('layouts.headerNav')

@section('content')
<main id="main" class="main">
    <!-- Page Title -->
    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Report</h1>
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
    <div class="d-flex align-items-center px-4 py-3">
        <div class="me-3 dropdown py-3">

            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Select Columns
            </button>

            <div class="dropdown-menu" style="width: 300px; padding: 10px;">
                <!-- Search Option -->
                <input type="text" id="searchColumns" class="form-control mb-2" placeholder="Search columns...">

                <!-- Select All Option -->
                <label class="dropdown-item">
                    <input type="checkbox" id="selectAllColumns" checked> Select All
                </label>

                <div id="columnsContainer">
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="1" checked> S.NO</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="2" checked> Product Name</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="3" checked> S.MRP</label>
                    <!-- <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="3" checked> P.MRP</label> -->
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="4" checked> RM Cost</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="5" checked> RM %</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="6" checked> Packing Cost</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="7" checked> Packing %</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="8" checked> Total</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="9" checked> %</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="10" checked> Overhead</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="11" checked> Overhead %</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="12" checked> Cost</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="13" checked> Selling Rate</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="14" checked> Tax</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="15" checked> Before Tax</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="16" checked> Margin Amount</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="17" checked> Margin %</label>
                </div>
            </div>
        </div>
        <span class="me-3" style="color: gray;">
            <i class="bi bi-filter"></i> Filters
        </span>

        <!-- Combined Search Field with Dropdown -->
        <div class="me-2 align-items-center d-flex">
            <div class="input-group" style="width: 250px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by" style="flex: 1;">
                <select class="form-select me-2" id="searchCategory" style="width: 30px;">
                    <option value="product">Product</option>
                    <option value="rm">Raw Material</option>
                    <option value="pm">Packing Material</option>
                </select>
            </div>
        </div>


        <!-- RM% Range Filter -->
        <div class="d-flex align-items-center me-2">
            <div class="input-group" style="width: 200px;">
                <input type="number" id="rmValue" class="form-control" placeholder="RM%" style="flex: 1;">
                <select class="form-select" id="rmRangeType" style="width: 40px;">
                    <option value="above">Above</option>
                    <option value="below">Below</option>
                </select>
            </div>
        </div>


        <!-- PM% Range Filter -->
        <div class="d-flex align-items-center me-2">
            <div class="input-group" style="width: 200px;">
                <input type="text" id="pmValue" class="form-control" placeholder="PM%" style="flex: 1;">
                <select class="form-select" id="pmRangeType" style="width: 40px;">
                    <option value="above">Above</option>
                    <option value="below">Below</option>
                </select>
            </div>
        </div>


        <!-- Margin Dropdown -->
        <select
            class="form-select me-2"
            id="marginFilter"
            style="width: 160px; background: white; border: 1px solid #ccc; border-radius: 5px;"
            onchange="filterMargin(this.value)">
            <option value="" disabled selected>Margin %</option>
            <option value="low" style="color:rgb(183, 18, 34);"><i class="bi bi-filter"></i>⬤ Low</option>
            <!-- <option value="medium" style="color:rgb(186, 141, 6);"><i class="bi bi-filter"></i> Medium</option> -->
            <option value="high" style="color: #155724;">⬤ High</option>
            <option value="asc">Sort Ascending</option>
            <option value="desc">Sort Descending</option>
        </select>
    </div>

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
                                    <th scope="col" style="color:white;">Product_Name</th>
                                    <!-- <th scope="col" style="color:white;">P.MRP</th> -->
                                    <th scope="col" style="color:white;">Present MRP</th>
                                    <th scope="col" style="color:white;">RM Cost</th>
                                    <th scope="col" style="color:white;">RM %</th>
                                    <th scope="col" style="color:white;">Packing Cost</th>
                                    <th scope="col" style="color:white;">Packing %</th>
                                    <th scope="col" style="color:white;">Total</th>
                                    <th scope="col" style="color:white;">%</th>
                                    <th scope="col" style="color:white;">Overhead</th>
                                    <th scope="col" style="color:white;">Overhead %</th>
                                    <th scope="col" style="color:white;">Cost</th>
                                    <th scope="col" style="color:white;">Selling Rate</th>
                                    <th scope="col" style="color:white;">Tax</th>
                                    <th scope="col" style="color:white;">Before Tax</th>
                                    <th scope="col" style="color:white;">Margin Amount</th>
                                    <th scope="col" style="color:white;">Margin %</th>
                                </tr>
                            </thead>
                            <tbody id="ReportTable">
                                @foreach ($reports as $index => $report)
                                @php
                                $rm_perc = $report->RM_Cost * 100 / $report->P_MRP;
                                $pm_perc = $report->PM_Cost * 100 / $report->P_MRP;
                                $oh_perc = ($report->RM_Cost + $report->PM_Cost) * $report->PM_Cost / 100;
                                $total = $report->RM_Cost + $report->PM_Cost;
                                $total_perc = ($total * 100) / $report->P_MRP;
                                $cost = $total + $report->OH_Cost + $report->MOH_Cost;

                                $sellingRate = ($report->P_MRP * 100)/(100 + $report->discount);
                                $beforeTax = ($sellingRate * 100) / (100 + $report->tax);
                                $OH_PERC = ($report->OH_Cost + $report->MOH_Cost/$total) * 100;
                                $MARGINAMOUNT = $beforeTax-$cost;
                                $marginPerc = ($MARGINAMOUNT/$beforeTax)*100;
                                @endphp
                                <tr data-rm="{{ strtolower($report->RM_Names) }}" data-pm="{{ strtolower($report->PM_Names) }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $report->Product_Name }}</td>
                                    <!-- <td>{{ $report->P_MRP }}</td> -->
                                    <td>{{ number_format($report->P_MRP, 2)  }}</td>
                                    <td>{{ number_format($report->RM_Cost, 2) }}</td>
                                    <td>{{ number_format($rm_perc, 2) }}</td>
                                    <td>{{ number_format($report->PM_Cost, 2) }}</td>
                                    <td>{{ number_format($pm_perc, 2) }}</td>
                                    <td>{{ number_format($total, 2) }}</td>
                                    <td>{{ number_format($total_perc, 2) }}</td>
                                    <td>{{ number_format($report->OH_Cost + $report->MOH_Cost, 2) }}</td>
                                    <td>{{ number_format($OH_PERC, 2) }}</td>
                                    <td>{{ number_format($cost, 2) }}</td>
                                    <td>{{ number_format($sellingRate, 2) }}</td>
                                    <td>{{ $report->tax }}</td>
                                    <td>{{ number_format($beforeTax, 2) }}</td>
                                    <td>{{ number_format($MARGINAMOUNT, 2) }}</td>
                                    <td style="background-color:
                                            {{ ($marginPerc < $report->margin) ? '#ffb3b3' :
                                                (($marginPerc > $report->margin) ? '#b3ffcc' : '#fff79a')
                                                }}">
                                        {{ number_format($marginPerc, 2) }}
                                    </td>
                                </tr>
                                @endforeach

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


        // Toggle column visibility
        function toggleColumn() {
            const columnIndex = parseInt(this.dataset.column, 10); // Get column index (1-based)
            const isVisible = this.checked;

            // Select all rows (including headers and body)
            const rows = document.querySelectorAll('table tr');
            rows.forEach((row) => {
                const cell = row.children[columnIndex - 1]; // Convert 1-based to 0-based index
                if (cell) {
                    cell.style.display = isVisible ? '' : 'none';
                }
            });

            // Recalculate serial numbers after toggle
            updateSerialNumbers();
        }

        // Select All functionality
        document.getElementById('selectAllColumns').addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.column-toggle').forEach((checkbox) => {
                checkbox.checked = isChecked;
                toggleColumn.call(checkbox); // Apply the toggle
            });
        });

        // Search functionality
        document.getElementById('searchColumns').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('#columnsContainer .dropdown-item').forEach((item) => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(query) ? '' : 'none';
            });
        });


        // Filter and Apply functionality
        const searchCategory = document.getElementById('searchCategory'); // Dropdown
        const searchInput = document.getElementById('searchInput');
        const rmRangeType = document.getElementById('rmRangeType');
        const rmValue = document.getElementById('rmValue');
        const pmRangeType = document.getElementById('pmRangeType');
        const pmValue = document.getElementById('pmValue');
        const marginFilter = document.getElementById('marginFilter');
        const tableRows = document.querySelectorAll('#reportTable tbody tr');

        const applyFilters = () => {
            const searchValue = searchInput.value.toLowerCase();
            const selectedCategory = searchCategory.value;
            const marginFilterValue = marginFilter.value; // Get selected margin filter

            let rowsArray = Array.from(tableRows); // Convert NodeList to array for sorting

            rowsArray.forEach(row => {
                const productName = row.children[1].textContent.toLowerCase();
                const rmNames = row.getAttribute('data-rm');
                const pmNames = row.getAttribute('data-pm');
                const rmPercent = parseFloat(row.children[4].textContent);
                const pmPercent = parseFloat(row.children[6].textContent);
                const marginCell = row.children[16]; // Margin % cell
                const marginColor = window.getComputedStyle(marginCell).backgroundColor; // Get background color

                let rmMatch = true;
                if (rmValue.value) {
                    const rmInputValue = parseFloat(rmValue.value);
                    rmMatch = rmRangeType.value === "above" ? rmPercent >= rmInputValue : rmPercent <= rmInputValue;
                }

                let pmMatch = true;
                if (pmValue.value) {
                    const pmInputValue = parseFloat(pmValue.value);
                    pmMatch = pmRangeType.value === "above" ? pmPercent >= pmInputValue : pmPercent <= pmInputValue;
                }

                let searchMatch = false;
                if (selectedCategory === 'product') {
                    searchMatch = productName.includes(searchValue);
                } else if (selectedCategory === 'rm') {
                    searchMatch = rmNames && rmNames.includes(searchValue);
                } else if (selectedCategory === 'pm') {
                    searchMatch = pmNames && pmNames.includes(searchValue);
                }

                // Map color codes to margin categories
                let marginCategory = "";
                if (marginColor === "rgb(255, 179, 179)") { // #ffb3b3 (low)
                    marginCategory = "low";
                } else if (marginColor === "rgb(255, 247, 154)") { // #fff79a (medium)
                    marginCategory = "medium";
                } else if (marginColor === "rgb(179, 255, 204)") { // #b3ffcc (high)
                    marginCategory = "high";
                }

                // Apply margin category filtering
                let marginMatch = true;
                if (["low", "medium", "high"].includes(marginFilterValue)) {
                    marginMatch = marginCategory === marginFilterValue;
                }

                // Show or hide row based on all applied filters
                row.style.display = (searchMatch && rmMatch && pmMatch && marginMatch) ? '' : 'none';
            });

            // Sorting Logic for Margin
            if (marginFilterValue === "asc" || marginFilterValue === "desc") {
                rowsArray.sort((a, b) => {
                    const marginA = parseFloat(a.children[16].textContent);
                    const marginB = parseFloat(b.children[16].textContent);
                    return marginFilterValue === "asc" ? marginA - marginB : marginB - marginA;
                });

                // Reorder rows in the table
                const tbody = document.querySelector("#reportTable tbody");
                rowsArray.forEach(row => tbody.appendChild(row));
            }

            // Recalculate serial numbers after filtering and sorting
            updateSerialNumbers();
        };

        // Listen for margin filter changes
        marginFilter.addEventListener('change', applyFilters);


        // Event Listeners for Filters
        searchInput.addEventListener('input', applyFilters);
        searchCategory.addEventListener('change', applyFilters);
        rmRangeType.addEventListener('change', applyFilters);
        rmValue.addEventListener('input', applyFilters);
        pmRangeType.addEventListener('change', applyFilters);
        pmValue.addEventListener('input', applyFilters);
        // marginFilter.addEventListener('change', applyFilters);

        // Function to update serial numbers dynamically
        const updateSerialNumbers = () => {
            let serialNumber = 1;
            const visibleRows = document.querySelectorAll('#reportTable tbody tr:not([style*="display: none"])'); // Select only visible rows

            visibleRows.forEach((row) => {
                row.children[0].textContent = serialNumber; // Assuming the first column is for serial numbers
                serialNumber++; // Increment serial number
            });
        };

    });
</script>