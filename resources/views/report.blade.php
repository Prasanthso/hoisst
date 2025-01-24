@extends('layouts.headerNav')

@section('content')
<main id="main" class="main">
    <!-- Page Title -->
    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Report</h1>
        <button type="button" class="btn btn-primary" id="exportBtn">Export</button>
    </div>

    <!-- Column Selection Dropdown -->
    <div class="dropdown px-4 py-3">
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
                                    <th scope="col" style="color:white;">P.MRP</th>
                                    <th scope="col" style="color:white;">S.MRP</th>
                                    <th scope="col" style="color:white;">RM Cost</th>
                                    <th scope="col" style="color:white;">RM %</th>
                                    <th scope="col" style="color:white;">Packing Cost</th>
                                    <th scope="col" style="color:white;">Packing %</th>
                                    <th scope="col" style="color:white;">Total</th>
                                    <th scope="col" style="color:white;">%</th>
                                    <th scope="col" style="color:white;">Overhead %</th>
                                    <th scope="col" style="color:white;">Overhead</th>
                                    <th scope="col" style="color:white;">Cost</th>
                                    <th scope="col" style="color:white;">Selling Rate</th>
                                    <th scope="col" style="color:white;">Tax</th>
                                    <th scope="col" style="color:white;">Before Tax</th>
                                    <th scope="col" style="color:white;">Margin Amount</th>
                                    <th scope="col" style="color:white;">Margin %</th>
                                </tr>
                            </thead>
                            <tbody id="ReportTable">
                                <tr>
                                    <td>1.</td>
                                    <td>Carrot Cake</td>
                                    <td>160</td>
                                    <td>160</td>
                                    <td>45.3</td>
                                    <td>55.66</td>
                                    <td>19.56</td>
                                    <td>10.224</td>
                                    <td>45.55</td>
                                    <td>39.555</td>
                                    <td>35</td>
                                    <td>17.55</td>
                                    <td>17.65</td>
                                    <td>120</td>
                                    <td>18</td>
                                    <td>101.11</td>
                                    <td>23.66</td>
                                    <td>33.66</td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>Egg Puff</td>
                                    <td>160</td>
                                    <td>160</td>
                                    <td>45.3</td>
                                    <td>55.66</td>
                                    <td>19.56</td>
                                    <td>10.224</td>
                                    <td>45.55</td>
                                    <td>39.555</td>
                                    <td>35</td>
                                    <td>17.55</td>
                                    <td>17.65</td>
                                    <td>120</td>
                                    <td>18</td>
                                    <td>101.11</td>
                                    <td>23.66</td>
                                    <td>33.66</td>
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

<<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Attach event listeners to all checkboxes
            document.querySelectorAll('.column-toggle').forEach((checkbox) => {
                checkbox.addEventListener('change', toggleColumn);
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

                rows.forEach((row) => {
                    const cells = Array.from(row.children); // Get all cells in the row
                    const visibleCells = cells
                        .filter((cell, index) => {
                            const columnCheckbox = document.querySelector(`.column-toggle[data-column="${index + 1}"]`);
                            return columnCheckbox && columnCheckbox.checked; // Include only visible columns
                        })
                        .map((cell) => cell.innerText.trim()); // Get text content of visible cells

                    visibleData.push(visibleCells); // Add filtered row data
                });

                // Convert data to workbook
                const ws = XLSX.utils.aoa_to_sheet(visibleData); // Create worksheet
                const wb = XLSX.utils.book_new(); // Create workbook
                XLSX.utils.book_append_sheet(wb, ws, 'Report'); // Append worksheet to workbook

                // Export to Excel file
                XLSX.writeFile(wb, 'report.xlsx'); // Trigger download
            });
        });
    </script>