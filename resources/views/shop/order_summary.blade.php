@php
    $total = 0;
    $shippingFees = 100;

    foreach ($data as $key => $value) {
        $total += $value->price * $value->quantity;
    }
@endphp
<div class="order-summary-card">
    <h6 class="fw-800 mb-3">Order Summary</h6>
    <div class="summary-row">
        <span>Subtotal (<span id="summaryItemCount">{{ count($cartItems) }}</span> items)</span>
        <span id="summarySubtotal">Rs. {{ number_format($total) }}</span>
    </div>
    <div class="summary-row">
        <span><i class="ri-truck-line me-1 text-primary"></i>Shipping</span>
        <span id="summaryShipping" class="text-success"><span class="text-success">Rs.
                {{ number_format($shippingFees) }}</span></span>
    </div>
    <div class="summary-row">
        <span class="summary-total">Total</span>
        <span class="summary-total" id="summaryTotal">Rs. {{ number_format($total + $shippingFees) }}</span>
    </div>
    <a href="{{ route('shopping.checkout') }}" class="btn-primary-custom w-100 mt-3 d-flex justify-content-center" id="checkoutBtn">
        <i class="ri-shield-check-line me-2"></i>Proceed to Checkout
    </a>
    <div class="text-center mt-3">
        <small class="text-muted d-flex align-items-center justify-content-center gap-1">
            <i class="ri-lock-line"></i> Secure 256-bit SSL Encryption
        </small>
    </div>
    <!-- Payment Badges -->
    {{-- <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
        <img src="https://img.shields.io/badge/VISA-1A1F71?style=flat-square&amp;logo=visa&amp;logoColor=white"
            alt="Visa">
        <img src="https://img.shields.io/badge/Mastercard-EB001B?style=flat-square&amp;logo=mastercard&amp;logoColor=white"
            alt="Mastercard">
        <img src="https://img.shields.io/badge/PayPal-003087?style=flat-square&amp;logo=paypal&amp;logoColor=white"
            alt="PayPal">
    </div> --}}
</div>
