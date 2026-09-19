<div class="row g-3">
    <div class="col-md-6">
        <h6 class="fw-700 mb-2">Customer Info</h6>
        <div class="bg-light p-3 rounded-3 small">
            <div><strong>Name:</strong> {{ $orderData->getOrderBy->full_name }}</div>
            <div><strong>Email:</strong> {{ $orderData->getOrderBy->email_address }}</div>
            <div><strong>Phone:</strong> {{ $orderData->getOrderBy->mobile_number }}</div>
            <div><strong>Address:</strong> {{ $orderData->getOrderBy->address }}</div>
            <div><strong>Payment:</strong> Cash on Delivery</div>
        </div>
    </div>
    <div class="col-md-6">
        <h6 class="fw-700 mb-2">Order Timeline</h6>
        <div class="order-timeline">
            @forelse ($orderData->getOrderTimeLine as $key => $value)
                <div class="timeline-item done">
                    <div class="timeline-title">{{ $value->getStatusLabel($value->status) }}</div>
                    {{-- @dd($value) --}}
                    <div class="timeline-date">{{ $value->created_at->toDateTimeString() }} - {{ $value->getProcessBy->name }} ({{ $value->getProcessBy->login_id }})</div>
                </div>
            @empty
                <div class="timeline-item done">
                    <div class="timeline-title">No Order Timeline Found</div>
                </div>
            @endforelse
        </div>
    </div>
    <div class="col-12">
        <h6 class="fw-700 mb-2">Items</h6>
        @foreach ($orderData->getOrderLineItems as $key => $value)
            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                <img src="{{ asset('product_profile_image/' . $value->getLineItemProduct->product_profile_image) }}"
                    style="width:50px;height:50px;object-fit:cover;border-radius:8px">
                <div class="flex-fill">
                    <div class="fw-700 small">{{ $value->getLineItemProduct->product_name }}</div>
                    <div class="text-muted small">Qty: {{ $value->quantity }}</div>
                </div>
                <div class="fw-700">Rs. {{ $value->price }}</div>
            </div>
        @endforeach

        <div class="mt-3 bg-light p-3 rounded-3">
            <div class="d-flex justify-content-between small mb-1">
                <span>Subtotal</span><span>Rs. {{ number_format($orderData->total_amount) }}</span>
            </div>
            <div class="d-flex justify-content-between small mb-1">
                <span>Shipping</span><span>Rs. {{ $orderData->getShippingMethod->cost }}</span>
            </div>
            <div class="d-flex justify-content-between fw-800 border-top pt-2 mt-1">
                <span>Total</span><span class="text-primary">Rs.
                    {{ number_format($orderData->total_amount + $orderData->getShippingMethod->cost) }}</span>
            </div>
        </div>
    </div>
</div>
