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
    </style>
</head>


<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="#" class="logo d-flex align-items-center" style="text-decoration: none;">
                <img src="/assets/img/logo.jpeg" alt="Recipe Management System Logo" style="height: 100%; width: 70%;">
            </a>
            <!-- <i class="bi bi-list toggle-sidebar-btn"></i> -->
        </div>

        <!-- End Logo -->


        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->

                <li class="nav-item dropdown">

                    <!-- Search Icon -->
                <li>
                    <a class="nav-link nav-icon" href="#" onclick="toggleSearchBox()">
                        <i class="bi bi-search"></i>
                    </a>
                </li>

                <!-- Search Box -->
                <div id="search-box" style="display: none; position: absolute; top: 20px; right: 200px; background-color: #fff; border: 1px solid #ccc; padding: 10px; border-radius: 5px; z-index: 1000;">
                    <input type="text" placeholder="Search..." style="padding: 5px; width: 200px;">
                    <button onclick="performSearch()" style="padding: 5px;">Go</button>
                </div>


                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-primary badge-number">4</span>
                </a><!-- End Notification Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        You have 4 new notifications
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-exclamation-circle text-warning"></i>
                        <div>
                            <h4>Lorem Ipsum</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>30 min. ago</p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-x-circle text-danger"></i>
                        <div>
                            <h4>Atque rerum nesciunt</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>1 hr. ago</p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-check-circle text-success"></i>
                        <div>
                            <h4>Sit rerum fuga</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>2 hrs. ago</p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-info-circle text-primary"></i>
                        <div>
                            <h4>Dicta reprehenderit</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>4 hrs. ago</p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="dropdown-footer">
                        <a href="#">Show all notifications</a>
                    </li>

                </ul><!-- End Notification Dropdown Items -->

                </li><!-- End Notification Nav -->

                <li>
                    <a class="nav-link nav-icon" href="login.html" ">
                                <i class=" bi bi-person"></i>
                    </a>
                </li>

                <li>
                    <a class="nav-link nav-icon" href="{{ 'login' }}" style="color: red;">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </li>

            </ul><!-- End Profile Dropdown Items -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar" style="background-color: #BAEAF9;">

        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/dashboard" style="background-color: rgb(186, 234, 249); font-size:17px;">
                    {{-- <i class="bi bi-grid"></i> --}}
                    <img src="/assets/img/dashboard_pic.svg" alt="dashboard Icon" style="width: 1.2em; height: auto; margin-right: 4px;">
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('rawmaterial') || Request::is('showcategoryitem') || Request::is('products') || Request::is('overheads') || Request::is('packingmaterial') ? '' : 'collapsed' }}" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" style="background-color: rgb(186, 234, 249); font-size:17px;text-decoration: none;">
                    {{-- <i class="bi bi-menu-button-wide"></i> --}}
                    <img src="/assets/img/master_pic.svg" alt="master Icon" style="width: 0.8em; height: auto; margin-right: 10px;">
                    <span>Masters</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav"
                 class="nav-content collapse {{ Request::is('rawmaterial') ||  Request::routeIs('rawMaterial.edit') || Request::is('showcategoryitem') || Request::is('addcategory') || Request::routeIs('categoryitem.edit') || Request::is('addrawmaterial') || Request::routeIs('packingMaterial.edit') || Request::is('packingmaterial') || Request::is('addpackingmaterial') || Request::is('overheads') ||  Request::is('addoverheads') ||  Request::routeIs('overheads.edit') || Request::is('products') || Request::routeIs('products.edit') || Request::is('addproduct')   ? 'show' : '' }}"
                 data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/rawmaterial"  class="{{ Request::is('rawmaterial') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
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
                    <li>
                        <a href="/products" class="{{ Request::is('products') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
                            <span>Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="/showcategoryitem" class="{{ Request::is('showcategoryitem') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none">
                            <span>Category</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Masters Nav -->

            <li class="nav-item">
                <a class="nav-link {{ Request::is('receipedetails') ||  Request::is('addreceipedetails') || Request::is('pricing') ? '' : 'collapsed'}} " data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" style="background-color: rgb(186, 234, 249); font-size:17px;text-decoration: none;">
                    {{-- <i class="bi bi-journal-text"></i> --}}
                    <img src="/assets/img/receipe_pic.svg" alt="receipe Icon" style="width: 1em; height: auto; margin-right:10px;">
                    <span>Recipe</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="forms-nav" class="nav-content collapse {{ Request::is('receipedetails') ||  Request::is('addreceipedetails') || Request::is('pricing') ? 'show' :'' }}"
                data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/receipedetails" class="{{ Request::is('receipedetails') || Request::is('addreceipedetails') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
                            <span>Details & Description</span>
                        </a>
                    </li>
                    <li>
                        <a href="/pricing" class="{{Request::is('pricing') ? 'active' : '' }}" style="background-color: rgb(186, 234, 249); font-size:16px;text-decoration: none;">
                            <span>Pricing</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Recipe Nav -->
        </ul>
    </aside>

    <!-- End Sidebar-->

    <main>
        @yield('content') <!-- This is where child content will be injected -->
    </main>

    <!-- ======= Footer ======= -->
    <!-- <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>RMS</span></strong>. All Rights Reserved
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


    <script>
        // Toggle the search box visibility
        function toggleSearchBox() {
            const searchBox = document.getElementById("search-box");
            if (searchBox.style.display === "none") {
                searchBox.style.display = "block";
            } else {
                searchBox.style.display = "none";
            }
        }

        // Example: Perform a search (you can replace this with actual search functionality)
        function performSearch() {
            const input = document.querySelector("#search-box input");
            alert(`Searching for: ${input.value}`);
        }
    </script>

</body>

</html>
