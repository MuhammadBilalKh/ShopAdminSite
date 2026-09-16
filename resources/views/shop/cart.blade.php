@extends('layout.shop.main')

@section('title', 'Your Cart')

@section('content')

    @include('layout.shop.breadcrumbs', [
        'route' => route('shopping.view_cart'),
        'moduleName' => 'Cart',
        'subModuleName' => 'Checkout'
    ])

    <section class="py-5">
        <div class="container">
            <h2 class="section-title mb-4">Shopping Cart <span class="text-muted fw-400 fs-5"
                    id="cartItemCount">({{ count($cartItems) }}
                    items)</span></h2>

            @if (count($cartItems) == 0)
                <div id="emptyCart" class="empty-state">
                    <div class="empty-icon"><i class="ri-shopping-cart-line"></i></div>
                    <h5>Your cart is empty</h5>
                    <p>Looks like you haven't added anything yet.</p>
                    <a href="/" class="btn-primary-custom">
                        <i class="ri-store-line"></i> Continue Shopping
                    </a>
                </div>
            @else
                <div id="cartContent" class="row g-4">
                    <!-- Cart Items -->
                    <div class="col-lg-8">
                        <div class="admin-table-card">
                            <div class="table-header">
                                <h6>Cart Items</h6>
                                <button class="btn btn-sm btn-delete-all btn-outline-danger rounded-pill" id="clearCartBtn">
                                    <i class="ri-delete-bin-line me-1"></i>Clear All
                                </button>
                            </div>
                            <div class="admin-table-wrap">
                                <table class="table cart-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th style="min-width:280px">Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartTableBody">
                                        @foreach ($cartItems as $key => $value)
                                            @include('shop.cart_items', ['value' => $value])
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <a href="/" class="btn btn-outline-secondary btn-sm rounded-pill">
                                    <i class="ri-arrow-left-line me-1"></i>Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4" id="colOrderSummary">
                        @include('shop.order_summary', ['data' => $cartItems])
                    </div>
                </div>

                {{-- <div class="mt-5" id="recommendSection">
                    <h2 class="section-title mb-4">You May Also Like</h2>
                    <div class="row g-3" id="recommendGrid">
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card" data-id="p_4">
                                <div class="card-img-wrap">
                                    <span class="badge-discount">-15%</span>
                                    <img src="https://images.unsplash.com/photo-1495121553079-4c61bcce1894?w=400&amp;h=400&amp;fit=crop"
                                        alt="4K Ultra HD Action Camera" loading="lazy">
                                    <div class="quick-view-overlay">
                                        <a href="product-detail.html?id=p_4" class="btn">Quick View</a>
                                    </div>
                                    <button class="wishlist-btn " data-pid="p_4" title="Add to Wishlist">
                                        <i class="ri-heart-line"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="product-category">Electronics</div>
                                    <div class="product-name">4K Ultra HD Action Camera</div>
                                    <div class="mb-1"><span class="rating-stars"><i class="ri-star-fill"></i><i
                                                class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                                class="ri-star-fill"></i><i class="ri-star-fill"></i></span> <small
                                            class="text-muted">(4.7)</small></div>
                                    <div class="price-wrap">
                                        <span class="price-current">$169.99</span>
                                        <span class="price-old">$199.99</span>
                                    </div>
                                    <button class="btn-add-cart" data-pid="p_4">
                                        <i class="ri-shopping-cart-add-line me-1"></i>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card" data-id="p_5">
                                <div class="card-img-wrap">
                                    <span class="badge-discount">-23%</span>
                                    <img src="https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=400&amp;h=400&amp;fit=crop"
                                        alt="Mechanical Gaming Keyboard" loading="lazy">
                                    <div class="quick-view-overlay">
                                        <a href="product-detail.html?id=p_5" class="btn">Quick View</a>
                                    </div>
                                    <button class="wishlist-btn " data-pid="p_5" title="Add to Wishlist">
                                        <i class="ri-heart-line"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="product-category">Electronics</div>
                                    <div class="product-name">Mechanical Gaming Keyboard</div>
                                    <div class="mb-1"><span class="rating-stars"><i class="ri-star-fill"></i><i
                                                class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                                class="ri-star-fill"></i><i class="ri-star-fill"></i></span> <small
                                            class="text-muted">(4.6)</small></div>
                                    <div class="price-wrap">
                                        <span class="price-current">$99.99</span>
                                        <span class="price-old">$129.99</span>
                                    </div>
                                    <button class="btn-add-cart" data-pid="p_5">
                                        <i class="ri-shopping-cart-add-line me-1"></i>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card" data-id="p_6">
                                <div class="card-img-wrap">
                                    <span class="badge-discount">-20%</span>
                                    <img src="https://images.unsplash.com/photo-1625315714641-9e2a6d8e2e65?w=400&amp;h=400&amp;fit=crop"
                                        alt="USB-C Hub 7-in-1" loading="lazy">
                                    <div class="quick-view-overlay">
                                        <a href="product-detail.html?id=p_6" class="btn">Quick View</a>
                                    </div>
                                    <button class="wishlist-btn " data-pid="p_6" title="Add to Wishlist">
                                        <i class="ri-heart-line"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="product-category">Electronics</div>
                                    <div class="product-name">USB-C Hub 7-in-1</div>
                                    <div class="mb-1"><span class="rating-stars"><i class="ri-star-fill"></i><i
                                                class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                                class="ri-star-fill"></i><i class="ri-star-line"></i></span> <small
                                            class="text-muted">(4.4)</small></div>
                                    <div class="price-wrap">
                                        <span class="price-current">$39.99</span>
                                        <span class="price-old">$49.99</span>
                                    </div>
                                    <button class="btn-add-cart" data-pid="p_6">
                                        <i class="ri-shopping-cart-add-line me-1"></i>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card" data-id="p_7">
                                <div class="card-img-wrap">
                                    <span class="badge-discount">-25%</span>
                                    <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=400&amp;h=400&amp;fit=crop"
                                        alt="Men's Classic Oxford Shirt" loading="lazy">
                                    <div class="quick-view-overlay">
                                        <a href="product-detail.html?id=p_7" class="btn">Quick View</a>
                                    </div>
                                    <button class="wishlist-btn " data-pid="p_7" title="Add to Wishlist">
                                        <i class="ri-heart-line"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="product-category">Fashion</div>
                                    <div class="product-name">Men's Classic Oxford Shirt</div>
                                    <div class="mb-1"><span class="rating-stars"><i class="ri-star-fill"></i><i
                                                class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                                class="ri-star-fill"></i><i class="ri-star-line"></i></span> <small
                                            class="text-muted">(4.3)</small></div>
                                    <div class="price-wrap">
                                        <span class="price-current">$44.99</span>
                                        <span class="price-old">$59.99</span>
                                    </div>
                                    <button class="btn-add-cart" data-pid="p_7">
                                        <i class="ri-shopping-cart-add-line me-1"></i>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            @endif

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $(".btn-delete-all").on("click", function() {

                if (window.confirm("Are Your Sure You Want To Remove All Items From The Cart") == false) {
                    return false;
                }

                $.ajax({
                    url: "{{ route('customer.manage_customer_cart') }}",
                    type: "POST",
                    data: {
                        product_id: 0,
                        request_type: 1
                    },
                    beforeSend: function() {
                        showToast('Please Wait! Your Request Is Processing..');
                    },
                    success: function(resp) {
                        if (resp.status == 1) {
                            showToast(`<strong>${resp.message}</strong>`);

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000)
                        } else {
                            showToast(`<strong>${resp.message}</strong>`);
                        }
                    },
                    error: function() {
                        showToast(
                            `<strong>An Error Occured While Processing Your Request.</strong>`
                        );
                    }
                });
            });

            $(".remove-item").on("click", function(e) {

                if (window.confirm("Are Your Sure You Want To Remove This Item") == false) {
                    return false;
                }

                $.ajax({
                    url: "{{ route('customer.manage_customer_cart') }}",
                    type: "POST",
                    data: {
                        product_id: $(this).data("key"),
                        request_type: 2
                    },
                    beforeSend: function() {
                        showToast('Please Wait! Your Request Is Processing..');
                    },
                    success: function(resp) {
                        if (resp.status == 1) {
                            showToast(`<strong>${resp.message}</strong>`);

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000)
                        } else {
                            showToast(`<strong>${resp.message}</strong>`);
                        }
                    },
                    error: function() {
                        showToast(
                            `<strong>An Error Occured While Processing Your Request.</strong>`
                        );
                    }
                });
            });

            $(".qty-input").on("change", function() {
                $.ajax({
                    url: "{{ route('customer.manage_customer_cart') }}",
                    type: "POST",
                    data: {
                        product_id: $(this).data("key"),
                        request_type: 3,
                        product_quantity: $(this).val()
                    },
                    beforeSend: function() {
                        showToast('Please Wait! Your Request Is Processing..');
                    },
                    success: function(resp) {
                        if (resp.status == 1) {
                            showToast(`<strong>${resp.message}</strong>`);

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000)
                        } else {
                            showToast(`<strong>${resp.message}</strong>`);
                        }
                    },
                    error: function() {
                        showToast(
                            `<strong>An Error Occured While Processing Your Request.</strong>`
                        );
                    }
                });
            });

        });
    </script>
@endpush
