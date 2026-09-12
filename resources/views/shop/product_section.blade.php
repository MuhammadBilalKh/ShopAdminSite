<section class="py-5" id="{{ $sectionID }}">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <h2 class="section-title mb-0">{{ $productSectionName }}</h2>
                <a href="products.html" class="btn-outline-custom" style="font-size:.85rem;padding:.45rem 1.2rem">View
                    All</a>
            </div>
            <div class="row g-3" id="featuredProducts">
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

        @if(isset($have_pagination) && $have_pagination == 1)
            {{ $productsArr->links() }}
        @endif
    </section>