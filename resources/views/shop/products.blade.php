@php
    $showViewAll = 1;
    $colorArr = ['#dc3545;', '#198754', '#0d6efd;', '#ec4899;'];
    $randArr = rand(0, 3);
    $lastColor = null;
@endphp

@extends('layout.shop.main')

@section('title', 'All Products')

<div class="breadcrumb-wrap">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" id="breadLabel">All Products</li>
            </ol>
        </nav>
    </div>
</div>

@section('content')
    <section class="shop-layout py-4">
        <div class="container">

            <!-- Mobile filter toggle -->
            <div class="d-lg-none mb-3">
                <button class="btn-filter-toggle w-100" id="mobileFilterToggle">
                    <i class="ri-equalizer-2-line me-2"></i>Filters
                    <span class="filter-active-badge d-none ms-2" id="mobileFilterBadge">0</span>
                    <i class="ri-arrow-down-s-line ms-auto" id="mobileFilterArrow"></i>
                </button>
            </div>

            <div class="row g-4">

                <div class="col-lg-3" id="filterSidebarCol">
                    <aside class="filter-sidebar" id="filterSidebar">

                        <div class="filter-sidebar-header">
                            <span><i class="ri-equalizer-2-fill me-2"></i>Filters</span>
                            <button class="btn-clear-all" id="clearAllFilters">Clear All</button>
                        </div>

                        <div id="activeFilterTags" class="active-filter-tags d-none"></div>

                        <div class="filter-section" id="catSection">
                            <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#catCollapse"
                                aria-expanded="true">
                                <span><i class="ri-grid-line me-2"></i>Category</span>
                                <i class="ri-arrow-down-s-line toggle-icon"></i>
                            </div>
                            <div class="collapse show" id="catCollapse">
                                <div class="filter-section-body" id="categoryFilters">
                                    @forelse ($category as $key => $value)
                                        @php
                                            $availableColors = array_diff($colorArr, [$lastColor]);
                                            $currentColor = $availableColors[array_rand($availableColors)];
                                            $lastColor = $currentColor;
                                        @endphp

                                        <label class="filter-check-item cat-item" data-cat="{{ $value->category_name }}">
                                            <input type="checkbox" class="cat-checkbox" value="{{ $value->category_name }}">
                                            <span class="cat-dot" style="background:{{ $currentColor }}"></span>
                                            <span class="flex-grow-1">{{ $value->category_name }}</span>
                                            <span class="filter-count-badge">{{ $value->products_count }}</span>
                                        </label>

                                    @empty
                                        <span>No Product Categories Found</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="filter-section">
                            <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#ratingCollapse"
                                aria-expanded="true">
                                <span><i class="ri-star-line me-2"></i>Rating</span>
                                <i class="ri-arrow-down-s-line toggle-icon"></i>
                            </div>
                            <div class="collapse show" id="ratingCollapse">
                                <div class="filter-section-body" id="ratingFilters">
                                    <label class="filter-radio-item">
                                        <input type="radio" name="ratingFilter" value="0" checked="">
                                        <span class="filter-radio-label">
                                            <span class="rating-stars-filter all-star">All Ratings</span>
                                        </span>
                                    </label>
                                    <label class="filter-radio-item">
                                        <input type="radio" name="ratingFilter" value="4">
                                        <span class="filter-radio-label">
                                            <span class="rating-stars-filter">★★★★</span>
                                            <span class="ms-1 text-muted small">4 &amp; above</span>
                                        </span>
                                    </label>
                                    <label class="filter-radio-item">
                                        <input type="radio" name="ratingFilter" value="3">
                                        <span class="filter-radio-label">
                                            <span class="rating-stars-filter">★★★</span>
                                            <span class="ms-1 text-muted small">3 &amp; above</span>
                                        </span>
                                    </label>
                                    <label class="filter-radio-item">
                                        <input type="radio" name="ratingFilter" value="2">
                                        <span class="filter-radio-label">
                                            <span class="rating-stars-filter">★★</span>
                                            <span class="ms-1 text-muted small">2 &amp; above</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="filter-section">
                            <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#availCollapse"
                                aria-expanded="true">
                                <span><i class="ri-checkbox-circle-line me-2"></i>Availability</span>
                                <i class="ri-arrow-down-s-line toggle-icon"></i>
                            </div>
                            <div class="collapse show" id="availCollapse">
                                <div class="filter-section-body">
                                    <label class="filter-check-item">
                                        <input type="checkbox" id="filterInStock">
                                        <span>In Stock Only</span>
                                    </label>
                                    <label class="filter-check-item">
                                        <input type="checkbox" id="filterOnSale">
                                        <span>On Sale</span>
                                        <span class="filter-check-badge sale-badge ms-auto">SALE</span>
                                    </label>
                                    <label class="filter-check-item">
                                        <input type="checkbox" id="filterFeatured">
                                        <span>Featured Items</span>
                                        <span class="filter-check-badge featured-badge ms-auto">★</span>
                                    </label>
                                    <label class="filter-check-item">
                                        <input type="checkbox" id="filterNew">
                                        <span>New Arrivals</span>
                                        <span class="filter-check-badge new-badge ms-auto">NEW</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="filter-section">
                            <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#tagCollapse"
                                aria-expanded="false">
                                <span><i class="ri-price-tag-3-line me-2"></i>Popular Tags</span>
                                <i class="ri-arrow-down-s-line toggle-icon"></i>
                            </div>
                            <div class="collapse" id="tagCollapse">
                                <div class="filter-section-body">
                                    <div class="tag-cloud" id="tagCloud">
                                        @forelse ($tags as $key => $value)
                                            <button class="tag-chip-btn" data-tag="{{ $value->tag_name }}">
                                                {{ $value->tag_name }} <span
                                                    class="tag-cnt">{{ $value->get_products_count }}</span>
                                            </button>
                                        @empty
                                            <button class="tag-chip-btn">
                                                No Tags Found..
                                            </button>
                                        @endforelse

                                    </div>
                                </div>
                            </div>
                        </div>

                    </aside>
                </div>

                <div class="col-lg-9">

                    <div class="shop-toolbar">
                        <div class="d-flex align-items-center gap-2 flex-wrap flex-grow-1">
                            <!-- Search in page -->
                            <div class="shop-search-wrap">
                                <i class="ri-search-line shop-search-icon"></i>
                                <input type="text" class="shop-search-input" id="shopSearch"
                                    placeholder="Search products…">
                                <button class="shop-search-clear d-none" id="clearSearch"><i
                                        class="ri-close-line"></i></button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <!-- Sort -->
                            <div class="shop-sort-wrap">
                                <i class="ri-sort-desc me-1 text-muted"></i>
                                <select class="shop-sort-select" id="sortSelect" aria-label="Sort products">
                                    <option value="default">Relevance</option>
                                    <option value="price_asc">Price: Low to High</option>
                                    <option value="price_desc">Price: High to Low</option>
                                    <option value="rating">Top Rated</option>
                                    <option value="newest">Newest First</option>
                                    <option value="discount">Biggest Discount</option>
                                </select>
                            </div>

                            <div class="view-toggle-group" role="group" aria-label="View mode">
                                <button class="view-toggle-btn active" id="gridViewBtn" title="Grid View"
                                    aria-pressed="true">
                                    <i class="ri-grid-fill"></i>
                                </button>
                                <button class="view-toggle-btn" id="listViewBtn" title="List View" aria-pressed="false">
                                    <i class="ri-list-check-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="searchResultMsg" class="search-result-msg d-none">
                        <i class="ri-search-line"></i>
                        <span id="searchResultText"></span>
                        <button class="search-result-clear" id="clearSearchMsg"><i
                                class="ri-close-line me-1"></i>Clear</button>
                    </div>

                    <div class="row" id="productsGrid">
                    </div>

                    <!-- No results -->
                    <div id="noResults" class="empty-state d-none">
                        <div class="empty-icon"><i class="ri-search-line"></i></div>
                        <h5>No products found</h5>
                        <p class="text-muted">Try adjusting your filters or search term.</p>
                        <button class="btn-primary-custom" id="resetAllBtn">
                            <i class="ri-refresh-line me-1"></i> Reset All Filters
                        </button>
                    </div>

                </div><!-- /col-lg-9 -->
            </div><!-- /row -->
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        let category = [];
        let rating = [];
        let availability = [];
        let tags = [];

        $(document).ready(function() {
            loadProducts();

            $("#clearAllFilters").click(function() {
                loadProducts();
            });

            $('#filterSidebarCol').on('change', 'input[type="checkbox"], input[type="radio"]', function(e) {
                e.preventDefault();
            });
        });

        function loadProducts() {
            let currentHtml = $("#productsGrid").html();
            url = $(this).attr('href') == null ? "{{ route('shopping.show_all_products') }}" : $(this).attr("href");
            $.ajax({
                url: url,
                type: 'GET',
                data:{
                    category, rating, availability, tags
                },
                beforeSend: function() {
                    console.clear();
                    console.log(url);
                    $("#productsGrid").html("<span class='fs-5 text-info'>Loading Products..</span>");
                },
                success: function(response) {
                    $("#productsGrid").html("");
                    if (response.status == 1 || response.status == '') {
                        $('#productsGrid').html("<div class='col-sm-12'>" + response.products_view + "</div>");
                    }
                },
                error: function(xhr) {
                    //alert("Failed To Load Products");
                    $("#productsGrid").html(currentHtml);
                }
            });
        }
    </script>
@endpush
