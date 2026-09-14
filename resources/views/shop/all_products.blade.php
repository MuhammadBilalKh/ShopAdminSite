<section class="py-5" id="{{ $sectionID }}">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <h2 class="section-title mb-0">{{ $productSectionName }}</h2>
            @if (isset($showViewAll) && $showViewAll == 1)
                <a href="{{ route('shopping.products_lists') }}" class="btn-outline-custom" target="_blank"
                    style="font-size:.85rem;padding:.45rem 1.2rem">View
                    All</a>
            @endif
        </div>
        <div class="row g-3" id="allProducts">
            @forelse ($productsArr as $key => $productData)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('shop.show_single_product', compact('productData'))
                </div>
            @empty
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card" data-id="">
                        <div class="card-body">
                            <h4 class="text-warning">No {{ $productSectionName }} Found..</h4>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{ $productsArr->links() }}

</section>
