@php
    $total = 0;
    $customerData = Auth::guard('customer')->user();

    if (Auth::guard('customer')->user()->getCartItems->count() == 0) {
        redirect()->route('shopping.view_cart');
    }
@endphp

@extends('layout.shop.main')

@section('title', 'Order(s) Checkout')

@section('content')

    @include('layout.shop.breadcrumbs', [
        'route' => route('shopping.checkout'),
        'moduleName' => 'Checkout',
        'subModuleName' => 'Order Placement',
    ])

    <section class="py-5">
        <div class="container">

            @if (session()->has('error'))

                <div class="alert alert-danger">

                    <strong>{{ session('error') }}</strong>

                    @if (session()->has('zeroQuantity') && is_array(session('zeroQuantity')))

                        <ul class="mb-0 mt-2">

                            @foreach (session('zeroQuantity') as $key => $value)
                                <li>
                                    {{ $value }} ({{ $key }})
                                </li>
                            @endforeach

                        </ul>

                    @endif

                </div>

            @endif

            <h2 class="section-title mb-4">Checkout</h2>

            <div id="emptyCartGuard" class="empty-state d-none">
                <div class="empty-icon">
                    <i class="ri-shopping-cart-line"></i>
                </div>
                <h5>Your cart is empty</h5>
                <a href="index.html" class="btn-primary-custom mt-2">
                    <i class="ri-store-line"></i> Shop Now
                </a>
            </div>

            <div id="checkoutBody" class="row g-4">

                <div class="col-lg-7">

                    <div id="stepShipping">
                        <div class="bg-white rounded-3 shadow-sm p-4">

                            <h5 class="fw-800 mb-4">
                                <i class="ri-map-pin-line me-2 text-primary"></i>
                                Shipping Information
                            </h5>

                            <form id="shippingForm" method="POST" action="{{ route('shopping.submit_checkout') }}">
                                @csrf

                                <div class="row g-3">

                                    <div class="col-md-12">
                                        <label class="form-label">
                                            Customer Name:
                                        </label>
                                        <input type="text" class="form-control" id="firstName"
                                            value="{{ $customerData->full_name }}" readonly>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Email Address
                                        </label>
                                        <input type="email" class="form-control" id="emailAddr" readonly
                                            value="{{ $customerData->email_address }}" />
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Phone Number
                                        </label>
                                        <input type="tel" class="form-control" id="phoneNo" readonly
                                            value="{{ $customerData->mobile_number }}" />
                                    </div>

                                    <!-- Street Address -->
                                    <div class="col-12">
                                        <label class="form-label">
                                            Street Address *
                                        </label>
                                        <input type="text" class="form-control" id="streetAddr" readonly
                                            value="{{ $customerData->address }}" />
                                    </div>

                                    <!-- City -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            City *
                                        </label>
                                        <input type="text" class="form-control" id="city" readonly
                                            value="{{ $customerData->getCustomerCity->first()->city_name }}">
                                    </div>

                                    <!-- ZIP Code -->
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            ZIP Code *
                                        </label>
                                        <input type="text" class="form-control" name="zip_code"
                                            value="{{ old('zip_code') }}" />
                                        @error('zip_code')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">
                                            Shipping Address
                                        </label>
                                        <textarea name="shipping_address" id="txtShippingAddress" style="resize: none;" rows="7" class="form-control">{{ old('shipping_address') }}</textarea>
                                        @error('shipping_address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Shipping Method -->
                                    <div class="col-12">
                                        <label class="form-label">
                                            Shipping Method *
                                        </label>

                                        <div class="d-flex flex-column gap-2">

                                            <label
                                                class="d-flex align-items-center gap-3 p-3 border rounded-3 shipping-option active"
                                                style="cursor:pointer">

                                                <input type="radio" name="shipping" value="standard" checked
                                                    class="form-check-input mt-0">

                                                <div class="flex-fill">
                                                    <div class="fw-700 small">
                                                        Standard Shipping
                                                    </div>
                                                    <div class="text-muted" style="font-size:.78rem">
                                                        5–7 business days
                                                    </div>
                                                </div>

                                                <span class="fw-700" id="standardShipCost">
                                                    <span class="text-success">
                                                        Free
                                                    </span>
                                                </span>

                                            </label>

                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">
                                            Order Notes (optional)
                                        </label>

                                        <textarea class="form-control" id="orderNotes" name="optional_notes" rows="2" placeholder="Special instructions for delivery…"></textarea>
                                    </div>

                                    <div class="col-12">

                                        <h5 class="fw-800 mt-2 mb-3">
                                            <i class="ri-bank-card-line me-2 text-primary"></i>
                                            Payment Method
                                        </h5>

                                        <label
                                            class="d-flex align-items-center gap-3 p-3 border rounded-3 payment-option active"
                                            style="cursor:pointer">

                                            <input type="radio" name="payment" value="cod" checked
                                                class="form-check-input mt-0">

                                            <i class="ri-money-dollar-circle-line fs-4 text-success"></i>

                                            <div class="flex-fill">
                                                <div class="fw-700">
                                                    Cash on Delivery
                                                </div>
                                                <div class="text-muted small">
                                                    Pay cash when your order is delivered.
                                                </div>
                                            </div>

                                        </label>

                                        <div class="alert alert-success py-2 small mt-3 mb-0">
                                            <i class="ri-check-line me-1"></i>
                                            Pay cash when your order is delivered.
                                            No extra charges.
                                        </div>

                                    </div>

                                </div>

                                <button type="submit" class="btn-primary-custom mt-4 w-100 justify-content-center">

                                    Place Your Order
                                    <i class="ri-arrow-right-line ms-2"></i>

                                </button>

                            </form>

                        </div>
                    </div>

                    <div id="stepReview" class="d-none">

                        <div class="bg-white rounded-3 shadow-sm p-4">

                            <h5 class="fw-800 mb-4">
                                <i class="ri-file-list-3-line me-2 text-primary"></i>
                                Review Your Order
                            </h5>

                            <div id="reviewShipping" class="mb-4">
                                <!-- Existing review data can be populated by JavaScript -->
                            </div>

                            <div id="reviewItems" class="mb-4">
                                <h6 class="fw-700 mb-2">Items:</h6>
                            </div>

                            <div class="d-flex gap-3">

                                <button class="btn btn-outline-secondary flex-fill" id="backToShipping">

                                    <i class="ri-arrow-left-line me-1"></i>
                                    Back

                                </button>

                                <button class="btn-primary-custom flex-fill justify-content-center" id="placeOrderBtn">

                                    <i class="ri-shield-check-line me-2"></i>
                                    Place Order

                                </button>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- RIGHT SIDE: ORDER SUMMARY -->
                <div class="col-lg-5">

                    <div class="order-summary-card sticky-top" style="top:80px">

                        <h6 class="fw-800 mb-3">
                            Your Order
                        </h6>

                        <div id="checkoutItems" class="mb-3">

                            @foreach ($placeItems as $key => $value)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div style="position:relative">
                                        <img src="{{ asset('product_profile_image/' . $value->getCartProduct->product_profile_image) }}"
                                            style="width:50px;height:50px;object-fit:cover;border-radius:8px"
                                            alt="{{ $value->getCartProduct->product_name }}">
                                        <span class="badge bg-secondary"
                                            style="position:absolute;top:-6px;right:-6px;font-size:.65rem">{{ $value->quantity }}</span>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="small fw-700" style="line-height:1.3">
                                            {{ $value->getCartProduct->product_name }}</div>

                                    </div>
                                    <div class="fw-700 small">Rs. {{ number_format($value->price * $value->quantity) }}
                                    </div>
                                </div>

                                @php
                                    $total += $value->price * $value->quantity;
                                @endphp
                            @endforeach

                        </div>

                        <div class="divider"></div>

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="ckSubtotal">Rs. {{ number_format($total) }}</span>
                        </div>

                        <div class="summary-row">
                            <span>Shipping</span>
                            <span id="ckShipping">
                                <span class="text-success">Rs. 100</span>
                            </span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-total">Total</span>
                            <span class="summary-total" id="ckTotal">
                                Rs. {{ number_format($total + 100) }}
                            </span>
                        </div>

                        <div class="mt-3 d-flex gap-2 align-items-center justify-content-center text-muted small">
                            <i class="ri-lock-line text-primary"></i>
                            Secure Checkout
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('shippingForm').addEventListener('submit', function(event) {

            event.preventDefault();

            const confirmed = confirm(
                'Are you sure you want to place this order?'
            );

            if (confirmed) {
                this.submit();
            }

        });
    </script>
@endpush
