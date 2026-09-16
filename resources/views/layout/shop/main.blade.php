<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ APPLICATION_NAME }} - @yield('title')</title>
    <link rel="icon" href="https://img.icons8.com/fluency/48/shopping-bag.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>

<body>

    @include('layout.shop.header')

    @yield('content')

    @include('layout.shop.footer')

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/datatable.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        @if (Auth::guard('customer')->user())

            $.ajax({
                url: "{{ route('shopping.get_wishlist_and_cart_count') }}",
                type: "GET",
                success: function(resp) {
                    $("#customerWishlist").text(resp.wishlistCount);
                    $("#customerCart").text(resp.cartCount);
                }
            });

            $(document).on("click", '.btn-add-cart', function(e) {

                let prodID = $(this).data("pid");

                let baseRoute =
                    "{{ route('customer.add_to_cart', ['productID' => 'PLACEHOLDER_ID']) }}";
                let addToCartUrl = baseRoute.replace('PLACEHOLDER_ID', prodID);

                $.ajax({
                    url: addToCartUrl,
                    type: "POST",
                    beforeSend: function() {
                        console.log("beforeSend")
                        $(this).text("Adding...");
                        $(this).attr("disabled", "disabled");
                    },
                    success: function(resp) {
                        if (resp.status == 1) {
                            showToast(`<strong>${resp.productName}</strong> added to cart!`,
                                'success');
                            $("#customerCart").text(resp.totalCartItems);
                        }

                        $(this).text("Add To Cart");
                        $(this).removeAttr("disabled");
                    }
                });
            });
        @endif
    });
</script>

@stack('scripts')

</html>
