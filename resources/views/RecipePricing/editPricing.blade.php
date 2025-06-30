@extends('layouts.headerNav')

@section('content')
<main id="main" class="main">

    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>Edit Recipe Costing</h1>
        <div class="row">
            <!-- Action Buttons -->
        </div>
    </div>

    <section class="section dashboard">
        <div class="container mt-5">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-4">
                <label for="productSelect" class="form-label">Select Product</label>
                <div class="col-6">
                    <select id="productSelect" class="form-select select2" name="productSelect" aria-labelledby="productSelect">
                        <option value="" disabled selected>Select a Product</option>
                        @foreach ($products as $product)
                        <option value="{{ $product->id }}" selected>
                            {{ $product->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 col-sm-10 mb-2">
                    <label for="recipeOutput" class="form-label">Output</label>
                    <input type="text" class="form-control rounded" id="recipeOutput" name="recipeOutput" value="{{ $products[0]->rp_output ?? '' }}" readonly>
                </div>
                <div class="col-md-2 col-sm-10">
                    <label for="recipeUoM" class="form-label">UoM</label>
                    <select id="recipeUoM" class="form-select select2" name="recipeUoM">
                        <option value="" disabled selected>UoM</option>
                        @foreach ($products as $rpuom)
                        <option value="{{ $rpuom->rp_uom }}" {{ $rpuom->rp_uom == $products[0]->rp_uom ? 'selected' : '' }}>
                            {{ $rpuom->rp_uom }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingrawmaterial" class="form-label text-primary" name="pricingrawmaterial" id="pricingrawmaterial">Raw Material</label>
                </div>
                <div class="col">
                    <hr />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="rawmaterial" class="form-label">Raw Material</label>
                    <select id="rawmaterial" class="form-select select2">
                        <option selected disabled>Choose...</option>
                        @foreach($rawMaterials as $rawMaterialItem)
                        <option
                            value="{{ $rawMaterialItem->id }}"
                            data-code="{{ $rawMaterialItem->rmcode }}"
                            data-uom="{{ $rawMaterialItem->uom }}"
                            data-price="{{ $rawMaterialItem->price }}">
                            {{ $rawMaterialItem->name }}
                        </option>
                        @endforeach
                    </select>

                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="rmQuantity" name="rmQuantity">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmCode" class="form-label">RM Code</label>
                    <input type="text" class="form-control rounded" id="rmCode" name="rmCode" readonly>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control" id="rmUoM" name="rmUoM" readonly>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="rmPrice" name="rmPrice" readonly>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="rmAmount" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="rmAmount" name="rmAmount">
                </div>
                <div class="d-flex flex-column" style="flex: 2;">
                    <button type="button" class="btn btn-primary rmaddbtn" id="rmaddbtn">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
            </div>

            @if(isset($pricingData) && $pricingData->isNotEmpty())
            <div class="row mb-4">
                <!-- Raw Materials Table -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #eaf8ff; width:90%;">
                        <thead>
                            <tr>
                                <th>Raw Material</th>
                                <th>Quantity</th>
                                <th>RM Code</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="rawMaterialTable">
                            @php $rmTotal = 0;
                            $filteredData = collect($pricingData)->unique('rid')->values();
                            @endphp
                            @foreach($filteredData as $data)
                            @if($data->rm_name)
                            @php
                            $amount = $data->rm_quantity * $data->rm_price;
                            $rmTotal += $amount;
                            @endphp
                            <tr>
                                <td>{{ $data->rm_name }}</td>
                                <td class="quantity-cell" id="quantity-cell-{{ $data->rid }}">
                                    <span id="quantity-text-{{ $data->rid }}">{{ $data->rm_quantity }}</span>
                                    <input type="number" class="form-control quantity-input" id="quantity-{{ $data->rid }}" value="{{ $data->rm_quantity }}" style="display: none;" disabled>
                                </td>
                                <td>{{ $data->rm_code }}</td>
                                <td>{{ $data->rm_uom ?? 'N/A' }}</td>
                                <td id="rmprice-{{ $data->rid }}">{{ $data->rm_price }}</td>
                                <td class="rmamountcell" id="rmamount-{{ $data->rid }}">{{ $amount }}</td>
                                <td>
                                    <!-- Action Buttons -->
                                    <span
                                        class="icon-action edit-btn"
                                        id="edit-{{ $data->rid }}"
                                        style="cursor: pointer; color: blue;"
                                        title="Edit Row"
                                        onclick="editRow('{{ $data->rm_id }}', '{{ $data->rid }}')">
                                        &#9998;
                                    </span>
                                    <span
                                        class="icon-action save-btn"
                                        id="save-{{ $data->rid }}"
                                        style="cursor: pointer; color: green; display:none;"
                                        title="Save Row"
                                        onclick="saveRow('{{ $data->rm_id }}', '{{ $data->rid }}')">
                                        &#x2714;
                                    </span>
                                    <span class="delete-icon" id="delete-{{ $data->rid }}" style="cursor: pointer; color: red;" title="Remove Row" data-id="{{ $data->rid }}">&#x1F5D1;</span>
                                    <span class="cancel-icon" id="cancel-{{ $data->rid }}" style="cursor: pointer; color: blue; display:none;" title="Cancel"
                                        data-id="{{ $data->rid }}" onclick="cancelRow('{{ $data->rm_id }}', '{{ $data->rid }}')">&#x2716;</span>
                                </td>
                            </tr>
                            @endif
                            @endforeach

                            @if($pricingData->whereNotNull('rm_name')->isEmpty())
                            <tr>
                                <td colspan="6">No records available for Raw Materials</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color: #eaf8ff; width:90%;">
                        <strong>RM Cost (A) : </strong> <span id="totalRmCost">{{ $rmTotal }}</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingpackingmaterial" class="form-label text-primary" id="pricingpackingmaterial">Packing Material</label>
                </div>
                <div class="col">
                    <hr />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="packingmaterial" class="form-label">Packing Material</label>
                    <select id="packingmaterial" class="form-select select2">
                        <option selected disabled>Choose...</option>
                        @foreach($packingMaterials as $packingMaterialItem)
                        <option
                            value="{{ $packingMaterialItem->id }}"
                            data-code="{{ $packingMaterialItem->pmcode }}"
                            data-uom="{{ $packingMaterialItem->uom }}"
                            data-price="{{ $packingMaterialItem->price }}">
                            {{ $packingMaterialItem->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="pmQuantity" name="pmQuantity">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmCode" class="form-label">PM Code</label>
                    <input type="text" class="form-control rounded" id="pmCode" name="pmCode" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control" id="pmUoM" name="pmUoM" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="pmPrice" name="pmPrice" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="pmAmount" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="pmAmount" name="pmAmount">
                </div>
                <div class="d-flex flex-column" style="flex: 2;">
                    {{-- <a href="#" class='text-decoration-none pm-ps-add-btn text-white py-4 px-4'> --}}
                    <button type="button" class="btn btn-primary pmaddbtn" id="pmaddbtn"><i class="fas fa-plus"></i> Add</button>
                    {{-- </a> --}}
                </div>
            </div>
            <div class="row mb-4">
                <!-- Packing Materials Table -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #F1F1F1; width:90%;">
                        <thead>
                            <tr>
                                <th>Packing Material</th>
                                <th>Quantity</th>
                                <th>PM Code</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="packingMaterialTable">
                            @php
                            $filteredData = collect($pricingData)->unique('pid')->values();
                            $pmTotal = 0; @endphp
                            @foreach($filteredData as $data)
                            @if($data->pm_name)
                            @php
                            $amount = $data->pm_quantity * $data->pm_price;
                            $pmTotal += $amount;
                            @endphp
                            <tr>
                                <td>{{ $data->pm_name }}</td>
                                <td class="pmquantity-cell" id="pmquantity-cell-{{ $data->pid }}">
                                    <span id="pmquantity-text-{{ $data->pid }}">{{ $data->pm_quantity }}</span>
                                    <input type="number" class="form-control pmquantity-input" id="pmquantity-{{ $data->pid }}" value="{{ $data->pm_quantity }}" style="display: none;" disabled>
                                </td>
                                <td>{{ $data->pm_code }}</td>
                                <td>{{ $data->pm_uom ?? 'N/A' }}</td>
                                <td id="pmprice-{{ $data->pid }}">{{ $data->pm_price }}</td>
                                <td id="pmamount-{{ $data->pid }}">{{ $amount }}</td>
                                <td>
                                    <!-- Action Buttons -->
                                    <span
                                        class="icon-action pm-edit-btn"
                                        id="pmedit-{{ $data->pid }}"
                                        style="cursor: pointer; color: blue;"
                                        title="Edit Row"
                                        onclick="pm_editRow('{{ $data->pm_id }}', '{{ $data->pid }}')">
                                        &#9998;
                                    </span>
                                    <span
                                        class="icon-action pm-save-btn"
                                        id="pmsave-{{ $data->pid }}"
                                        style="cursor: pointer; color: green; display:none;"
                                        title="Save Row"
                                        onclick="pm_saveRow('{{ $data->pm_id }}', '{{ $data->pid }}')">
                                        &#x2714;
                                    </span>
                                    <span class="delete-icon" id="pmdelete-{{ $data->pid }}" style="cursor: pointer; color: red;" title="Remove Row" data-id="{{ $data->pid }}">&#x1F5D1;</span>
                                    <span class="cancel-icon" id="pmcancel-{{ $data->pid }}" style="cursor: pointer; color: blue; display:none;" title="Cancel"
                                        data-id="{{ $data->pid }}" onclick="pm_cancelRow('{{ $data->pm_id }}', '{{ $data->pid }}')">&#x2716;</span>
                                </td>
                            </tr>
                            @endif
                            @endforeach

                            @if($pricingData->whereNotNull('pm_name')->isEmpty())
                            <tr>
                                <td colspan="6">No records available for Packing Materials</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color:#F1F1F1; width:90%;">
                        <strong>PM Cost (B) : </strong> <span id="totalPmCost">{{ $pmTotal }}</span>
                    </div>
                </div>
            </div>
            @php
            $ohTotal = 0;
            $mohTotal = 0;
            @endphp
            <div><input type="hidden" id="oh_mohValue" value="{{ $pricingData->whereNotNull('oh_name')->isNotEmpty() ? 'masters' : ($pricingData->whereNotNull('moh_name')->isNotEmpty() ? 'manual' : '') }}"></div>

            @if($pricingData->whereNotNull('oh_name')->isNotEmpty())
            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingoverheads" class="form-label text-primary" id="pricingoverheads">Overheads From Masters</label>
                </div>
                <div class="col-2 form-check">
                    <input type="checkbox" class="form-check-input" id="frommasters" checked>
                    <label class="form-check-label" for="frommasters"> From Masters </label>
                </div>
                <!-- <div class="col-2 form-check">
                    <input type="checkbox" class="form-check-input" id="entermanually"> <label class="form-check-label" for="entermanually"> Enter Manually </label>
                </div> -->
                <div class="col">
                    <hr />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="overheads" class="form-label">Overheads</label>
                    <select id="overheads" class="form-select select2">
                        <option selected disabled>Choose...</option>
                        @foreach($overheads as $overheadsItem)
                        <option
                            value="{{ $overheadsItem->id }}"
                            data-code="{{ $overheadsItem->ohcode }}"
                            data-uom="{{ $overheadsItem->uom }}"
                            data-price="{{ $overheadsItem->price }}">
                            {{ $overheadsItem->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="ohQuantity" name="ohQuantity">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohCode" class="form-label">OH Code</label>
                    <input type="text" class="form-control rounded" id="ohCode" name="ohCode" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control" id="ohUoM" name="ohUoM" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="ohPrice" name="ohPrice" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohAmount" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="ohAmount" name="ohAmount">
                </div>
                <div class="d-flex flex-column" style="flex: 2;">
                    {{-- <a href="#" class='text-decoration-none oh-ps-add-btn text-white py-4 px-4'> --}}
                    <button type="button" class="btn btn-primary ohaddbtn" id="ohaddbtn"><i class="fas fa-plus"></i> Add</button>
                    {{-- </a> --}}
                </div>
            </div>
            <div class="row mb-4">
                <!-- Overheads Table -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #D7E1E4; width:90%;">
                        <thead>
                            <tr>
                                <th>Overheads</th>
                                <th>Quantity</th>
                                <th>OH Code</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="overheadsTable">
                            @php
                            $ohTotal = 0;
                            $filteredData = collect($pricingData)->unique('ohid')->values();
                            @endphp
                            @forelse($filteredData as $data)
                            @if($data->oh_name)
                            @php
                            $amount = $data->oh_quantity * $data->oh_price;
                            $ohTotal += $amount;
                            $mohTotal = 0;
                            @endphp
                            <tr>
                                <td>{{ $data->oh_name }}</td>
                                <td class="ohquantity-cell" id="ohquantity-cell-{{ $data->ohid }}">
                                    <span id="ohquantity-text-{{ $data->ohid }}">{{ $data->oh_quantity }}</span>
                                    <input type="number" class="form-control ohquantity-input" id="ohquantity-{{ $data->ohid }}" value="{{ $data->oh_quantity }}" style="display: none;" disabled>
                                </td>
                                <td>{{ $data->oh_code }}</td>
                                <td>{{ $data->oh_uom ?? 'N/A' }}</td>
                                <td id="ohprice-{{ $data->ohid }}">{{ $data->oh_price }}</td>
                                <td id="ohamount-{{ $data->ohid }}">{{ $amount }}</td>
                                <td>
                                    <!-- Action Buttons -->
                                    <span
                                        class="icon-action oh-edit-btn"
                                        id="ohedit-{{ $data->ohid }}"
                                        style="cursor: pointer; color: blue;"
                                        title="Edit Row"
                                        onclick="oh_editRow('{{ $data->oh_id }}', '{{ $data->ohid }}')">
                                        &#9998;
                                    </span>
                                    <span
                                        class="icon-action oh-save-btn"
                                        id="ohsave-{{ $data->ohid }}"
                                        style="cursor: pointer; color: green; display:none;"
                                        title="Save Row"
                                        onclick="oh_saveRow('{{ $data->oh_id }}', '{{ $data->ohid }}')">
                                        &#x2714;
                                    </span>
                                    <span class="delete-icon" id="ohdelete-{{ $data->ohid }}" style="cursor: pointer; color: red;" title="Remove Row" data-id="{{ $data->ohid }}">&#x1F5D1;</span>
                                    <span class="cancel-icon" id="ohcancel-{{ $data->ohid }}" style="cursor: pointer; color: blue; display:none;" title="Cancel"
                                        data-id="{{ $data->ohid }}" onclick="oh_cancelRow('{{ $data->oh_id }}', '{{ $data->ohid }}')">&#x2716;</span>
                                </td>
                            </tr>
                            @endif
                            @empty
                            <tr>
                                <td colspan="6">No records available for Manual Overheads</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="text-end" style="background-color:#F1F1F1; width:90%;">
                        <strong>OH Cost (C) : </strong> <span id="totalohCost">{{ $ohTotal }}</span>
                    </div>
                </div>
            </div>
            @endif

            @if($pricingData->whereNotNull('moh_name')->isNotEmpty())
            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingoverheads" class="form-label text-primary" id="pricingoverheads">Overheads Enter Manually</label>

                </div>
                <!-- <div class="col-2 form-check">
                    <label class="form-check-label" for="frommasters"> From Masters </label>
                </div> -->
                <div class="col-2 form-check">
                    <input type="checkbox" class="form-check-input" id="entermanually" checked>
                    <label class="form-check-label" for="entermanually"> Enter Manually </label>
                </div>
                <div class="col">
                    <hr />
                </div>
            </div>
            <div id="manualEntry">
                <div class="row mb-4">
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOverheads" class="form-label">Overheads Name</label>
                        <input type="text" class="form-control rounded" id="manualOverheads" name="manualOverheads">
                    </div>

                    <!-- Dropdown for selecting Overheads Type -->
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOhType" class="form-label">Type</label>
                        <select id="manualOhType" class="form-select">
                            <option value="price" selected>Overheads Price</option>
                            <option value="percentage">Overheads Percentage</option>
                        </select>
                    </div>

                    <!-- Input Fields (Only One Will Be Visible at a Time) -->
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOhPrice" class="form-label" id="manualOhPricelab">Overheads Value</label>
                        <input type="number" class="form-control rounded" id="manualOhPrice" name="manualOhPrice">

                        <label for="manualOhPerc" class="form-label" id="manualOhPerclab" style="display: none;">Overheads Percentage</label>
                        <input type="number" class="form-control rounded" id="manualOhPerc" name="manualOhPerc" style="display: none;">
                    </div>

                    <div class="d-flex flex-column" style="flex: 2;">
                        <button type="button" class="btn btn-primary manualOhaddbtn" id="manualOhaddbtn">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <!-- Overheads Table -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="background-color: #D7E1E4; width:90%;">
                        <thead>
                            <tr>
                                <th>Overheads</th>
                                <th>Quantity</th>
                                <th>OH Code</th>
                                <th>UoM</th>
                                <th>Percentage(%)</th>
                                <th>Price/Amount</th>
                            </tr>
                        </thead>
                        <tbody id="manualEntryTable">
                            @php $mohTotal = 0;
                            $filteredData = collect($pricingData)->unique('mohid')->values();
                            @endphp
                            @forelse($filteredData as $data)
                            @if($data->moh_name)
                            @php
                            $amount = $data->moh_price;
                            $mohTotal += $amount;
                            $ohTotal = 0;
                            @endphp
                            <tr>
                                <td>{{ $data->moh_name }}</td>
                                <td> - </td>
                                <td> - </td>
                                <td> - </td>
                                <td id="mohprice-{{ $data->mohid }}">{{ $data->moh_percentage }}</td>
                                <td id="mohamount-{{ $data->mohid }}">{{ $amount }}</td>
                                <td>
                                    <span class="delete-icon" id="mohdelete-{{ $data->mohid }}" style="cursor: pointer; color: red;" title="Remove Row" data-id="{{ $data->mohid }}">&#x1F5D1;</span>
                                </td>
                            </tr>
                            @endif
                            @empty
                            <tr>
                                <td colspan="6">No records available for Manual Overheads</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                    <div class="text-end" style="background-color:#F1F1F1; width:90%;">
                        <strong>OH Cost (C) : </strong> <span id="totalohCost">{{ $mohTotal }}</span>
                    </div>
                </div>
            </div>
            @endif

            @if(collect($pricingData)->whereNotNull('oh_name')->isEmpty() && collect($pricingData)->whereNotNull('moh_name')->isEmpty())
            <div class="row mb-2">
                <div class="col-auto">
                    <label for="pricingoverheads1" class="form-label text-primary" id="pricingoverheads1">Overheads</label>
                </div>
                <div class="col-2 form-check">
                    <input type="checkbox" class="form-check-input" id="frommasters1" checked>
                    <label class="form-check-label" for="frommasters1"> From Masters </label>
                </div>
                <div class="col-2 form-check">
                    <input type="checkbox" class="form-check-input" id="entermanually1"> <label class="form-check-label" for="entermanually1"> Enter Manually </label>
                </div>
                <div class="col">
                    <hr />
                </div>
            </div>
            <div class="row mb-4" id="newOh">
                <div class="col-md-3">
                    <label for="overheads" class="form-label">Overheads</label>
                    <select id="overheads" class="form-select select2">
                        <option selected disabled>Choose...</option>
                        @foreach($overheads as $overheadsItem)
                        <option
                            value="{{ $overheadsItem->id }}"
                            data-code="{{ $overheadsItem->ohcode }}"
                            data-uom="{{ $overheadsItem->uom }}"
                            data-price="{{ $overheadsItem->price }}">
                            {{ $overheadsItem->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohQuantity" class="form-label">Quantity</label>
                    <input type="text" class="form-control rounded" id="ohQuantity" name="ohQuantity">
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohCode" class="form-label">OH Code</label>
                    <input type="text" class="form-control rounded" id="ohCode" name="ohCode" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohUoM" class="form-label">UoM</label>
                    <input type="text" class="form-control" id="ohUoM" name="ohUoM" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohPrice" class="form-label">Price</label>
                    <input type="text" class="form-control rounded" id="ohPrice" name="ohPrice" disabled>
                </div>
                <div class="d-flex flex-column" style="flex: 1.5;">
                    <label for="ohAmount" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="ohAmount" name="ohAmount">
                </div>
                <div class="d-flex flex-column" style="flex: 2;">
                    {{-- <a href="#" class='text-decoration-none oh-ps-add-btn text-white py-4 px-4'> --}}
                    <button type="button" class="btn btn-primary ohaddbtn" id="ohaddbtn"><i class="fas fa-plus"></i> Add</button>
                    {{-- </a> --}}
                </div>
            </div>
            <div id="manualEntry1" style="display: none;">
                <div class="row mb-4">
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOverheads" class="form-label">Overheads Name</label>
                        <input type="text" class="form-control rounded" id="manualOverheads" name="manualOverheads">
                    </div>
                    <!-- Dropdown for selecting Overheads Type -->
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOhType" class="form-label">Type</label>
                        <select id="manualOhType" class="form-select">
                            <option value="price" selected>Overheads Price</option>
                            <option value="percentage">Overheads Percentage</option>
                        </select>
                    </div>
                    <!-- Input Fields (Only One Will Be Visible at a Time) -->
                    <div class="d-flex flex-column" style="flex: 1.5;">
                        <label for="manualOhPrice" class="form-label" id="manualOhPricelab">Overheads Price</label>
                        <input type="number" class="form-control rounded" id="manualOhPrice" name="manualOhPrice">

                        <label for="manualOhPerc" class="form-label" id="manualOhPerclab">Overheads Percentage</label>
                        <input type="number" class="form-control rounded" id="manualOhPerc" name="manualOhPerc" style="display: none;">
                    </div>

                    <div class="d-flex flex-column" style="flex: 2;">
                        <button type="button" class="btn btn-primary ohaddbtn" id="manualOhaddbtn">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
            {{-- <div class="container-fluid mt-4"> --}}
            <div class="row mb-4">
                <div class="col-12 col-md-12 mx-auto table-responsive"> <!-- Use col-md-11 for slightly left alignment -->
                    <table class="table table-bordered text-center" style=" background-color: #D7E1E4; width:90%;">
                        <thead class="no border">
                            <tr>
                                <th>Overheads</th>
                                <th>Quantity</th>
                                <th>OH Code</th>
                                <th>UoM</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="overheadsTable">
                        </tbody>

                    </table>
                    <div class="text-end" style="background-color: #D7E1E4; width:90%;">
                        <strong>OH Cost (C) : </strong> <span id="totalohCost">0.00</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-between">
                <div class=" mb-2">
                    <div class="mt-2">
                        <label for="totalcost" class="form-label">Total Cost (A+B+C):
                    </div>
                    <div>
                        <input type="text" class="form-control" id="totalcost" value="{{ ($rmTotal ?: 0) + ($pmTotal ?: 0) + ($ohTotal ?: 0) + ($mohTotal ?: 0) }}" disabled>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="mt-2">
                        <label for="unitcost" class="form-label">Unit Cost:</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control" id="singletotalcost" value="{{ $data->rp_output ? round(($rmTotal + $pmTotal + $ohTotal + $mohTotal) / $data->rp_output, 2) : 0 }}" disabled>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    // Ensure functions are available in the global scope
    document.addEventListener('DOMContentLoaded', function() {

        window.editRow = editRow;
        window.saveRow = saveRow;

        window.pm_editRow = pm_editRow;
        window.pm_saveRow = pm_saveRow;

        window.oh_editRow = oh_editRow;
        window.oh_saveRow = oh_saveRow;
        // create_OhMoh();

        for_Oh_Manual();
        rmforRecipe();
        pmforRecipe();
        ohforRecipe();
        mohforRecipe();
        // manualOhType.addEventListener("change", toggleFields);
    });

    function for_Oh_Manual() {
        const fromMastersCheckbox = document.getElementById("frommasters1");
        const enterManuallyCheckbox = document.getElementById("entermanually1");
        const masterEntryDiv = document.getElementById("newOh");
        const manualEntryDiv = document.getElementById("manualEntry1");
        const ohMohValue = document.getElementById("oh_mohValue").value;
        console.log("new overheads");
        console.log(ohMohValue);
        if (ohMohValue === "") {
            console.log("check 2");
            // Function to toggle visibility based on checkbox selection
            function toggleForms() {
                if (fromMastersCheckbox.checked) {
                    masterEntryDiv.style.display = "flex";
                    manualEntryDiv.style.display = "none";
                } else if (enterManuallyCheckbox.checked) {
                    masterEntryDiv.style.display = "none";
                    manualEntryDiv.style.display = "block";
                } else {
                    fromMastersCheckbox.checked = true;
                    masterEntryDiv.style.display = "none";
                    manualEntryDiv.style.display = "none";
                }
            }
            const manualOhType = document.getElementById("manualOhType");
            const manualOhPrice = document.getElementById("manualOhPrice");
            const manualOhPricelab = document.getElementById("manualOhPricelab");
            const manualOhPerc = document.getElementById("manualOhPerc");
            const manualOhPerclab = document.getElementById("manualOhPerclab");

            function toggleFields() {
                if (manualOhType.value === "price") {
                    manualOhPrice.style.display = "block";
                    manualOhPricelab.style.display = "block";
                    manualOhPerc.style.display = "none";
                    manualOhPerclab.style.display = "none";
                } else {
                    manualOhPrice.style.display = "none";
                    manualOhPricelab.style.display = "none";
                    manualOhPerc.style.display = "block";
                    manualOhPerclab.style.display = "block";
                }
            }
            // Ensure correct field is shown when the page loads
            toggleFields();

            // Listen for changes in dropdown selection
            manualOhType.addEventListener("change", toggleFields);

            // Add event listeners to checkboxes to toggle the forms
            fromMastersCheckbox.addEventListener("change", function() {
                if (fromMastersCheckbox.checked) {
                    enterManuallyCheckbox.checked = false; // Uncheck the "Enter Manually" checkbox
                }
                toggleForms();
            });
            enterManuallyCheckbox.addEventListener("change", function() {
                if (enterManuallyCheckbox.checked) {
                    fromMastersCheckbox.checked = false; // Uncheck the "From Masters" checkbox
                }
                toggleForms();
            });
            // Set default to "From Masters" and call toggleForms() to show the correct form
            fromMastersCheckbox.checked = true;
            toggleForms();
        }
    }

    function recipevalidation() {
        const rpvalue = document.getElementById('productSelect').value.trim();
        const rpopvalue = document.getElementById('recipeOutput').value.trim();
        const rpuomvalue = document.getElementById('recipeUoM').value.trim();

        if (rpvalue === "" && rpvalue === "Choose...") {
            alert("Please fill in the Recipe Name.");
            document.getElementById('productSelect').focus();
            return;
        } else if (rpopvalue === "") {
            alert("Please fill in the Recipe Output.");
            document.getElementById('recipeOutput').focus();
            return;
        } else if (rpuomvalue === "") {
            alert("Please fill in the Recipe UoM.");
            document.getElementById('recipeUoM').focus();
            return;
        }
    }

    // raw materials recipe-pricing details
    // Function to enable editing for a specific row
    function editRow(id, rid) {
        // Hide the text and show the input field
        document.getElementById('quantity-text-' + rid).style.display = 'none';
        document.getElementById('quantity-' + rid).style.display = 'inline-block';

        // Enable the input field
        document.getElementById('quantity-' + rid).disabled = false;

        // Show the save button and hide the edit button
        document.querySelector(`#save-${rid}`).style.display = 'inline-block';
        document.querySelector(`#cancel-${rid}`).style.display = 'inline-block';
        document.querySelector(`#edit-${rid}`).style.display = 'none';
        document.querySelector(`#delete-${rid}`).style.display = 'none';
    }

    function cancelRow(id, rid) {
        // Hide the input field and show the static text
        document.getElementById('quantity-' + rid).style.display = 'none';
        document.getElementById('quantity-text-' + rid).style.display = 'inline-block';

        // Reset the input field value to match the static text value (original value)
        const originalValue = document.getElementById('quantity-text-' + rid).innerText.trim();
        document.getElementById('quantity-' + rid).value = originalValue;
        // Disable the input field to ensure it's not editable in view mode
        document.getElementById('quantity-' + rid).disabled = true;

        // Show the "Edit" and "Delete" buttons
        document.querySelector(`#edit-${rid}`).style.display = 'inline-block';
        document.querySelector(`#delete-${rid}`).style.display = 'inline-block';

        // Hide the "Save" and "Cancel" buttons
        document.querySelector(`#save-${rid}`).style.display = 'none';
        document.querySelector(`#cancel-${rid}`).style.display = 'none';
        // Optionally log the action for debugging purposes
        // console.log(`Edit for row with PID ${pid} canceled. Value reverted to: ${originalValue}`);

    }

    // Function to save the edited data
    function saveRow(id, rid) {
        const quantityInput = document.getElementById('quantity-' + rid);
        const quantity = parseFloat(quantityInput.value); // Get and parse the input value
        const rmpriceInput = document.getElementById('rmprice-' + rid); // Get the price cell element
        const rmprice = parseFloat(rmpriceInput.innerText) || 0;
        const rmnewAmt = (rmprice * quantity).toFixed(2);
        console.log('Quantity & price & Amount:', quantity, rmprice, rmnewAmt);
        // Perform validation
        if (isNaN(quantity) || quantity <= 0) {
            alert('Please enter a valid quantity greater than 0.');
            return;
        }
        // Get CSRF token from the meta tag
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Send data to the server via a POST request
        fetch(`/update-pricing/${rid}`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    quantity: quantity,
                    amount: rmnewAmt,
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Quantity updated successfully.");
                    // Update the text to show the new quantity
                    document.getElementById('quantity-text-' + rid).textContent = quantity;
                    document.getElementById('rmamount-' + rid).textContent = parseFloat(rmnewAmt).toFixed(2);
                    window.location.reload();
                } else {
                    alert("Error updating quantity.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An unexpected error occurred while updating quantity.");
            });

        // Disable the input again and revert UI changes
        quantityInput.disabled = true;
        document.getElementById('quantity-text-' + rid).style.display = 'inline-block'; // Show the text
        document.getElementById('quantity-' + rid).style.display = 'none'; // Hide the input

        // Hide the save button and show the edit button again
        document.querySelector(`#save-${rid}`).style.display = 'none';
        document.querySelector(`#cancel-${rid}`).style.display = 'none';
        document.querySelector(`#edit-${rid}`).style.display = 'inline-block';
        document.querySelector(`#delete-${rid}`).style.display = 'inline-block';
    }

    // raw materials recipe-pricing function
    function rmforRecipe() {
        const productSelect = document.getElementById('productSelect');
        const rawMaterialSelect = document.getElementById('rawmaterial');
        const quantityInput = document.getElementById('rmQuantity');
        const codeInput = document.getElementById('rmCode');
        const uomInput = document.getElementById('rmUoM');
        const priceInput = document.getElementById('rmPrice');
        const amountInput = document.getElementById('rmAmount');
        const addButton = document.getElementById('rmaddbtn');
        const tableBody = document.getElementById('rawMaterialTable');
        const totalCostSpan = document.getElementById('totalRmCost');
        const totalCostInput = document.getElementById('totalcost'); // Total Cost (A+B+C)
        const rpoutputInput = document.getElementById('recipeOutput');
        const rpuomInput = document.getElementById('recipeUoM');

        recipevalidation();

        productSelect.addEventListener('change', function() {
            product_id = this.value; // Update product_id with the selected value
            console.log('Selected product ID:', product_id); // Debug log to check the selected value
        });

        // Update fields when raw material is selected
        rawMaterialSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.disabled) {
                clearFields();
                return;
            }
            const code = selectedOption.getAttribute('data-code');
            const uom = selectedOption.getAttribute('data-uom');
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            codeInput.value = code || '';
            uomInput.value = uom || '';
            priceInput.value = price.toFixed(2);
            updateAmount();
        });

        // Update amount on quantity input
        quantityInput.addEventListener('input', updateAmount);

        // add rawmaterial
        document.getElementById('rmaddbtn').addEventListener('click', function() {
            const product_id = productSelect.value;
            if (!product_id) {
                alert('Please select a valid product.');
                return;
            }
            const rawMaterialId = rawMaterialSelect.value;
            const rawMaterialName = rawMaterialSelect.options[rawMaterialSelect.selectedIndex]?.text;
            const quantity = parseFloat(quantityInput.value) || 0;
            const code = codeInput.value;
            const uom = uomInput.value;
            const price = parseFloat(priceInput.value) || 0;
            const amount = parseFloat(amountInput.value) || 0;
            const rpoutput = rpoutputInput.value.trim(); // Convert to number
            const rpuom = rpuomInput.value;
            console.log("rp", rpoutput, rpuom);
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!token) {
                console.error('CSRF token not found.');
                return;
            }

            if (!rawMaterialName || !quantity || !code || !uom || !price || !amount) {
                alert('Please fill all fields before adding.');
                return;
            }
            const rows = Array.from(tableBody.querySelectorAll('tr'));
            const isAlreadyAdded = rows.some(row => row.cells[0].textContent === rawMaterialName);

            if (isAlreadyAdded) {
                alert('This raw material has already been added to the table.');
                clearFields();
                return;
            }

            // Prepare data for server request
            const rmdata = {
                raw_material_id: rawMaterialId,
                product_id: product_id,
                quantity: quantity,
                amount: amount,
                code: code,
                uom: uom,
                price: price,
                rpoutput: rpoutput,
                rpuom: rpuom,
            };

            // console.log(rmdata);
            // Send data to the server
            fetch('/rm-for-recipe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify(rmdata),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add the new row to the table
                        console.log('Success:', data);
                        const rmInsertedId = data.rmInserted_id;
                        // Add row to the table
                        const row = `<tr>
                    <td>${rawMaterialName}</td>
                    <td>${quantity}</td>
                    <td>${code}</td>
                    <td>${uom}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${amount.toFixed(2)}</td>
                    <td>
                    <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${rmInsertedId}">&#x1F5D1;</span>
                    </td>
                    </tr>`;
                        tableBody.insertAdjacentHTML('beforeend', row);
                        // updateTotalCost(amount); // Update the total cost after adding a row
                        clearFields();
                        alert('Raw material added successfully!');
                        window.location.reload();
                    } else {
                        alert('Failed to add raw material. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // alert('An error occurred while adding raw material.');
                });
        });

        // Delete row functionality
        tableBody.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-icon')) {
                const deleteIcon = e.target;
                const row = deleteIcon.closest('tr');
                const rmInsertedId = deleteIcon.getAttribute('data-id');

                // Confirm deletion
                if (!confirm('Are you sure you want to delete this record?')) {
                    return;
                }
                // CSRF token
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    console.error('CSRF token not found.');
                    return;
                }

                // Send DELETE request to the server
                fetch(`/rm-for-recipe/${rmInsertedId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Server response not OK');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success:', data);
                        // Remove the row from the table
                        const amount = parseFloat(row.cells[5].textContent) || 0;
                        row.remove();
                        // Update the total cost
                        updateTotalCost(-amount);

                    })
                    .catch(error => console.error('Error:', error.message));
            }
        });

        // Helper functions
        function updateAmount() {
            const price = parseFloat(priceInput.value) || 0;
            const quantity = parseFloat(quantityInput.value) || 0;
            amountInput.value = (price * quantity).toFixed(2);
        }

        function updateTotalCost(newAmount) {
            const totalCostSpan = document.getElementById('totalRmCost');
            const currentTotal = parseFloat(totalCostSpan.textContent) || 0;
            totalCostSpan.textContent = (currentTotal + newAmount).toFixed(2);
            updateGrandTotal();
        }

        function clearFields() {
            // Clear select dropdown
            rawMaterialSelect.value = rawMaterialSelect.options[0].value; // Set to the first option (usually the placeholder)
            // Clear input fields
            quantityInput.value = '';
            codeInput.value = '';
            uomInput.value = '';
            priceInput.value = '';
            amountInput.value = '';
        }
    }

    // Packing materials details
    function pm_editRow(id, pid) {
        // Hide the text and show the input field
        document.getElementById('pmquantity-text-' + pid).style.display = 'none';
        document.getElementById('pmquantity-' + pid).style.display = 'inline-block';

        // Enable the input field
        document.getElementById('pmquantity-' + pid).disabled = false;

        // Show the save button and hide the edit button
        document.querySelector(`#pmsave-${pid}`).style.display = 'inline-block';
        document.querySelector(`#pmedit-${pid}`).style.display = 'none';
        document.querySelector(`#pmcancel-${pid}`).style.display = 'inline-block';
        document.querySelector(`#pmdelete-${pid}`).style.display = 'none';
    }

    function pm_cancelRow(id, pid) {
        // Hide the input field and show the static text
        document.getElementById('pmquantity-' + pid).style.display = 'none';
        document.getElementById('pmquantity-text-' + pid).style.display = 'inline-block';

        // Reset the input field value to match the static text value (original value)
        const originalValue = document.getElementById('pmquantity-text-' + pid).innerText.trim();
        document.getElementById('pmquantity-' + pid).value = originalValue;

        // Disable the input field to ensure it's not editable in view mode
        document.getElementById('pmquantity-' + pid).disabled = true;

        // Show the "Edit" and "Delete" buttons
        document.querySelector(`#pmedit-${pid}`).style.display = 'inline-block';
        document.querySelector(`#pmdelete-${pid}`).style.display = 'inline-block';
        // Hide the "Save" and "Cancel" buttons
        document.querySelector(`#pmsave-${pid}`).style.display = 'none';
        document.querySelector(`#pmcancel-${pid}`).style.display = 'none';
        // Optionally log the action for debugging purposes
        // console.log(`Edit for row with PID ${pid} canceled. Value reverted to: ${originalValue}`);
    }

    // Function to save the edited data
    function pm_saveRow(id, pid) {
        const pmquantityInput = document.getElementById('pmquantity-' + pid);
        const pmquantity = parseFloat(pmquantityInput.value); // Get and parse the input value
        const pmpriceInput = document.getElementById('pmprice-' + pid); // Get the price cell element
        const pmprice = parseFloat(pmpriceInput.innerText) || 0;
        // Calculate the new amount
        const pmnewAmt = (pmprice * pmquantity).toFixed(2);
        // Log the values for debugging
        console.log('pm-Quantity & price & Amount:', pmquantity, pmprice, pmnewAmt);
        // Perform validation
        if (isNaN(pmquantity) || pmquantity <= 0) {
            alert('Please enter a valid quantity greater than 0.');
            return;
        }
        // Get CSRF token from the meta tag
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Send data to the server via a POST request
        fetch(`/pm-update-pricing/${pid}`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    quantity: pmquantity,
                    amount: pmnewAmt,
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Quantity updated successfully.");
                    // Update the text to show the new quantity
                    document.getElementById('pmquantity-text-' + pid).textContent = pmquantity.toFixed(2);
                    document.getElementById('pmamount-' + pid).textContent = parseFloat(pmnewAmt).toFixed(2);
                    window.location.reload();
                } else {
                    alert("Error updating quantity.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An unexpected error occurred while updating quantity.");
            });

        // Disable the input again and revert UI changes
        pmquantityInput.disabled = true;

        document.getElementById('pmquantity-text-' + pid).style.display = 'inline-block'; // Show the text
        document.getElementById('pmquantity-' + pid).style.display = 'none'; // Hide the input
        // Hide the save button and show the edit button again
        document.querySelector(`#pmsave-${pid}`).style.display = 'none';
        document.querySelector(`#pmedit-${pid}`).style.display = 'inline-block';
        document.querySelector(`#pmcancel-${pid}`).style.display = 'none';
        document.querySelector(`#pmdelete-${pid}`).style.display = 'inline-block';
    }

    function pmforRecipe() {
        // const productSelect = document.getElementById('productSelect');
        const packingMaterialSelect = document.getElementById('packingmaterial');
        const pmQuantityInput = document.getElementById('pmQuantity');
        const pmCodeInput = document.getElementById('pmCode');
        const pmUoMInput = document.getElementById('pmUoM');
        const pmPriceInput = document.getElementById('pmPrice');
        const pmAmountInput = document.getElementById('pmAmount');
        const pmAddButton = document.getElementById('pmaddbtn');
        const packingMaterialTable = document.getElementById('packingMaterialTable');
        const totalPmCostSpan = document.getElementById('totalPmCost'); // Total Cost (A+B+C)

        productSelect.addEventListener('change', function() {
            const product_id = this.value; // Update product_id with the selected value
            console.log('Selected product ID:', product_id); // Debug log to check the selected value
        });

        // Update fields when packing material is selected
        packingMaterialSelect.addEventListener('change', function() {

            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.disabled) {
                clearPmFields();
                return;
            }
            const code = selectedOption.getAttribute('data-code');
            const uom = selectedOption.getAttribute('data-uom');
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            pmCodeInput.value = code || '';
            pmUoMInput.value = uom || '';
            pmPriceInput.value = price.toFixed(2);
            updatePmAmount();
        });

        // Update amount on quantity input
        pmQuantityInput.addEventListener('input', updatePmAmount);

        // Add packing material
        pmAddButton.addEventListener('click', function() {
            const product_id = productSelect.value;
            if (!product_id) {
                alert('Please select a valid product.');
                return;
            }
            // if((parseFloat(recipeOutput.textContent) || 0) <= 0)
            const totalCostSpan = document.getElementById('totalRmCost');
            if ((parseFloat(totalCostSpan.textContent) || 0) <= 0) {
                alert("Please add raw materials");
                return;
            }

            const packingMaterialId = packingMaterialSelect.value;
            const packingMaterialName = packingMaterialSelect.options[packingMaterialSelect.selectedIndex]?.text;
            const quantity = parseFloat(pmQuantityInput.value) || 0;
            const code = pmCodeInput.value;
            const uom = pmUoMInput.value;
            const price = parseFloat(pmPriceInput.value) || 0;
            const amount = parseFloat(pmAmountInput.value) || 0;

            if (!packingMaterialName || !quantity || !code || !uom || !price || !amount) {
                alert('Please fill all fields before adding.');
                return;
            }

            const rows = Array.from(packingMaterialTable.querySelectorAll('tr'));
            const isAlreadyAdded = rows.some(row => row.cells[0].textContent === packingMaterialName);

            if (isAlreadyAdded) {
                alert('This packing material has already been added to the table.');
                clearPmFields();
                return;
            }
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) {
                console.error('CSRF token not found.');
                return;
            }

            if (!packingMaterialId || quantity <= 0 || amount <= 0) {
                alert('Please select a valid packing material and fill all fields correctly.');
                return;
            }

            // Prepare data for server request
            const pmData = {
                product_id: product_id,
                packing_material_id: packingMaterialId,
                quantity: quantity,
                amount: amount,
                code: code,
                uom: uom,
                price: price,
            };

            // Send data to the server
            fetch('/pm-for-recipe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify(pmData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Success:', data);
                        const pmInsertedId = data.pmInserted_id;
                        // Add row to the table
                        const row = `<tr>
                        <td>${packingMaterialName}</td>
                        <td>${quantity}</td>
                        <td>${code}</td>
                        <td>${uom}</td>
                        <td>${price.toFixed(2)}</td>
                        <td>${amount.toFixed(2)}</td>
                        <td>
                            <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${pmInsertedId}">&#x1F5D1;</span>
                        </td>
                    </tr>`;
                        packingMaterialTable.insertAdjacentHTML('beforeend', row);
                        // updatePmTotalCost(amount); // Update the total cost after adding a row
                        clearPmFields();
                        alert('Packing material added successfully!');
                        window.location.reload();
                    } else {
                        alert('Failed to add packing material. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // alert('An error occurred while adding packing material.');
                });
        });

        // Delete row functionality
        packingMaterialTable.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-icon')) {
                const deleteIcon = e.target;
                const row = deleteIcon.closest('tr');
                const pmInsertedId = deleteIcon.getAttribute('data-id');

                // Confirm deletion
                if (!confirm('Are you sure you want to delete this record?')) {
                    return;
                }
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    console.error('CSRF token not found.');
                    return;
                }

                // Send DELETE request to the server
                fetch(`/pm-for-recipe/${pmInsertedId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Server response not OK');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success:', data);
                        // Remove the row from the table
                        const amount = parseFloat(row.cells[5].textContent) || 0;
                        row.remove();
                        // Update the total cost
                        updatePmTotalCost(-amount);

                    })
                    .catch(error => console.error('Error:', error.message));
            }
        });

        // Helper functions
        function updatePmAmount() {
            const price = parseFloat(pmPriceInput.value) || 0;
            const quantity = parseFloat(pmQuantityInput.value) || 0;
            pmAmountInput.value = (price * quantity).toFixed(2);
        }

        function updatePmTotalCost(newAmount) {
            const totalPmCostSpan = document.getElementById('totalPmCost');
            const currentTotal = parseFloat(totalPmCostSpan.textContent) || 0;
            totalPmCostSpan.textContent = (currentTotal + newAmount).toFixed(2);
            updateGrandTotal();

        }

        function clearPmFields() {
            packingMaterialSelect.value = '';
            pmQuantityInput.value = '';
            pmCodeInput.value = '';
            pmUoMInput.value = '';
            pmPriceInput.value = '';
            pmAmountInput.value = '';
        }

    }

    function oh_editRow(id, ohid) {
        // Hide the text and show the input field
        document.getElementById('ohquantity-text-' + ohid).style.display = 'none';
        document.getElementById('ohquantity-' + ohid).style.display = 'inline-block';

        // Enable the input field
        document.getElementById('ohquantity-' + ohid).disabled = false;

        // Show the save button and hide the edit button
        document.querySelector(`#ohsave-${ohid}`).style.display = 'inline-block';
        document.querySelector(`#ohedit-${ohid}`).style.display = 'none';
        document.querySelector(`#ohcancel-${ohid}`).style.display = 'inline-block';
        document.querySelector(`#ohdelete-${ohid}`).style.display = 'none';
    }

    function oh_cancelRow(id, ohid) {
        // Hide the input field and show the static text
        document.getElementById('ohquantity-' + ohid).style.display = 'none';
        document.getElementById('ohquantity-text-' + ohid).style.display = 'inline-block';

        // Reset the input field value to match the static text value (original value)
        const originalValue = document.getElementById('ohquantity-text-' + ohid).innerText.trim();
        document.getElementById('ohquantity-' + ohid).value = originalValue;

        // Disable the input field to ensure it's not editable in view mode
        document.getElementById('ohquantity-' + ohid).disabled = true;

        // Show the "Edit" and "Delete" buttons
        document.querySelector(`#ohedit-${ohid}`).style.display = 'inline-block';
        document.querySelector(`#ohdelete-${ohid}`).style.display = 'inline-block';
        // Hide the "Save" and "Cancel" buttons
        document.querySelector(`#ohsave-${ohid}`).style.display = 'none';
        document.querySelector(`#ohcancel-${ohid}`).style.display = 'none';
        // Optionally log the action for debugging purposes
        // console.log(`Edit for row with PID ${pid} canceled. Value reverted to: ${originalValue}`);
    }
    // Function to save the edited data
    function oh_saveRow(id, ohid) {
        const ohquantityInput = document.getElementById('ohquantity-' + ohid);
        const ohquantity = parseFloat(ohquantityInput.value); // Get and parse the input value
        const ohpriceInput = document.getElementById('ohprice-' + ohid); // Get the price cell element
        const ohprice = parseFloat(ohpriceInput.innerText) || 0;
        // Calculate the new amount
        const ohnewAmt = (ohprice * ohquantity).toFixed(2);
        // Log the values for debugging
        console.log('Quantity & price & Amount:', ohquantity, ohprice, ohnewAmt);
        // Perform validation
        if (isNaN(ohquantity) || ohquantity <= 0) {
            alert('Please enter a valid quantity greater than 0.');
            return;
        }
        // Get CSRF token from the meta tag
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Send data to the server via a POST request
        fetch(`/oh-update-pricing/${ohid}`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    quantity: ohquantity,
                    amount: ohnewAmt,
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Quantity updated successfully.");
                    // Update the text to show the new quantity
                    document.getElementById('ohquantity-text-' + ohid).textContent = ohquantity.toFixed(2);
                    document.getElementById('ohamount-' + ohid).textContent = parseFloat(ohnewAmt).toFixed(2);
                    window.location.reload();
                } else {
                    alert("Error updating quantity.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An unexpected error occurred while updating quantity.");
            });

        // Disable the input again and revert UI changes
        ohquantityInput.disabled = true;

        document.getElementById('ohquantity-text-' + ohid).style.display = 'inline-block'; // Show the text
        document.getElementById('ohquantity-' + ohid).style.display = 'none'; // Hide the input
        // Hide the save button and show the edit button again
        document.querySelector(`#ohsave-${ohid}`).style.display = 'none';
        document.querySelector(`#ohedit-${ohid}`).style.display = 'inline-block';
        document.querySelector(`#ohcancel-${ohid}`).style.display = 'none';
        document.querySelector(`#ohdelete-${ohid}`).style.display = 'inline-block';
    }

    function ohforRecipe() {
        const productSelect = document.getElementById('productSelect');
        const overheadsSelect = document.getElementById('overheads');
        const ohQuantityInput = document.getElementById('ohQuantity');
        const ohCodeInput = document.getElementById('ohCode');
        const ohUoMInput = document.getElementById('ohUoM');
        const ohPriceInput = document.getElementById('ohPrice');
        const ohAmountInput = document.getElementById('ohAmount');
        const ohAddButton = document.getElementById('ohaddbtn');
        const overheadsTable = document.querySelector('#overheadsTable');
        const manualEntryTable = document.getElementById('manualEntryTable');
        const totalOhCostSpan = document.getElementById('totalohCost');
        const totalCostSpan = document.getElementById('totalRmCost');

        productSelect.addEventListener('change', function() {
            const product_id = this.value; // Update product_id with the selected value
            console.log('Selected product ID:', product_id); // Debug log to check the selected value
        });

        if ((parseFloat(totalCostSpan.textContent) || 0) <= 0) {
            alert("Please add raw materials");
            return;
        }
        if (overheadsSelect) {
            console.log("from masters");
            overheadsSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.disabled) {
                    clearOhFields();
                    return;
                }
                const code = selectedOption.getAttribute('data-code');
                const uom = selectedOption.getAttribute('data-uom');
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                ohCodeInput.value = code || '';
                ohUoMInput.value = uom || '';
                ohPriceInput.value = price.toFixed(2);
                updateOhAmount();
            });

            // Update amount on quantity input
            if (ohQuantityInput) {
                ohQuantityInput.addEventListener('input', updateOhAmount);
            }
            // ohQuantityInput.addEventListener('input', updateOhAmount);
            // Add overheads
            ohAddButton.addEventListener('click', function() {

                const product_id = productSelect.value;
                if (!product_id) {
                    alert('Please select a valid product.');
                    return;
                }

                const overheadsId = overheadsSelect.value;
                const overheadsName = overheadsSelect.options[overheadsSelect.selectedIndex]?.text;
                const quantity = parseFloat(ohQuantityInput.value) || 0;
                const code = ohCodeInput.value;
                const uom = ohUoMInput.value;
                const price = parseFloat(ohPriceInput.value) || 0;
                const amount = parseFloat(ohAmountInput.value) || 0;

                if (!overheadsName || !quantity || !code || !uom || !price || !amount) {
                    alert('Please fill all fields before adding.');
                    return;
                }
                if (overheadsTable) {
                    const rows = Array.from(overheadsTable.querySelectorAll('tr'));
                    const isAlreadyAdded = rows.some(row => row.cells[0].textContent === overheadsName);
                    if (isAlreadyAdded) {
                        alert('This overheads has already been added to the table.');
                        clearOhFields();
                        return;
                    }
                }
                //     const newRow = ohtbody.insertRow();
                // newRow.innerHTML = `
                //    <td>${overheadsName}</td>
                //     <td>${quantity}</td>
                //     <td>${code}</td>
                //     <td>${uom}</td>
                //     <td>${price.toFixed(2)}</td>
                //     <td>${amount.toFixed(2)}</td>
                // `;

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!token) {
                    console.error('CSRF token not found.');
                    return;
                }
                if (!overheadsId || quantity <= 0 || amount <= 0) {
                    alert('Please select a valid overheads and fill all fields correctly.');
                    return;
                }
                // Prepare data for server request
                const ohData = {
                    product_id: product_id,
                    overheads_id: overheadsId,
                    quantity: quantity,
                    amount: amount,
                    code: code,
                    uom: uom,
                    price: price,
                };

                // Send data to the server
                fetch('/oh-for-recipe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify(ohData),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Success:', data);
                            const insertedId = data.inserted_id;
                            // Add row to the table
                            const row = `<tr>
                        <td>${overheadsName}</td>
                        <td>${quantity}</td>
                        <td>${code}</td>
                        <td>${uom}</td>
                        <td>${price.toFixed(2)}</td>
                        <td>${amount.toFixed(2)}</td>
                        <td>
                            <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${insertedId}">&#x1F5D1;</span>
                        </td>
                    </tr>`;
                            overheadsTable.insertAdjacentHTML('beforeend', row);
                            // updateOhTotalCost(amount); // Update the total cost after adding a row
                            clearOhFields();
                            alert('Overheads added successfully!');
                            window.location.reload();
                        } else {
                            alert('Failed to add overheads. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // alert('An error occurred while adding overheads.');
                    });
            });
        }
        // Delete row functionality
        if (overheadsTable) {
            overheadsTable.addEventListener('click', function(e) {
                //  const ohMohValue = document.getElementById("oh_mohValue").value;
            // const enterManuallyCheckbox = document.getElementById("entermanually");
                if (e.target.classList.contains('delete-icon')) {
                    const deleteIcon = e.target;
                    const row = deleteIcon.closest('tr');
                    const insertedId = deleteIcon.getAttribute('data-id');

                    // Confirm deletion
                    if (!confirm('Are you sure you want to delete this record?')) {
                        return;
                    }
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!token) {
                        console.error('CSRF token not found.');
                        return;
                    }

                    // Send DELETE request to the server
                    fetch(`/oh-for-recipe/${insertedId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Server response not OK');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Success:', data);
                            // Remove the row from the table
                            const amount = parseFloat(row.cells[5].textContent) || 0;
                            row.remove();
                            // Update the total cost
                            updateOhTotalCost(-amount);
                            window.location.reload();
                        })
                        .catch(error => console.error('Error:', error.message));
                }
            });
        }

        function updateOhAmount() {
            const price = parseFloat(ohPriceInput.value) || 0;
            const quantity = parseFloat(ohQuantityInput.value) || 0;
            ohAmountInput.value = (price * quantity).toFixed(2);
        }

        function updateOhTotalCost(newAmount) {

            const totalOhCostSpan = document.getElementById('totalohCost');
            const currentTotal = parseFloat(totalOhCostSpan.textContent) || 0;
            totalOhCostSpan.textContent = (currentTotal + newAmount).toFixed(2);
            updateGrandTotal();
        }

        function clearOhFields() {
            overheadsSelect.value = '';
            ohQuantityInput.value = '';
            ohCodeInput.value = '';
            ohUoMInput.value = '';
            ohPriceInput.value = '';
            ohAmountInput.value = '';
        }
    }

    function mohforRecipe() {
        let manualOhPriceValue = 0;
        let manualOhPercValue = 0;
        let totalCostSpan = document.getElementById('totalRmCost');
        let totalOhCostSpan = document.getElementById('totalohCost');
        let manualEntryTable = document.getElementById('manualEntryTable');
        const manualOverheads = document.getElementById('manualOverheads');
        const ohMohValue = document.getElementById("oh_mohValue").value;
        let manualTable = document.getElementById('overheadsTable');
        if ((parseFloat(totalCostSpan.textContent) || 0) <= 0) {
            alert("Please add raw materials");
            return;
        }
        if (manualOverheads) {

            function toggleFields1() {
                let manualOhPrice = document.getElementById("manualOhPrice");
                let manualOhPricelab = document.getElementById("manualOhPricelab");
                let manualOhPerc = document.getElementById("manualOhPerc");
                let manualOhPerclab = document.getElementById("manualOhPerclab");
                if (manualOhType.value === "price") {
                    manualOhPrice.style.display = "block";
                    manualOhPricelab.style.display = "block";
                    manualOhPerc.style.display = "none";
                    manualOhPerclab.style.display = "none";
                } else {
                    manualOhPrice.style.display = "none";
                    manualOhPricelab.style.display = "none";
                    manualOhPerc.style.display = "block";
                    manualOhPerclab.style.display = "block";
                }
            }
            manualOhType.addEventListener("change", toggleFields1);

            function calcForManual() {
                let manualOhAmount = 0;
                let manualOhPercent = 0;
                let totalRmCost = document.getElementById('totalRmCost');
                let totalPmCost = document.getElementById('totalPmCost');
                const manualOhType = document.getElementById("manualOhType").value.trim();
                console.log("manual type is:", manualOhType);
                let rmTotal = parseFloat(totalRmCost.textContent) || 0;
                let pmTotal = parseFloat(totalPmCost.textContent) || 0;
                if ((rmTotal + pmTotal) <= 0) {
                    alert("Please add raw materials & packing materials.");
                    return;
                }

                if (manualOhType == 'percentage') {
                    manualOhPercValue = parseFloat(document.getElementById("manualOhPerc").value) || 0;
                    // manualOhPercValue = parseFloat(document.getElementById("manualOhPrice").value) || 0;
                    console.log("manualOhPerc/price : ", manualOhPercValue);
                    console.log(parseFloat(rmTotal));
                    manualOhAmount = ((rmTotal + pmTotal) * manualOhPercValue / 100);
                    console.log(manualOhAmount);
                    manualOhPriceValue = manualOhAmount;
                } else if (manualOhType == 'price') {
                    manualOhPriceValue = parseFloat(document.getElementById("manualOhPrice").value) || 0;
                    console.log(parseFloat(rmTotal));
                    manualOhPercent = (manualOhPriceValue / (rmTotal + pmTotal)) * 100;
                    console.log(manualOhPercent);
                    manualOhPercValue = manualOhPercent;
                }
            }
            const manualOhAddButton = document.getElementById('manualOhaddbtn');
            if (totalOhCostSpan) {
                manualOhAddButton.addEventListener('click', function() {
                    console.log("Add button clicked"); // Debugging

                    const product_id = productSelect.value;
                    if (!product_id) {
                        alert('Please select a valid product.');
                        return;
                    }

                    const manualOverheadsName = document.getElementById('manualOverheads').value.trim();
                    const manualOhType = document.getElementById("manualOhType").value;

                    if (manualOhType === "price") {
                        manualOhPriceValue = parseFloat(document.getElementById('manualOhPrice').value) || 0;
                        calcForManual();
                    } else {
                        manualOhPercValue = parseFloat(document.getElementById('manualOhPerc').value) || 0;
                        console.log("manualOhPercValue: ", manualOhPercValue);
                        calcForManual();
                    }

                    if (!manualOverheadsName || (manualOhType === "price" && manualOhPriceValue <= 0) || (manualOhType === "percentage" && manualOhPercValue <= 0)) {
                        console.log(manualOhPercValue);
                        alert('Please fill all fields before adding.');
                        return;
                    }

                    const existingNames = Array.from(document.querySelectorAll('#manualEntryTable tr td:first-child')).map(td => td.textContent.trim());
                    if (existingNames.includes(manualOverheadsName)) {
                        alert('This overhead name already exists in the table.');
                        clearMohFields();
                        return;
                    }

                    const data = {
                        product_id: product_id, // Ensure product_id is set
                        manualOverheads: manualOverheadsName,
                        manualOverheadsType: manualOhType,
                        manualOhPrice: manualOhPriceValue,
                        manualOhPerc: manualOhPercValue,
                    };

                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!token) {
                        console.error('CSRF token not found.');
                        return;
                    }

                    fetch('/manual-overhead', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Parsed Response:", data);
                            if (data.success) {
                                //alert('Manual overhead added successfully!');
                                console.log("Manual overhead added successfully!.");
                                const insertedId = data.inserted_id; // Get the inserted ID from the response

                                // Add a new row to the table
                                const row = `<tr>
                    <td>${manualOverheadsName}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>${manualOhPercValue}</td>
                    <td>${manualOhPriceValue.toFixed(2)}</td>
                    <td>
                        <span class="delete-icon" style="cursor: pointer; color: red;" title="Remove Row" data-id="${insertedId}">&#x1F5D1;</span>
                    </td>
                </tr>`;

                                // Assuming you have a table with ID 'overheadsTable'
                                if (ohMohValue === '') {
                                    manualTable.insertAdjacentHTML('beforeend', row);
                                } else {
                                    manualEntryTable.insertAdjacentHTML('beforeend', row);
                                }
                                clearMohFields()
                                window.location.reload();
                                // Optionally, update the total cost after adding a row
                                // updateOhTotalCost(); // Assuming this function exists and updates the total cost

                            } else {
                                alert('Failed to save manual overhead.');
                            }
                        })
                        .catch(error => console.error("Fetch error:", error));
                });
            }

            if (manualEntryTable) {
                manualEntryTable.addEventListener('click', function(e) {

                    if (e.target.classList.contains('delete-icon')) {
                        const deleteIcon = e.target;
                        const row = deleteIcon.closest('tr');
                        const insertedId = deleteIcon.getAttribute('data-id');

                        console.log("moh",
                            insertedId); // Debugging

                        // Confirm deletion
                        if (!confirm('Are you sure you want to delete this record?')) {
                            return;
                        }
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        if (!token) {
                            console.error('CSRF token not found.');
                            return;
                        }

                        // Send DELETE request to the server
                        fetch(`/moh-for-recipe/${insertedId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                },
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Server response not OK');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Success:', data);

                                // Remove the row from the table
                                const amount = parseFloat(row.cells[5].textContent) || 0;
                                row.remove();
                                window.location.reload();
                                // Update the total cost
                                // updateOhTotalCost(-amount);
                            })
                            .catch(error => console.error('Error:', error.message));
                    }
                });
            }
        }
    }

    function updateGrandTotal() {
        const totalCostSpan = document.getElementById('totalRmCost');
        const totalPmCostSpan = document.getElementById('totalPmCost');
        const totalOhCostSpan = document.getElementById('totalohCost');
        const totalCostInput = document.getElementById('totalcost');
        const rawMaterialTotal = parseFloat(totalCostSpan.textContent) || 0;
        const packingMaterialTotal = parseFloat(totalPmCostSpan.textContent) || 0;
        const overheadsTotal = parseFloat(totalOhCostSpan.textContent) || 0;
        const grandTotal = rawMaterialTotal + packingMaterialTotal + overheadsTotal; // Add other totals if needed
        totalCostInput.value = grandTotal.toFixed(2); // Display in Total Cost (A+B+C)
        // let totalcostval = parseFloat(document.getElementById('totalcost').value);
            let countrpoutput = parseFloat(document.getElementById('recipeOutput').value);
            let singleunit = grandTotal/countrpoutput;
            const singlecost = document.getElementById('singletotalcost');
            singlecost.value = singleunit.toFixed(2);
    }

    function clearMohFields() {
        // Clear the overheads name
        document.getElementById('manualOverheads').value = '';

        // Reset the overheads type dropdown to the default value
        document.getElementById('manualOhType').value = 'price';

        // Clear the overheads price input
        document.getElementById('manualOhPrice').value = '';

        // Clear the overheads percentage input
        document.getElementById('manualOhPerc').value = '';

        // Ensure the correct fields are displayed
        document.getElementById('manualOhPrice').style.display = '';
        document.getElementById('manualOhPricelab').style.display = '';
        document.getElementById('manualOhPerc').style.display = 'none';
        document.getElementById('manualOhPerclab').style.display = 'none';
    }
</script>
