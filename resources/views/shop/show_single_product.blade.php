<div class="product-card" data-id="{{ $productData->unique_product_id }}">
    <div class="card-img-wrap">
        @if ($productData->is_new == 1)
            <span class="badge-new">NEW</span>
        @endif
        <img src="{{ asset('product_profile_image/' . $productData->product_profile_image) }}"
            alt="{{ $productData->product_name }}" loading="lazy">
        <div class="quick-view-overlay">
            <a href="{{ route('shopping.product_detail', ['_id' => $productData->unique_product_id]) }}" target="_blank"
                class="btn">Quick View</a>
        </div>

        @if (Auth::guard('customer')->user())
            <button class="wishlist-btn " data-pid="{{ $productData->unique_product_id }}" title="Add to Wishlist">
                <i class="ri-heart-line"></i>
            </button>
        @endif

    </div>
    <div class="card-body">
        <div class="product-category">{{ $productData->getProductCategory->category_name }}</div>
        <div class="product-name">{{ $productData->product_name }}</div>
        <div class="mb-1"><span class="rating-stars"><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                    class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i></span> <small
                class="text-muted">(5)</small></div>
        <div class="price-wrap">
            @if (isset($productData->sales_price) && $productData->sales_price > 0)
                <span class="price-current">Rs. {{ number_format($productData->sales_price) }}</span>
                <span class="price-old">Rs. {{ number_format($productData->regular_price) }}</span>
            @else
                <span class="price-current">Rs. {{ number_format($productData->regular_price) }}</span>
            @endif
        </div>
        <button class="btn-add-cart" data-pid="{{ $productData->unique_product_id }}">
            <i class="ri-shopping-cart-add-line me-1"></i>
            Add to Cart
        </button>
    </div>
</div>
