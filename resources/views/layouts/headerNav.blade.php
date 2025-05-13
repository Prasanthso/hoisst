<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - Recipe Management System</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ asset('/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('/assets/img/newlogo.png') }}" rel="newlogo">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Laravel Mix CSS -->
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    <!-- Template Main CSS File -->
    <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet">

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Laravel Mix JS -->
    <script src="{{ mix('/js/app.js') }}" defer></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        * {
            font-family: "Poppins", serif;
        }

        /* dashboard left side bar  */
        .btn-primary {
            background-color: #0079AD !important;
            border-color: #0079AD !important;
            color: white !important;
        }

        .dropdown-menu.notifications {
            min-width: 400px;
            max-width: 600px;
            white-space: normal;
        }
    </style>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="#" class="d-flex align-items-center" style="text-decoration: none;">
                <img src="/assets/img/logo.svg" alt="Recipe Management System Logo" style="width:45%;">
            </a>
            <!-- <i class="bi bi-list toggle-sidebar-btn"></i> -->
        </div>

        <!-- End Logo -->


        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle" href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->

                <li>
                    <div class="search-bar mt-3">
                        <form class="search-form d-flex align-items-center" id="menu-search-form" action="#" onsubmit="return false;">
                            <input type="text" id="menu-search-input" name="query" placeholder="Search Menu" title="Enter search keyword">
                            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                        </form>
                    </div><!-- End Search Bar -->
                </li>

                @php
                $alertTypeCount = 0;
                $alertTypeCount += count($lowMarginProducts) > 0 ? 1 : 0;
                $alertTypeCount += count($productPriceThresholdCollection) > 0 ? 1 : 0;
                $alertTypeCount += count($productPriceAlertCollection) > 0 ? 1 : 0;
                $alertTypeCount += count($rawMaterialsPriceThresholdCollection) > 0 ? 1 : 0;
                $alertTypeCount += count($rawMaterialsPriceAlertCollection) > 0 ? 1 : 0;
                $alertTypeCount += count($packingMaterialsPriceThresholdCollection) > 0 ? 1 : 0;
                $alertTypeCount += count($packingMaterialsPriceAlertCollection) > 0 ? 1 : 0;
                @endphp

                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-primary badge-number">{{ $alertTypeCount }}</span>
                    </a><!-- End Notification Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class="dropdown-header">
                            You have {{ $alertTypeCount }} new notifications
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <!-- Low Margin Products -->
                        @if(count($lowMarginProducts) > 0)
                        <li class="notification-item">
                            <i class="bi bi-x-circle text-danger"></i>
                            <div class="d-flex flex-column" style="width: 100%;">
                                <div>
                                    <h4>Low Margin Alert</h4>
                                    @foreach($lowMarginProducts->slice(0, 2) as $product)
                                    <p>* {{ $product['name'] }}: Margin {{ $product['margin'] }}% (Threshold: {{ $product['threshold'] }}%)</p>
                                    @endforeach
                                </div>
                                @if(count($lowMarginProducts) > 2)
                                <div class="text-end pe-3 mt-1">
                                    <a href="#" style="font-size: 12px;">View all</a>
                                </div>
                                @endif
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif


                        <!-- Product Price Threshold Exceeded -->
                        @if(count($productPriceThresholdCollection) > 0)
                        <li class="notification-item">
                            <i class="bi bi-exclamation-circle text-warning"></i>
                            <div class="d-flex flex-column" style="width: 100%;">
                                <div>
                                    <h4 style="font-size: 14px; margin-bottom: 4px;">Product Price Threshold Exceeded</h4>
                                    @foreach($productPriceThresholdCollection->slice(0, 2) as $product)
                                    <p style="font-size: 12px; margin-bottom: 2px;">* {{ $product['name'] }} (Code: {{ $product['pdcode'] }}): Price {{ $product['price'] }} > Threshold {{ $product['threshold'] }}</p>
                                    @endforeach
                                </div>

                                @if(count($productPriceThresholdCollection) > 2)
                                <div class="text-end pe-3 mt-1">
                                    <a href="/productNotification" style="font-size: 12px;">View all</a>
                                </div>
                                @endif
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif


                        <!-- Product Price Update Alert -->
                        @if(count($productPriceAlertCollection) > 0)
                        <li class="notification-item">
                            <i class="bi bi-exclamation-circle text-warning"></i>
                            <div class="d-flex flex-column" style="width: 100%;">
                                <div>
                                    <h4>Product Price Update Alert</h4>
                                    @foreach($productPriceAlertCollection->slice(0, 2) as $productPriceAlert)
                                    <p>* {{ $productPriceAlert['name'] }} (Code: {{ $productPriceAlert['pdcode'] }})</p>
                                    @endforeach
                                </div>
                                @if(count($productPriceAlertCollection) > 2)
                                <div class="text-end pe-3 mt-1">
                                    <a href="/productNotification" style="font-size: 12px;">View all</a>
                                </div>
                                @endif
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif

                        <!-- Raw Material Price Threshold Exceeded -->
                        @if(count($rawMaterialsPriceThresholdCollection) > 0)
                        <li class="notification-item">
                            <i class="bi bi-exclamation-circle text-warning"></i>
                            <div class="d-flex flex-column" style="width: 100%;">
                                <div>
                                    <h4>Raw Material Price Threshold Exceeded</h4>
                                    @foreach($rawMaterialsPriceThresholdCollection->slice(0, 2) as $rawMaterials)
                                    <p>* {{ $rawMaterials['name'] }} (Code: {{ $rawMaterials['rmcode'] }}): Price {{ $rawMaterials['price'] }} > Threshold {{ $rawMaterials['threshold'] }}</p>
                                    @endforeach
                                </div>
                                @if(count($rawMaterialsPriceThresholdCollection) > 2)
                                <div class="text-end pe-3 mt-1">
                                    <a href="/rawmaterialNotification" style="font-size: 12px;">View all</a>
                                </div>
                                @endif

                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif

                        <!-- Raw Material Price Update Alert -->
                        @if(count($rawMaterialsPriceAlertCollection) > 0)
                        <li class="notification-item">
                            <i class="bi bi-exclamation-circle text-warning"></i>
                            <div class="d-flex flex-column" style="width: 100%;">
                                <div>
                                    <h4>Raw Material Price Update Alert</h4>
                                    @foreach($rawMaterialsPriceAlertCollection->slice(0, 2) as $rawMaterialsPriceAlert)
                                    <p>* {{ $rawMaterialsPriceAlert['name'] }} (Code: {{ $rawMaterialsPriceAlert['rmcode'] }})</p>
                                    @endforeach
                                </div>
                                @if(count($rawMaterialsPriceAlertCollection) > 2)
                                <div class="text-end pe-3 mt-1">
                                    <a href="/rawmaterialNotification" style="font-size: 12px;">View all</a>
                                </div>
                                @endif
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif

                        <!-- Packing Material Price Threshold Exceeded -->
                        @if(count($packingMaterialsPriceThresholdCollection) > 0)
                        <li class="notification-item">
                            <i class="bi bi-exclamation-circle text-warning"></i>
                            <div class="d-flex flex-column" style="width: 100%;">
                                <div>
                                    <h4>Packing Material Price Threshold Exceeded</h4>
                                    @foreach($packingMaterialsPriceThresholdCollection->slice(0, 2) as $packingMaterials)
                                    <p>* {{ $packingMaterials['name'] }} (Code: {{ $packingMaterials['pmcode'] }}): Price {{ $packingMaterials['price'] }} > Threshold {{ $packingMaterials['threshold'] }}</p>
                                    @endforeach
                                </div>
                                @if(count($packingMaterialsPriceThresholdCollection) > 2)
                                <div class="text-end pe-3 mt-1">
                                    <a href="/packingmaterialNotification" style="font-size: 12px;">View all</a>
                                </div>
                                @endif
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif

                        <!-- Packing Material Price Update Alert -->
                        @if(count($packingMaterialsPriceAlertCollection) > 0)
                        <li class="notification-item">
                            <i class="bi bi-exclamation-circle text-warning"></i>
                            <div class="d-flex flex-column" style="width: 100%;">
                                <div>
                                    <h4>Packing Material Price Update Alert</h4>
                                    @foreach($packingMaterialsPriceAlertCollection->slice(0, 2) as $packingMaterialsPriceAlert)
                                    <p>* {{ $packingMaterialsPriceAlert['name'] }} (Code: {{ $packingMaterialsPriceAlert['pmcode'] }})</p>
                                    @endforeach
                                </div>
                                @if(count($packingMaterialsPriceAlertCollection) > 2)
                                <div class="text-end pe-3 mt-1">
                                    <a href="/packingmaterialNotification" style="font-size: 12px;">View all</a>
                                </div>
                                @endif
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @endif
                    </ul>
                </li><!-- End Notification Nav -->

                @php
                $user = Auth::user();
                @endphp

                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <!-- Profile Image -->
                        <img src="https://static.vecteezy.com/system/resources/previews/000/439/863/original/vector-users-icon.jpg" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px;">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user->name }}</span>
                    </a><!-- End Profile Image Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>Name: {{ $user->name }}</h6>
                            <span>Role: {{ $user->role ?? 'User' }}</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                                <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sign Out</span>
                                </a>
                            </form>
                        </li>
                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            </ul>
        </nav><!-- End Icons Navigation -->
    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar" style="background-color: #BAEAF9;">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/dashboard" style="background-color: rgb(186, 234, 249); font-size:17px;">
                    <img src="/assets/img/Dashboard.svg" alt="dashboard Icon" style="width: 1.1em; height: auto; margin-right: 6px;">
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('rawmaterial') || Request::is('showcategoryitem') || Request::is('products') || Request::is('overheads') || Request::is('packingmaterial') ? '' : 'collapsed' }}" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" style="background-color: rgb(186, 234, 249); font-size:17px;text-decoration: none;">
                    <img src="/assets/img/Masters.svg" alt="master Icon" style="width: 1em; height: auto; margin-right: 10px;">
                    <span>Masters</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav"
                    class="nav-content collapse {{ Request::is('rawmaterial') || Request::routeIs('rawMaterial.edit') || Request::is('showcategoryitem') || Request::is('addcategory') || Request::routeIs('categoryitem.edit') || Request::is('addrawmaterial') || Request::routeIs('packingMaterial.edit') || Request::is('packingmaterial') || Request::is('addpackingmaterial') || Request::is('overheads') || Request::is('addoverheads') || Request::routeIs('overheads.edit') || Request::is('products') || Request::routeIs('products.edit') || Request::is('addproduct') ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/showcategoryitem" class="{{ Request::is('showcategoryitem') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none">
                            <span>Category</span>
                        </a>
                    </li>
                    <li>
                        <a href="/products" class="{{ Request::is('products') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
                            <span>Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="/rawmaterial" class="{{ Request::is('rawmaterial') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
                            <span>Raw Materials</span>
                        </a>
                    </li>
                    <li>
                        <a href="/packingmaterial" class="{{ Request::is('packingmaterial') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
                            <span>Packing Materials</span>
                        </a>
                    </li>
                    <li>
                        <a href="/overheads" class="{{ Request::is('overheads') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
                            <span>Overheads</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Masters Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('receipedetails') || Request::is('addreceipedetails') || Request::is('pricing-records') ? '' : 'collapsed'}} " data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" style="background-color: rgb(186, 234, 249); font-size:17px;text-decoration: none;">
                    <img src="/assets/img/Recipe.svg" alt="recipe Icon" style="width: 1em; height: auto; margin-right:10px;">
                    <span>Recipe</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="forms-nav" class="nav-content collapse {{ Request::is('receipedetails') || Request::is('addreceipedetails') || Request::routeIs('editrecipedetails.edit') || Request::is('pricing-records') || Request::is('pricing') || Request::routeIs('receipepricing.edit') || Request::is('overallcosting') || Request::is('addoverallcosting') || Request::routeIs('overallcosting.edit') ? 'show' :'' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/receipedetails" class="{{ Request::is('receipedetails') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
                            <span>Details & Description</span>
                        </a>
                    </li>
                    <li>
                        <a href="/pricing-records" class="{{Request::is('pricing-records') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
                            <span>Pricing</span>
                        </a>
                    </li>
                    <li>
                        <a href="/overallcosting" class="{{Request::is('overallcosting') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
                            <span>Overall Costing</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Recipe Nav -->

            <li class="nav-item">
                <a class="nav-link" href="/recipepricing" style="background-color: rgb(186, 234, 249); font-size:17px;">
                    <img src="/assets/img/Recipepricing.svg" alt="dashboard Icon" style="width: 1em; height: auto; margin-right: 8px;">
                    <span>Recipe Pricing</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/viewalert" style="background-color: rgb(186, 234, 249); font-size:17px;">
                    <img src="/assets/img/Recipepricing.svg" alt="dashboard Icon" style="width: 1em; height: auto; margin-right: 8px;">
                    <span>Alert</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/report" style="background-color: rgb(186, 234, 249); font-size:17px;">
                    <img src="/assets/img/Report.svg" alt="dashboard Icon" style="width: 1em; height: auto; margin-right: 9px;">
                    <span>Report</span>
                </a>
            </li>
        </ul>
    </aside><!-- End Sidebar-->

    <main>
        @yield('content') <!-- This is where child content will be injected -->
    </main>

    <!-- ======= Footer ======= -->
    <!-- <footer id="footer" class="footer">
        <div class="copyright">
            © Copyright <strong><span>RMS</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://www.hoisst.in/rms">Hoisst</a>
        </div>
    </footer> -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('/assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('/assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('/js/main.js') }}"></script>

    <!-- Search Functionality Script -->
    <script>
        document.getElementById('menu-search-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            const query = document.getElementById('menu-search-input').value.trim().toLowerCase();

            // Menu mappings
            const menuMap = {
                'dashboard': '/dashboard',
                'product': '/products',
                'raw material': '/rawmaterial',
                'packing material': '/packingmaterial',
                'overhead': '/overheads',
                'category': '/showcategoryitem',
                'recipe': '/receipedetails',
                'pricing': '/pricing-records',
                'overall costing': '/overallcosting',
                'recipe pricing': '/recipepricing',
                'report': '/report'
            };

            // Find a matching menu
            for (const key in menuMap) {
                if (query.includes(key)) {
                    window.location.href = menuMap[key];
                    return;
                }
            }

            // If no match is found
            alert('No matching menu found for: ' + query);
        });

        document.getElementById('menu-search-input').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                document.getElementById('menu-search-form').dispatchEvent(new Event('submit'));
            }
        });
    </script>

</body>

</html>