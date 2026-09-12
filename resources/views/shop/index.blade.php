@extends('layout.shop.main')

@section('title', 'Online Shopping Platform')

@section('content')

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12" data-aos="fade-right">
                    <h1 class="hero-title">Shop Smarter,<br>Live <span style="color:var(--secondary)">Better</span></h1>
                    <p class="hero-subtitle mt-3">Discover thousands of products at unbeatable prices. From electronics
                        to fashion — all in one place.</p>
                    <div class="d-flex gap-3 flex-wrap mt-4">
                        <a href="#products" class="btn-primary-custom">
                            <i class="ri-store-line"></i> Shop Now
                        </a>
                        <a href="#categories" class="btn-outline-custom"
                            style="color:#fff;border-color:rgba(255,255,255,.5)">
                            <i class="ri-grid-line"></i> Browse Categories
                        </a>
                    </div>
                    <div class="d-flex gap-4 mt-4 flex-wrap">
                        <div class="text-white">
                            <div class="fw-800 fs-4" id="heroProductCount">{{ number_format($totalProds) }}</div>
                            <div style="font-size:.8rem;opacity:.7">Products</div>
                        </div>
                        <div class="text-white">
                            <div class="fw-800 fs-4">{{ number_format($hpCustomers) }}</div>
                            <div style="font-size:.8rem;opacity:.7">Happy Customers</div>
                        </div>
                        @if (isset($avgRating) && $avgRating > 1.0)
                            <div class="text-white">
                                <div class="fw-800 fs-4">{{ $avgRating }}★</div>
                                <div style="font-size:.8rem;opacity:.7">Average Rating</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('shop.product_section', [
        'productsArr' => $featuredProducts,
        'sectionID' => 'featured',
        'productSectionName' => 'Featured Products',
        'have_pagination' => 0,
    ])

    {{-- <section class="py-5" id="featured">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <h2 class="section-title mb-0">Featured Products</h2>
                <a href="products.html" class="btn-outline-custom" style="font-size:.85rem;padding:.45rem 1.2rem">View
                    All</a>
            </div>
            <div class="row g-3" id="featuredProducts">
                @forelse ($featuredProducts as $key => $productData)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('shop.show_single_product', compact('productData'))
                    </div>
                @empty
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card" data-id="">
                            <div class="card-body">
                                <h4 class="text-warning">No Featured Products Found..</h4>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section> --}}

    <section class="py-4 mt-3 container-fluid" style="background:linear-gradient(135deg,#1a1a2e,#6c63ff)">
        <div class="container">
            <div class="row g-3 text-center text-white">
                <div class="col-6 col-md-4">
                    <i class="ri-shield-check-line fs-2 mb-2 d-block" style="color:#f59e0b"></i>
                    <div class="fw-700">Secure Payment</div>
                    <small style="opacity:.7">100% protected</small>
                </div>
                <div class="col-6 col-md-4">
                    <i class="ri-refresh-line fs-2 mb-2 d-block" style="color:var(--secondary)"></i>
                    <div class="fw-700">Easy Returns</div>
                    <small style="opacity:.7">30-day return policy</small>
                </div>
                <div class="col-6 col-md-4">
                    <i class="ri-customer-service-2-line fs-2 mb-2 d-block" style="color:#a78bfa"></i>
                    <div class="fw-700">24/7 Support</div>
                    <small style="opacity:.7">Always here for you</small>
                </div>
            </div>
        </div>
    </section>

    @include('shop.product_section', [
        'productsArr' => $allProds,
        'sectionID' => 'allProducts',
        'productSectionName' => 'All Products',
        'have_pagination' => 1,
    ])
@endsection
