<!-- Footer Start -->
<div class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h1>E Shop</h1>
                    <p>
                    Welcome to E Shop,
                     where we provide quality products and dedicated customer service to meet all your needs.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="title">Useful Pages</h3>
                    <ul>
                        <li><a href="product.html">Product</a></li>
                        <li><a href="product-detail.html">Product Detail</a></li>
                        <li><a href="cart.html">Cart</a></li>
                        <li><a href="checkout.html">Checkout</a></li>
                        <li><a href="login.html">Login & Register</a></li>
                        <li><a href="my-account.html">My Account</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="title">Quick Links</h3>
                    <ul>
                        <li><a href="product.html">Product</a></li>
                        <li><a href="cart.html">Cart</a></li>
                        <li><a href="checkout.html">Checkout</a></li>
                        <li><a href="login.html">Login & Register</a></li>
                        <li><a href="my-account.html">My Account</a></li>
                        <li><a href="wishlist.html">Wishlist</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="title">Get in Touch</h3>
                    <div class="contact-info">
                        <p><i class="fa fa-map-marker"></i>123 E Shop, Los Angeles, CA, USA</p>
                        <p><i class="fa fa-envelope"></i>email@example.com</p>
                        <p><i class="fa fa-phone"></i>+123-456-7890</p>
                        <div class="social">
                            <a href=""><i class="fa fa-twitter"></i></a>
                            <a href=""><i class="fa fa-facebook"></i></a>
                            <a href=""><i class="fa fa-linkedin"></i></a>
                            <a href=""><i class="fa fa-instagram"></i></a>
                            <a href=""><i class="fa fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row payment">
            <div class="col-md-6">
                <div class="payment-method">
                    <p>We Accept:</p>
                    <img src="{{asset('theme/client/img/payment-method.png')}}" alt="Payment Method" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="payment-security">
                    <p>Secured By:</p>
                    <img src="{{asset('theme/client/img/godaddy.svg')}}" alt="Payment Security" />
                    <img src="{{asset('theme/client/img/norton.svg')}}" alt="Payment Security" />
                    <img src="{{asset('theme/client/img/ssl.svg')}}" alt="Payment Security" />
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->


<!-- Footer Bottom Start -->

<!-- Footer Bottom End -->


<!-- Back to Top -->
<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>


<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('theme/client/js/lib/easing/easing.min.js')}}"></script>
<script src="{{asset('theme/client/js/lib/slick/slick.min.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="{{asset('theme/client/js/main.js')}}"></script>
<script>
    $('.main-slider').slick({
        autoplay: true,
        dots: true,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: true,
        variableWidth: true
    });
</script>
<script>
    // Quantity
    // $('.qty button').on('click', function () {
    //     var $button = $(this);
    //     var oldValue = $button.parent().find('input').val();
    //     if ($button.hasClass('btn-plus')) {
    //         var newVal = parseFloat(oldValue) + 1;
    //     } else {
    //         if (oldValue > 0) {
    //             var newVal = parseFloat(oldValue) - 1;
    //         } else {
    //             newVal = 0;
    //         }
    //     }
    //     $button.parent().find('input').val(newVal);
    // });
</script>
<!-- Template Javascript -->

</body>

</html>