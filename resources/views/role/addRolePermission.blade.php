@extends('layouts.headerNav')

@section('content')
<main id="main" class="main">
    <div class="pagetitle d-flex px-4 pt-4 justify-content-between">
        <h1>VIEW USER ROLE DETAILS</h1>
    </div>

    <section class="section dashboard">
        <div class="row">
            <h5 class="px-4">Role : <span style="color:rgb(237, 11, 11);">{{ $role->name }}</span></h5>

            @if(session('success'))
            <div id="success-message" class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="col-lg-2 px-1 mt-3">
                <div class="card" style="background-color: #EEEEEE;">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                @foreach($permission_category as $category)
                                <div class="form-check category-item">
                                    <input class="form-check-input category-checkbox" type="checkbox"
                                        data-category-id="{{ $category->id }}" value="{{ $category->id }}"
                                        id="category_{{ $category->id }}">
                                    <label class="form-check-label" for="category_{{ $category->id }}">
                                        {{ $category->categoryName }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mt-3">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="menu-items"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .form-check-inline {
        margin-bottom: 26px;
    }

    #menu-items {
        border: 1px solid #ccc;
        padding: 10px;
        width: 100%;
        height: 400px;
        overflow-y: auto;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
        const menuItemsDiv = document.getElementById('menu-items');
        const allMenus = @json($permission_menu);
        const allPermissions = @json($permissions);

        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const categoryId = this.dataset.categoryId;
                menuItemsDiv.innerHTML = '';

                categoryCheckboxes.forEach(cb => {
                    if (cb !== this) {
                        cb.checked = false;
                    }
                });

                const filteredMenus = allMenus.filter(menu => menu.permission_category_id === parseInt(categoryId));
                const categoryPermissions = allPermissions.filter(permission => permission.permission_category_id === parseInt(categoryId) && permission.menuCategoryId === null);

                if (this.checked) {
                    if (filteredMenus.length > 0) {
                        let menuHtml = '';
                        filteredMenus.forEach(menu => {
                            const menuPermissions = allPermissions.filter(permission => permission.menuCategoryId === menu.id);
                            if (menuPermissions.length > 0) {
                                menuHtml += `
                                    <h6>${menu.menuName}</h6>
                                    <div id="menu-permissions-${menu.id}"></div>
                                `;
                            }
                        });
                        menuItemsDiv.innerHTML = menuHtml;

                        filteredMenus.forEach(menu => {
                            const menuPermissions = allPermissions.filter(permission => permission.menuCategoryId === menu.id);
                            if (menuPermissions.length > 0) {
                                const permissionsHtml = menuPermissions.map(permission => `
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input permission-checkbox" type="checkbox"
                                               data-permission-id="${permission.id}" value="${permission.id}" id="permission_${permission.id}">
                                        <label class="form-check-label" for="permission_${permission.id}">
                                            ${permission.name}
                                        </label>
                                    </div>
                                `).join('');
                                document.getElementById(`menu-permissions-${menu.id}`).innerHTML = permissionsHtml;
                            }
                        });

                    } else {
                        menuItemsDiv.innerHTML = categoryPermissions.map(permission => `
                            <div class="form-check form-check-inline">
                                <input class="form-check-input permission-checkbox" type="checkbox"
                                       data-permission-id="${permission.id}" value="${permission.id}" id="permission_${permission.id}">
                                <label class="form-check-label" for="permission_${permission.id}">
                                    ${permission.name}
                                </label>
                            </div>
                        `).join('');
                    }
                } else {
                    menuItemsDiv.innerHTML = '';
                }
            });
        });
    });
</script>

@endsection

<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>