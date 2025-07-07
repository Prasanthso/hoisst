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
                                <th scope="col" style="color:white;">Present MRP</th>
                                <th scope="col" style="color:white;width: 250px;">Suggeted MRP
                                </th>
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
                            $total = $report->RM_Cost + $report->PM_Cost;
                            $cost = $total + $report->OH_Cost + $report->MOH_Cost;
                            $cost_with_margin = $cost + $report->margin_amt;

                            // Apply tax
                            $tax_amount = ($cost_with_margin * $report->tax) / 100;
                            $cost_with_tax = $cost_with_margin + $tax_amount;

                            // Apply discount
                            $discount_amount = ($cost_with_tax * $report->markupDiscount) / 100;
                            $S_MRP = $cost_with_tax + $discount_amount;

                            $sellingRate = ($S_MRP * 100)/(100 + $report->markupDiscount);
                            $beforeTax = ($sellingRate * 100) / (100 + $report->tax);
                            $margin = $beforeTax - $cost;
                            $marginPercentage = ($beforeTax > 0) ? ($margin / $beforeTax) * 100 : 0;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span>{{ $report->Product_Name }}</span>
                                </td>
                                <td>{{ $report->P_MRP }}</td>
                                <td class="editable-mrp position-relative d-flex justify-content-between align-items-center">
                                    <span class="mrp-text">{{ number_format($S_MRP, 2) }}
                                    </span>
                                    <i class="fas fa-pencil-alt edit-icon ms-2 mt-2" data-bs-toggle="tooltip" title="Check the price against the expected margin"
                                        style="font-size: 0.8rem; cursor: pointer; color: #007bff;"></i>
                                    <input type="text" class="form-control mrp-input d-none" value="{{ number_format($S_MRP, 2) }}">
                                </td>
                                <td>{{ number_format($report->RM_Cost, 2) }}</td>
                                <td>{{ number_format($report->PM_Cost, 2) }}</td>
                                <td>{{ number_format($total, 2) }}</td>
                                <td>{{ number_format($report->OH_Cost + $report->MOH_Cost, 2) }}</td>
                                <td class="cost">{{ number_format($cost, 2) }}</td>
                                <td class="selling-rate">{{ number_format($sellingRate, 2) }}</td>
                                <td class="tax-value">{{ $report->tax }}</td>
                                <td class="before-tax">{{ number_format($beforeTax, 2) }}</td>
                                <td class="margin">{{ number_format($beforeTax - $cost, 2) }}</td>
                                <td class="margin-perc">
                                    {{ $beforeTax > 0 ? number_format((($beforeTax -$cost) / $beforeTax) * 100, 2) . '%' : '0%' }}
                                </td>
                                <!-- <td class="d-none discount-value">{{ $report->markupDiscount }}</td> -->
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
        // Edit MRP Logic
        $(document).on('click', '.editable-mrp', function(e) {
            e.stopPropagation();
            $('.mrp-text').removeClass('d-none');
            $('.mrp-input').addClass('d-none');
            $('.edit-icon').removeClass('d-none');

            const cell = $(this);
            cell.find('.mrp-text').addClass('d-none');
            cell.find('.mrp-input').removeClass('d-none').focus();

            cell.find('.edit-icon').addClass('d-none');

        });

        $(document).on('click', '.edit-icon', function(e) {
            e.stopPropagation();

            // Find the closest row and specific cell
            const row = $(this).closest('tr'); // Assuming row is the closest <tr> element
            const cell = row.find('.editable-mrp');

            // Toggle visibility in the clicked cell
            cell.find('.mrp-text').addClass('d-none');
            cell.find('.mrp-input').removeClass('d-none').focus();

            $(this).addClass('d-none');
        });

        // Calculation Logic - Trigger Only on 'Enter' Key Press
        $(document).on('keypress', '.mrp-input', function(e) {
            if (e.which === 13) { // Enter key
                const inputField = $(this);

                const newValue = parseFloat(inputField.val().trim()) || 0;

                if (newValue > 0) {
                    inputField.addClass('d-none');
                    inputField.siblings('.mrp-text').text(newValue).removeClass('d-none');

                    const row = inputField.closest('tr');
                    row.find('.edit-icon').removeClass('d-none');
                    const cost = parseFloat(row.find('.cost').text().trim()) || 0;

                    // Extract discount and tax values
                    const discount = parseFloat(row.find('.discount-value').text().trim()) || 0;
                    const tax = parseFloat(row.find('.tax-value').text().trim()) || 0;

                    // Updated Calculation Logic
                    const sellingRate = parseFloat((newValue * 100) / (100 + discount)).toFixed(2);
                    const beforeTax = parseFloat((sellingRate * 100) / (100 + tax)).toFixed(2);
                    const margin = parseFloat((beforeTax - cost).toFixed(2));
                    const marginPercentage = beforeTax > 0 ? ((margin / beforeTax) * 100).toFixed(2) : '0.00';

                    row.find('.selling-rate').text(sellingRate);
                    row.find('.before-tax').text(beforeTax);
                    row.find('.margin').text(margin);
                    row.find('.margin-perc').text(marginPercentage + '%');

                }
            }
        });

        // Prevent bubbling inside input
        $(document).on('click', '.mrp-input', function(e) {
            e.stopPropagation();

        });

        // When focusing on .mrp-input → ensure other elements are hidden
        $(document).on('focus', '.mrp-input', function(e) {
            const cell = $(this).closest('.editable-mrp');
            cell.find('.mrp-text').addClass('d-none');
            cell.find('.edit-icon').addClass('d-none');
        });

        // PDF Export Function
        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            const table = document.getElementById('reportTable');
            if (!table) {
                console.error('Table with ID "reportTable" not found.');
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
                        if (index !== 0) {
                            rowData.push(cell.innerText.trim());
                        }
                    });

                    tableData.push(rowData);
                }
            });

            doc.autoTable({
                head: [tableData[0]],
                body: tableData.slice(1),
                startY: 20,
                theme: 'striped',
            });

            doc.save('margin_calculation.pdf');
        });

        // Excel Export Function
        document.getElementById('exportBtn').addEventListener('click', function() {
            const table = document.getElementById('reportTable');
            if (!table) {
                console.error('Table with ID "reportTable" not found.');
                return;
            }

            const rows = Array.from(table.querySelectorAll('tr'));
            const visibleData = [];
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
                        if (index !== 0) {
                            rowData.push(cell.innerText.trim());
                        }
                    });

                    visibleData.push(rowData);
                }
            });

            const ws = XLSX.utils.aoa_to_sheet(visibleData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Recipe Pricing');

            XLSX.writeFile(wb, 'margin_calculation.xlsx');
        });
    });
</script>
