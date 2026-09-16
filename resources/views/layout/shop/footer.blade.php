<div id="footerPlaceholder">
    <footer class="footer-main">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-2">{{ APPLICATION_NAME }}</div>
                    <p style="font-size:.88rem;color:rgba(255,255,255,.55);max-width:280px">Your one-stop destination for
                        quality products at unbeatable prices.</p>
                    <div class="social-links mt-3">
                        <a href="#"><i class="ri-facebook-fill"></i></a>
                        <a href="#"><i class="ri-twitter-fill"></i></a>
                        <a href="#"><i class="ri-instagram-line"></i></a>
                        <a href="#"><i class="ri-youtube-fill"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h6>Quick Links</h6>
                    <a href="index.html">Home</a>
                    <a href="products.html">All Products</a>
                    <a href="cart.html">Cart</a>
                    <a href="orders.html">My Orders</a>
                </div>
                <div class="col-lg-2 col-6">
                    <h6>Categories</h6>
                    @foreach ($category_names as $key => $value)
                        <a href="#">{{ $value->category_name }}</a>
                    @endforeach
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6>Newsletter</h6>
                    <p style="font-size:.82rem;color:rgba(255,255,255,.55)">Get deals &amp; updates straight to your
                        inbox.</p>
                    <div class="input-group mt-2">
                        <input type="email" class="form-control" placeholder="Your email…"
                            style="border-radius:8px 0 0 8px!important">
                        <button class="btn" style="background:var(--primary);color:#fff;border-radius:0 8px 8px 0"
                            onclick="showToast('Subscribed! 🎉','success')">Subscribe</button>
                    </div>
                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        <img src="https://img.shields.io/badge/Secure-Checkout-green?style=flat-square" alt="secure">
                        <img src="https://img.shields.io/badge/Free-Returns-blue?style=flat-square" alt="returns">
                        <img src="https://img.shields.io/badge/24%2F7-Support-purple?style=flat-square" alt="support">
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© 2026 ShopZone. All rights reserved.</span>
                <span>Made with ❤️ using Bootstrap 5</span>
            </div>
        </div>
    </footer>
    <button id="backToTop" title="Back to top" class="show"><i class="ri-arrow-up-line"></i></button>
</div>
