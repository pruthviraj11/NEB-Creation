<!DOCTYPE HTML>
<html lang="en">

<!-- Mirrored from outdoor.kwst.net/site/# by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 10 Sep 2025 10:31:01 GMT -->

<head>
    <!--=============== basic  ===============-->
    <meta charset="UTF-8">
    <title>@yield('title', 'NEB Creations')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="robots" content="index, follow" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <!-- <link rel="stylesheet" href="{{asset('home/css/all.min.css" integrit')}}y="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <!--=============== css  ===============-->
    <!-- <link type="text/css" rel="stylesheet" href="css/reset.css"> -->
    <link type="text/css" rel="stylesheet" href="{{ asset('home/css/plugins.css')}}">
    <link type="text/css" rel="stylesheet" href="{{ asset('home/css/default_style.css')}}">
    <link type="text/css" rel="stylesheet" href="{{ asset('home/css/yourstyle.css')}}">
    <link rel="stylesheet" href="{{ asset('home/css/style.css')}}">


    <!-- Vendor CSS Files -->
    <link href="{{ asset('home/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('home/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{ asset('home/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{ asset('home/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
    <!--=============== favicons ===============-->
    <link rel="shortcut icon" href="{{ asset('home/images/favicon.png')}}">

    <!--=============== font ===============-->
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
    <link href="{{ asset('home/css/css2.css' )}}" rel="stylesheet">
@stack('styles')

</head>

<body>

     <div class="loader"><i class="bi bi-arrow-repeat spin"></i></div>
    <div class="page-wrapper">
        <div id="main">
            <header>
			
                <!-- Header inner  -->
                <div class="header-inner">
                    <!-- Logo  -->
                    <div class="logo-holder">
                        <a href="{{route('front-home')}}"><img src="{{ asset('home/images/neblogo.png')}}" alt=""></a>
                    </div>
                    <!--Logo end  -->
                    <!--Navigation  -->
                    <div class="nav-button-holder">
                        <div class="nav-button vis-m"><span></span><span></span><span></span></div>
                    </div>
                    {{-- <a href="#" class="show-share isShare"> --}}
                        <a href="#" class="show-share isShare" data-bs-toggle="offcanvas" data-bs-target="#cartSlider">
                        <img src="{{ asset('home/images/Vector.png')}}" alt="">
                    </a>
                    <div style="display: flex; justify-content: center;">
                        <div class="nav-holder me-0">
                            <nav>
                                  
                                <ul>
                                    <li><a href="{{route('front-home')}}" class="{{Request::is('/')? 'act-link' : ''}}">Home</a></li>
                                    <li><a href="{{route('front-about')}}" class="{{Request::is('about_us')? 'act-link' : ''}}">About us </a></li>
                                    <li><a href="{{route('front-photos')}}" class="{{Request::is('photos')? 'act-link' : ''}}">Photos</a></li>
                                    <li><a href="{{route('front-contact')}}" class="{{Request::is('contact_us')? 'act-link' : ''}}">Contact</a></li>
                                    
                                </ul>
                            </nav>
                        </div>
                    </div>

                </div>
            </header>



            <!-- Offcanvas Slider (Cart) -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="cartSlider">
                <div class="offcanvas-header">
                    <h4 class="offcanvas-title">Your Cart</h4>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">

                    {{-- <!-- Product Item -->
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="{{asset('home/images/featured-img-4.jpg')}}" class="img-fluid rounded-start" alt="Product">
                            </div>
                            <div class="col-8">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1"><strong>Forest</strong></h6>
                                    <p class="card-text  pt-2">$39.99</p>

                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <a href="cart.php" class="btn btn-dark btn-sm w-sm-auto ms-0">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </a> --}}


                </div>

            </div>

            

		

    @yield('content')

    <footer class="d-flex flex-wrap align-items-center justify-content-around">
    <div class="policy-box p-2">
        <span>© 2025 NEB Creations . All rights reserved.</span>

    </div>
    <div class="footer_page p-2">
        <ul class="d-flex gap-4 list-unstyled mb-0 ">
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
        </ul>
    </div>
    <!-- footer social -->
    <div class="footer-social p-2">
        <ul>
            <li><a href="#" target="_blank"><i class="bi bi-instagram"></i></a></li>
            <li><a href="#" target="_blank"><i class="bi bi-facebook"></i></a></li>
            <li><a href="#" target="_blank"><i class="bi bi-twitter-x"></i></a></li>
            <li><a href="#" target="_blank"><i class="bi bi-linkedin"></i></a></li>
        </ul>
    </div>
    <!-- <div class="to-top"><i class="fa fa-angle-up"></i></div> -->
</footer>   




<script type="text/javascript" src="{{ asset('home/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('home/js/plugins.js')}}"></script>
<script type="text/javascript" src="{{ asset('home/js/scripts.js')}}"></script>
<script type="text/javascript" src="{{ asset('home/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>


<script>
   
$(document).ready(function(){
    $.ajax({
        url: "/ajaxcartdetails",
        type: "GET",
        dataType: "json",
        success: function (response) {
            let cartBody = $(".offcanvas-body");
            cartBody.empty(); // clear previous data

            if(response.length > 0){
                response.forEach(function(item){
                    cartBody.append(`
                        <div class="card mb-3 cart-item" data-id="${item.id}">
                            <div class="row g-0">
                                <div class="col-4">
                                    <img src="/storage/${item.front_image}" class="img-fluid rounded-start" alt="${item.title}">
                                </div>
                                <div class="col-8">
                                    <div class="card-body p-2 d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title mb-1"><strong>${item.title}</strong></h6>
                                            <p class="card-text pt-2">$${item.amount}</p>
                                        </div>
                                        <a class="btn btn-sm btn-outline-danger delete-item" data-id="${item.id}">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });

                cartBody.append(`
                    <a href="/cart" class="btn btn-dark btn-sm w-sm-auto ms-0">
                        <i class="bi bi-cart-plus"></i> View Cart
                    </a>
                `);
            } else {
                cartBody.append(`<p class="text-center text-danger">Your cart is empty.</p>`);
            }
        },
        error: function (xhr, status, error) {
            console.log("Error:", error);
        },
    });

    // Handle delete click
   $(document).on("click", ".delete-item", function(){
    let itemId = $(this).data("id");

    $.ajax({
        url: "/deletecartitem/" + itemId,
        type: "GET", // you can change to DELETE/POST later
        success: function(response){
            if (response.status === "success") {
                // Remove item from DOM
                $(`.cart-item[data-id="${itemId}"]`).remove();

                // If no items left, show empty message
                if ($(".cart-item").length === 0) {
                    $(".offcanvas-body").html(`<p class="text-center">Your cart is empty.</p>`);
                }
            } else {
                console.log("Error:", response.message);
            }
        },
        error: function(xhr, status, error){
            console.log("Delete Error:", error);
        }
    });
});



});



</script>

@stack('scripts')
</body>

</html>
