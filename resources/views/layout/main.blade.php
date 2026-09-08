<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ APPLICATION_NAME }} - @yield('title') </title>
    <link rel="icon" href="https://img.icons8.com/fluency/48/shield.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}" />

    <style>
        .mini-chart-bar {
            display: flex;
            align-items: flex-end;
            gap: 4px;
            height: 50px;
        }

        .mini-chart-bar .bar {
            flex: 1;
            background: var(--primary-light);
            border-radius: 3px 3px 0 0;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
        }

        .mini-chart-bar .bar:hover {
            background: var(--primary);
        }

        .mini-chart-bar .bar .bar-tip {
            position: absolute;
            bottom: calc(100% + 4px);
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: #fff;
            font-size: .65rem;
            padding: 2px 5px;
            border-radius: 3px;
            white-space: nowrap;
            display: none;
        }

        .mini-chart-bar .bar:hover .bar-tip {
            display: block;
        }

        .donut-wrap {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .donut-wrap canvas {
            position: absolute;
            top: 0;
            left: 0;
        }

        .donut-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            line-height: 1.2;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            padding: .7rem 0;
            border-bottom: 1px solid var(--border);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
        }
    </style>

    @stack('style')

</head>

<body>
    <div class="admin-layout">
        @include('layout.sidebar')
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <main class="admin-main">

            @include('layout.header')

            <div class="admin-content">

                {{-- <div class="p-4 mb-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3"
                    style="background:linear-gradient(135deg,var(--dark),var(--primary));color:#fff">
                    <div>
                        <h5 class="fw-800 mb-1">Good <span id="greeting">{{ now()->hour }}</span>, <span id="welcomeName">Admin</span>! 👋</h5>
                        <p class="mb-0 small" style="opacity:.75">Here's what's happening in your store today.</p>
                    </div>
                    <a href="products.html" class="btn btn-light btn-sm fw-700 rounded-pill">
                        <i class="ri-add-line me-1"></i>Add Product
                    </a>
                </div> --}}

                <div class="card">
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>

                @stack('additional_section')
    
            </div>
        </main>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/datatable.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

</html>