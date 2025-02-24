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
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="3" checked> P.MRP</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="3" checked> S.MRP</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="4" checked> RM Cost</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="5" checked> RM %</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="6" checked> Packing Cost</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="7" checked> Packing %</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="8" checked> Total</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="9" checked> %</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="10" checked> Overhead %</label>
                    <label class="dropdown-item"><input type="checkbox" class="column-toggle" data-column="11" checked> Overhead</label>
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
            <option value="" disabled selected>Margin</option>
            <!-- <option value="low" style="background-color:rgb(245, 171, 177); color:rgb(183, 18, 34);">Low</option>
            <option value="medium" style="background-color:rgb(228, 206, 133); color:rgb(186, 141, 6);">Medium</option>
            <option value="high" style="background-color:rgb(119, 220, 143); color: #155724;">High</option> -->
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
                                    <th scope="col" style="color:white;">S.MRP</th>
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
                                $sellingRate = $report->S_MRP * 0.75;
                                $beforeTax = ($sellingRate * 100) / 118;
                                $OH_PERC = ($report->OH_Cost/$report->TOTAL) * 100;
                                $MARGINAMOUNT = $beforeTax-$report->COST;
                                $marginPerc = ($MARGINAMOUNT/$beforeTax)*100;
                                @endphp
                                <tr data-rm="{{ strtolower($report->RM_Names) }}" data-pm="{{ strtolower($report->PM_Names) }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $report->Product_Name }}</td>
                                    <!-- <td>{{ $report->S_MRP }}</td> -->
                                    <td>{{ $report->S_MRP  }}</td>
                                    <td>{{ $report->RM_Cost }}</td>
                                    <td>{{ number_format($report->RM_perc, 2) }}</td>
                                    <td>{{ $report->PM_Cost }}</td>
                                    <td>{{ number_format($report->PM_perc, 2) }}</td>
                                    <td>{{ number_format($report->TOTAL, 2) }}</td>
                                    <td>{{ number_format($report->Total_perc, 2) }}</td>
                                    <td>{{ number_format($report->OH_Cost) }}</td>
                                    <td>{{ number_format($OH_PERC, 2) }}</td>
                                    <td>{{ $report->COST }}</td>
                                    <td>{{ number_format($report->Selling_Cost, 2) }}</td>
                                    <td>18</td>
                                    <td>{{ number_format($beforeTax, 2) }}</td>
                                    <td>{{ number_format($MARGINAMOUNT, 2) }}</td>
                                    <td>{{ number_format($marginPerc, 2) }}</td>
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

            doc.save('filtered_report.pdf');
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


        // Export to Excel function
        document.getElementById('exportBtn').addEventListener('click', function() {
            const table = document.getElementById('reportTable');
            const rows = Array.from(table.querySelectorAll('tr')); // Get all rows
            const visibleData = [];
            let serialNumber = 1; // Initialize serial number

            // Iterate through each row in the table and check if it's visible
            rows.forEach((row) => {
                if (row.style.display !== 'none') { // Only include rows that are visible
                    const cells = Array.from(row.children); // Get all cells in the row
                    const visibleCells = cells
                        .filter((cell, index) => {
                            const columnCheckbox = document.querySelector(`.column-toggle[data-column="${index + 1}"]`);
                            return columnCheckbox && columnCheckbox.checked; // Include only visible columns
                        })
                        .map((cell) => cell.innerText.trim()); // Get text content of visible cells

                    visibleCells.unshift(serialNumber.toString()); // Add serial number to row
                    visibleData.push(visibleCells); // Add filtered row data
                    serialNumber++; // Increment serial number
                }
            });

            // Convert data to workbook
            const ws = XLSX.utils.aoa_to_sheet(visibleData); // Create worksheet
            const wb = XLSX.utils.book_new(); // Create workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Report'); // Append worksheet to workbook

            // Export to Excel file
            XLSX.writeFile(wb, 'filtered_report.xlsx'); // Trigger download
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
            const marginSortOrder = marginFilter.value; // Get sorting order (asc/desc)

            let rowsArray = Array.from(tableRows); // Convert NodeList to array for sorting

            rowsArray.forEach(row => {
                const productName = row.children[1].textContent.toLowerCase();
                const rmNames = row.getAttribute('data-rm'); // RM Names stored in data attribute
                const pmNames = row.getAttribute('data-pm');
                const rmPercent = parseFloat(row.children[4].textContent);
                const pmPercent = parseFloat(row.children[6].textContent);
                const margin = parseFloat(row.children[7].textContent); // Convert to number

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

                const marginMatch = marginFilter.value === "" || ["asc", "desc"].includes(marginFilter.value) || marginFilter.value === row.children[7].textContent;

                // Show or hide row based on filters
                row.style.display = (searchMatch && rmMatch && pmMatch && marginMatch) ? '' : 'none';
            });

            // Sorting Logic for Margin
            if (marginSortOrder === "asc" || marginSortOrder === "desc") {
                rowsArray.sort((a, b) => {
                    const marginA = parseFloat(a.children[7].textContent);
                    const marginB = parseFloat(b.children[7].textContent);
                    return marginSortOrder === "asc" ? marginA - marginB : marginB - marginA;
                });

                // Reorder rows in the table
                const tbody = document.querySelector("#reportTable tbody");
                rowsArray.forEach(row => tbody.appendChild(row));
            }

            // Recalculate serial numbers after filtering and sorting
            updateSerialNumbers();
        };


        // Event Listeners for Filters
        searchInput.addEventListener('input', applyFilters);
        searchCategory.addEventListener('change', applyFilters);
        rmRangeType.addEventListener('change', applyFilters);
        rmValue.addEventListener('input', applyFilters);
        pmRangeType.addEventListener('change', applyFilters);
        pmValue.addEventListener('input', applyFilters);
        marginFilter.addEventListener('change', applyFilters);

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