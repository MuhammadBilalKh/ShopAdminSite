<tr data-key="{{ $value->getCartProduct->unique_product_id }}">
    <td>
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('product_profile_image/' . $value->getCartProduct->product_profile_image) }}"
                class="cart-item-img" alt="{{ $value->getCartProduct->product_profile_image }}">
            <div>
                <div class="cart-item-name">
                    <a href="{{ route('shopping.product_detail', ['_id' => $value->getCartProduct->unique_product_id]) }}"
                        class="text-dark">
                        {{ $value->getCartProduct->product_name }}</a>
                </div>

            </div>
        </div>
    </td>
    <td class="fw-700">Rs.
        {{ number_format($value->price) }}
    </td>
    <td>
        <div class="qty-control">
            {{-- <button class="qty-dec" data-key="{{ $value->getCartProduct->unique_product_id }}"><i class="ri-subtract-line"></i></button> --}}
            <input type="number" class="qty-input" value="{{ $value->quantity }}" min="1" max="99"
                data-key="{{ $value->getCartProduct->unique_product_id }}">
            {{-- <button class="qtyinc" data-key="{{ $value->getCartProduct->unique_product_id }}"><i class="ri-add-line"></i></button> --}}
        </div>
    </td>
    <td class="fw-700 text-primary">Rs. {{ number_format($value->quantity * $value->price) }}</td>
    <td>
        <button class="btn-action delete remove-item" data-key="{{ $value->getCartProduct->unique_product_id }}"
            title="Remove">
            <i class="ri-delete-bin-line"></i>
        </button>
    </td>
</tr>
