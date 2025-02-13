@extends('layouts.headerNav') <!-- Extending the layout -->

@section('content') <!-- Defining the 'content' section -->
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Recipe Pricing</h1>
        <div>
            <button type="button" class="btn btn-success" id="exportBtn">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
            <button id="exportPdfBtn" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export to PDF
            </button>
        </div>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-1"></div>
            <!-- Right side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end mb-2 action-buttons">
                        <!-- <button class="btn btn-sm edit-table-btn me-2" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-edit" style="color: black;"></i>
                        </button> -->
                        <!-- <button class="btn btn-sm delete-table-btn" style="background-color: #d9f2ff; border-radius: 50%; padding: 10px; border: none;">
                            <i class="fas fa-trash" style="color: red;"></i>
                        </button> -->
                    </div>

                    <!-- Bordered Table -->
                    <table class="table table-bordered" id="reportTable">
                        <thead class="custom-header">
                            <tr>
                                <th scope="col" style="color:white;">S.NO</th>
                                <th scope="col" style="color:white;">Product_Name</th>
                                <th scope="col" style="color:white;">P MRP</th>
                                <th scope="col" style="color:white;">S MRP</th>
                                <th scope="col" style="color:white;">RM Cost</th>
                                <!-- <th scope="col" style="color:white;">RM %</th> -->
                                <th scope="col" style="color:white;">Packing Cost</th>
                                <!-- <th scope="col" style="color:white;">Packing %</th> -->
                                <th scope="col" style="color:white;">Total</th>
                                <!-- <th scope="col" style="color:white;">%</th> -->
                                <!-- <th scope="col" style="color:white;">Overhead %</th> -->
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
                            @foreach ($reports as $index => $report)
                            @php
                            $sellingRate = $report->S_MRP * 0.75;
                            $beforeTax = ($sellingRate * 100) / 118;
                            $margin = $beforeTax - $report->COST;
                            $marginPercentage = $beforeTax > 0 ? ($margin / $beforeTax) * 100 : 0;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $report->Product_Name }}</td>
                                <td>{{ $report->S_MRP }}</td>
                                <td class="editable-mrp position-relative">
                                    <span class="mrp-text">{{ $report->S_MRP }}</span>
                                    <input type="text" class="form-control mrp-input d-none" value="{{ $report->S_MRP }}">
                                </td>
                                <td>{{ $report->RM_Cost }}</td>
                                <!-- <td>{{ number_format($report->RM_perc, 2) }}</td> -->
                                <td>{{ $report->PM_Cost }}</td>
                                <!-- <td>{{ number_format($report->PM_perc, 2) }}</td> -->
                                <td>{{ $report->TOTAL }}</td>
                                <!-- <td>{{ $report->Total_perc }}</td> -->
                                <td>{{ number_format($report->OH_Cost, 2) }}</td>
                                <!-- <td>{{ number_format($report->OH_perc, 2) }}</td> -->
                                <td class="cost">{{ $report->COST }}</td>
                                <td class="selling-rate">{{ number_format($report->S_MRP * 0.75, 2) }}</td>
                                <td>18</td>
                                <td class="before-tax">{{ number_format($beforeTax, 2) }}</td>
                                <td class="margin">{{ number_format($beforeTax - $report->COST, 2) }}</td>
                                <td class="margin-perc">{{ number_format((($beforeTax - $report->COST) / $beforeTax) * 100, 2) }}%</td>
                                @endforeach

                                <!-- Add more rows as needed -->
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>

                        </div>
                        <div>
                            <!-- Pagination Links -->

                        </div>
                    </div>
                    <!-- End Bordered Table -->
                </div>
            </div><!-- End Right side columns -->
        </div>
    </section>

</main><!-- End #main -->
@endsection

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

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

            doc.save('margin_calculation.pdf');
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
            XLSX.utils.book_append_sheet(wb, ws, 'Raw Material Report');

            // Export as an Excel file
            XLSX.writeFile(wb, 'margin_calculation.xlsx');
        });


        // When clicking the MRP cell
        $(document).on("click", ".editable-mrp", function(e) {
            e.stopPropagation(); // Prevents closing immediately when clicking inside

            $(".mrp-text").removeClass("d-none"); // Reset all first
            $(".mrp-input").addClass("d-none");

            var cell = $(this);
            cell.find(".mrp-text").addClass("d-none"); // Hide text
            cell.find(".mrp-input").removeClass("d-none").focus(); // Show input & focus
        });

        // When clicking outside, switch back and update Selling Rate, Before Tax, Margin & Margin %
        $(document).on("click", function() {
            $(".mrp-input").each(function() {
                var inputField = $(this);
                var newValue = parseFloat(inputField.val().trim()); // Get new MRP value

                if (!isNaN(newValue) && newValue > 0) {
                    inputField.addClass("d-none"); // Hide input
                    inputField.siblings(".mrp-text").text(newValue).removeClass("d-none"); // Update text

                    var row = inputField.closest("tr"); // Get the row
                    var sellingRateCell = row.find(".selling-rate"); // Get Selling Rate cell
                    var beforeTaxCell = row.find(".before-tax"); // Get Before Tax cell
                    var costCell = row.find(".cost"); // Get Cost cell
                    var marginCell = row.find(".margin"); // Get Margin cell
                    var marginPercCell = row.find(".margin-perc"); // Get Margin % cell

                    // Calculate Selling Rate (MRP * 0.75)
                    var sellingRate = (newValue * 0.75).toFixed(2);
                    sellingRateCell.text(sellingRate); // Update Selling Rate

                    // Calculate Before Tax (Selling Rate * 100 / 118)
                    var beforeTax = (sellingRate * 100 / 118).toFixed(2);
                    beforeTaxCell.text(beforeTax); // Update Before Tax

                    // Get Cost value
                    var cost = parseFloat(costCell.text().trim()) || 0;

                    // Calculate Margin (Before Tax - Cost)
                    var margin = (beforeTax - cost).toFixed(2);
                    marginCell.text(margin); // Update Margin

                    // Calculate Margin % (Margin / Before Tax * 100)
                    var marginPerc = beforeTax > 0 ? ((margin / beforeTax) * 100).toFixed(2) : "0.00";
                    marginPercCell.text(marginPerc + "%"); // Update Margin %
                }
            });
        });

        // Prevent event bubbling when clicking inside the input
        $(document).on("click", ".mrp-input", function(e) {
            e.stopPropagation();
        });

        // When pressing Enter, switch back and update Selling Rate, Before Tax, Margin & Margin %
        $(document).on("keypress", ".mrp-input", function(e) {
            if (e.which === 13) { // Enter key
                $(this).blur();
            }
        });
    });
</script>