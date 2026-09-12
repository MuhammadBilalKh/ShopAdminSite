@extends('layout.shop.main')

@section('title', $productData->product_name ?? 'Product Not Found')

@section('content')
    <div class="breadcrumb-wrap">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.html?cat=Electronics" id="breadCat">Electronics</a></li>
                    <li class="breadcrumb-item active" id="breadName">{{ $productData?->product_name ?? 'Loading…' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    @if (empty($productData))
        <section class="py-5">
            <div class="container">
                <div id="productNotFound" class="empty-state d-block">
                    <div class="empty-icon"><i class="ri-error-warning-line"></i></div>
                    <h5>Product not found</h5>
                    <p>The product you're looking for doesn't exist.</p>
                    <a href="/" class="btn-primary-custom"><i class="ri-arrow-left-line"></i>Back To Shop</a>
                </div>
            </div>
        </section>
    @else
        <section class="py-5">
            <div class="container">
                <div id="productNotFound" class="empty-state d-none">
                    <div class="empty-icon"><i class="ri-error-warning-line"></i></div>
                    <h5>Product not found</h5>
                    <p>The product you're looking for doesn't exist.</p>
                </div>

                <div id="productDetail" class="row g-5">

                    <div class="col-lg-6">
                        <div class="product-detail-gallery">
                            <div class="main-img">
                                <img id="mainImage"
                                    src="{{ asset('product_profile_image/' . $productData->product_profile_image) }}"
                                    alt="{{ $productData->product_name }}">
                            </div>
                            <div class="thumb-strip" id="thumbStrip"></div>
                        </div>
                    </div>

                    <div class="col-lg-6 product-detail-info">
                        <div class="product-category mb-1" id="detailCategory">
                            {{ $productData->getProductCategory->category_name }}</div>
                        <h1 class="product-title" id="detailName">{{ $productData->product_name }}</h1>

                        <div class="d-flex align-items-center gap-3 mt-2 mb-3">
                            <div id="detailStars"><span class="rating-stars"><i class="ri-star-fill"></i><i
                                        class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                        class="ri-star-fill"></i></span> <small class="text-muted">(4.8)</small></div>
                            <span class="text-muted small" id="detailReviews">(2 reviews)</span>
                            <span class="ms-auto" id="detailStock"><span
                                    class="badge-status">{!! $productData->getStyledProductQuantityLabel($productData->quantity) !!}</span></span>
                        </div>

                        <div class="price-wrap mb-3">
                            @if (isset($productData->sales_price) && $productData->sales_price > 0)
                                <span class="price-current fs-3" id="detailPrice">Rs. {{ $productData->sales_price }}</span>
                                <span class="price-old" id="detailOldPrice">Rs. {{ $productData->regular_price }}</span>
                                <span class="tag-chip ms-2"
                                    id="detailDiscount">{{ number_format($productData->getSalePercentage($productData->regular_price, $productData->sales_price), 2) }}%</span>
                            @else
                                <span class="price-current fs-3" id="detailPrice">{{ $productData->regular_price }}</span>
                            @endif
                        </div>

                        <p class="text-muted" id="detailDescription" style="font-size:.95rem;line-height:1.7">
                            {{ $productData->description }}</p>

                        <!-- Color Selector -->
                        <div id="colorWrap" class="mb-3 d-none">
                            <div class="fw-700 small mb-2">Color: <span id="colorLabel"
                                    class="fw-400 text-muted ms-1"></span>
                            </div>
                            <div class="color-selector" id="colorSelector"></div>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-4">
                            <div class="fw-700 small mb-2">Quantity:</div>
                            <div class="qty-control">
                                <button id="qtyMinus"><i class="ri-subtract-line"></i></button>
                                <input type="number" id="qtyInput" value="1" min="1" max="99">
                                <button id="qtyPlus"><i class="ri-add-line"></i></button>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="d-flex gap-3 flex-wrap mb-4">
                            <button class="btn-primary-custom flex-fill" id="addToCartBtn">
                                <i class="ri-shopping-cart-add-line"></i> Add to Cart
                            </button>
                            @if (Auth::guard('customer')->user())
                                <button class="btn-outline-custom" id="wishlistBtn" style="padding:.65rem 1.2rem"><i
                                        class="ri-heart-line"></i></button>
                            @endif
                        </div>

                        <button class="btn btn-success w-100 py-3 fw-700 rounded-3" id="buyNowBtn" style="font-size:1rem">
                            <i class="ri-lightning-line me-2"></i>Buy Now
                        </button>

                        <div class="divider"></div>
                        <div class="d-flex flex-column gap-2" style="font-size:.85rem;color:#666">
                            <div><i class="ri-refresh-line me-2 text-primary"></i><strong>30-Day</strong> hassle-free
                                returns
                            </div>
                            <div><i class="ri-shield-check-line me-2 text-primary"></i><strong>2-Year</strong> warranty
                                included</div>
                            <div id="detailTags" class="mt-1">
                                <div class="fw-700 small mb-1">Tags:</div>

                                @foreach ($productData->tags as $key => $value)
                                    <span class="tag-chip"> {{ $value->getTags[0]->tag_name }} </span>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Tabs: Description / Reviews ── -->
                <div class="mt-5" id="tabsSection">
                    <ul class="nav nav-tabs" id="detailTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-700" data-bs-toggle="tab" data-bs-target="#tabDesc"
                                aria-selected="true" role="tab">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-700" data-bs-toggle="tab" data-bs-target="#tabReviews"
                                aria-selected="false" tabindex="-1" role="tab">Reviews <span id="reviewCount"
                                    class="badge bg-primary ms-1">2</span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-700" data-bs-toggle="tab" data-bs-target="#tabShipping"
                                aria-selected="false" tabindex="-1" role="tab">Shipping</button>
                        </li>
                    </ul>
                    <div class="tab-content bg-white rounded-bottom shadow-sm p-4">
                        <div class="tab-pane fade show active" id="tabDesc" role="tabpanel">
                            <p id="tabDescText" class="mb-0" style="line-height:1.8">{{ $productData->description }}
                            </p>
                        </div>
                        <div class="tab-pane fade" id="tabReviews" role="tabpanel">
                            <div id="reviewsList">
                                <div class="p-3 mb-2 bg-light rounded-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="fw-700">Alice M. <span><i class="ri-star-fill"
                                                    style="color:#f59e0b"></i><i class="ri-star-fill"
                                                    style="color:#f59e0b"></i><i class="ri-star-fill"
                                                    style="color:#f59e0b"></i><i class="ri-star-fill"
                                                    style="color:#f59e0b"></i><i class="ri-star-fill"
                                                    style="color:#f59e0b"></i></span></div>
                                        <small class="text-muted">Sep 7, 2026</small>
                                    </div>
                                    <p class="mb-0 small">Absolutely love this product! Exceeded my expectations.</p>
                                </div>
                                <div class="p-3 mb-2 bg-light rounded-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="fw-700">Bob K. <span><i class="ri-star-fill"
                                                    style="color:#f59e0b"></i><i class="ri-star-fill"
                                                    style="color:#f59e0b"></i><i class="ri-star-fill"
                                                    style="color:#f59e0b"></i><i class="ri-star-fill"
                                                    style="color:#f59e0b"></i><i class="ri-star-line"
                                                    style="color:#f59e0b"></i></span></div>
                                        <small class="text-muted">Aug 31, 2026</small>
                                    </div>
                                    <p class="mb-0 small">Great quality, fast shipping. Would buy again.</p>
                                </div>
                            </div>
                            <!-- Add Review Form -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="fw-700 mb-3">Write a Review</h6>
                                <form id="reviewForm">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Your Name</label>
                                            <input type="text" class="form-control" id="reviewName" required=""
                                                placeholder="John Doe">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Rating</label>
                                            <select class="form-select" id="reviewRating">
                                                <option value="5">★★★★★ Excellent</option>
                                                <option value="4">★★★★☆ Good</option>
                                                <option value="3">★★★☆☆ Average</option>
                                                <option value="2">★★☆☆☆ Poor</option>
                                                <option value="1">★☆☆☆☆ Terrible</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Your Review</label>
                                            <textarea class="form-control" id="reviewText" rows="3" required="" placeholder="Share your experience…"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn-primary-custom">
                                                <i class="ri-send-plane-line"></i> Submit Review
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tabShipping" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex gap-3 p-3 bg-light rounded-3">
                                        <i class="ri-truck-line fs-3 text-primary"></i>
                                        <div>
                                            <div class="fw-700">Standard Shipping</div>
                                            <div class="text-muted small">5–7 business days · Free over $50, otherwise
                                                $9.99
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-3 p-3 bg-light rounded-3">
                                        <i class="ri-flashlight-line fs-3 text-warning"></i>
                                        <div>
                                            <div class="fw-700">Express Shipping</div>
                                            <div class="text-muted small">1–2 business days · $19.99</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <p class="text-muted small mb-0">Orders placed before 2PM EST on weekdays ship same
                                        day. We
                                        ship to all 50 US states and 25+ countries worldwide.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5" id="relatedSection">
                    <h2 class="section-title mb-4">Related Products</h2>
                    <div class="row g-3" id="relatedProducts">
                        @forelse ($relatedProds as $key => $productData)
                            <div class="col-6 col-md-4 col-lg-3">
                                @include('shop.show_single_product', compact('productData'))
                            </div>
                        @empty
                            <div class="col-6 col-md-4 col-lg-3">
                                <h5 class="text-center text-danger">No Related Products Found..</h5>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection
