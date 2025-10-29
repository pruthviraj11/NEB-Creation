@extends('layouts.homeLayout')
@section('title', $pageTitle['page_name'] . ' | ' . 'NEB Creation')

{{-- @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

@endpush --}}

{{-- @push('scripts')

@endpush --}}
@section('content')

    <div id="wrapper">
        <div class="content-holder   slid-hol pb-0">
            <div class="fixed-title"><span>Home</span></div>
            <div class="content full-height">
                <div class="full-height-wrap">
                    <div class="swiper-container" id="horizontal-slider" data-mwc="1" data-mwa="0">
                        <div class="swiper-wrapper">
                            <!--=============== 1 ===============-->
                            {{-- <div class="swiper-slide">
                            <div class="bg" style="background-image:url('{{asset('home/images/22.jpg')}}')"></div>
                            <div class="overlay"></div>
                            <div class="zoomimage"><img src="{{asset('home/images/22.jpg')}}" class="intense" alt=""><i class="bi bi-arrows-expand"></i></div>
                            <div class="slide-title-holder">
                                <div class="slide-title">
                                    <span class="subtitle">At posuere sem accumsan </span>
                                    <div class="separator-image"><img src="{{asset('home/images/separator.png')}}" alt=""></div>
                                    <h3 class="transition"> <a href="#">Blandit praesent</a></h3>
                                    <h4><a href="#">View</a></h4>
                                </div>
                            </div>
                        </div> --}}
                            <!--=============== 2 ===============-->
                            <div class="swiper-slide">
                                <div class="bg" style="background-image:url('{{ asset('home/images/slider1.png') }}')">
                                </div>
                                <div class="overlay"></div>
                                <div class="zoomimage"><img src="{{ asset('home/images/slider1.png') }}" class="intense"
                                        alt=""><i class="bi bi-arrows-expand"></i></div>
                                <div class="slide-title-holder">
                                    {{-- <div class="slide-title">
                                    <span class="subtitle">At posuere sem accumsan </span>
                                    <div class="separator-image"><img src="{{asset('home/images/separator.png')}}" alt=""></div>
                                    <h3 class="transition"> <a href="#">In tortor neque</a> </h3>
                                    <h4><a href="#">View</a></h4>
                                </div> --}}
                                </div>
                            </div>
                            <!--=============== 3 ===============-->
                            <div class="swiper-slide">
                                <div class="bg" style="background-image:url('{{ asset('home/images/slider2.png') }}')">
                                </div>
                                <div class="overlay"></div>
                                <div class="zoomimage"><img src="{{ asset('home/images/slider2.png') }}" class="intense"
                                        alt=""><i class="bi bi-arrows-expand"></i></div>
                                <div class="slide-title-holder">
                                    {{-- <div class="slide-title">
                                    <span class="subtitle">At posuere sem accumsan </span>
                                    <div class="separator-image"><img src="{{asset('home/images/separator.png')}}" alt=""></div>
                                    <h3 class="transition"> <a href="#">Vestibulum tincidunt</a> </h3>
                                    <h4><a href="#">View</a></h4>
                                </div> --}}
                                </div>
                            </div>
                            <!--=============== 4 ===============-->
                            <div class="swiper-slide">
                                <div class="bg" style="background-image:url('{{ asset('home/images/slider3.png') }}')">
                                </div>
                                <div class="overlay"></div>
                                <div class="zoomimage"><img src="{{ asset('home/images/slider3.png') }}" class="intense"
                                        alt=""><i class="bi bi-arrows-expand"></i></div>
                                <div class="slide-title-holder">
                                    {{-- <div class="slide-title">
                                    <span class="subtitle">At posuere sem accumsan </span>
                                    <div class="separator-image"><img src="{{asset('home/images/separator.png')}}" alt=""></div>
                                    <h3 class="transition"> <a href="#">Libero bibendum</a> </h3>
                                    <h4><a href="#">View</a></h4>
                                </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pagination text-center"></div>
                    <!-- slider navigation  -->
                    <div class="swiper-nav-holder hor hs">
                        <a class="swiper-nav arrow-left transition " href="#"><i class="bi bi-chevron-left"></i></a>
                        <a class="swiper-nav  arrow-right transition" href="#"><i class="bi bi-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- <div class="share-container  isShare" data-share="['facebook','pinterest','googleplus','twitter','linkedin']"></div> -->
        </div>
    </div>
    <!-- wrapper end -->





    <div class="left-decor"></div>
    <div class="right-decor"></div>


    <section class="featured-section feature_subtitle content p_90 pb-0 ">
        <h2 class="text-center heading">Featured Photos</h2>
        <p class="text-muted text-center">A selection of our best-selling photos.</p>

        <div class=" my-5">
            <div class="row g-5">

                @foreach ($photos as $photo)
                    @php
                        if ($photo->discount_price != '') {
                            $price = $photo->discount_price;
                            $cartPrice = $photo->discount_price;
                        } else {
                            $price = $photo->price;
                            $cartPrice = $photo->price;
                        }
                    @endphp


                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <form action="{{ route('front-add-cart') }}" method="POST"
                            class="d-flex flex-column flex-sm-row align-items-start gap-2">
                            @csrf
                            <input type="hidden" name="photo_id" value="{{ $photo->id }}">
                            <input type="hidden" name="cart_price" value="{{ $cartPrice }}">
                            <div class="photo-card w-100">
                                <span class="price-tag">${{ $price }}</span>

                                @php
                                    $imageUrl =
                                        isset($photo->front_image) &&
                                        Storage::disk('public')->exists($photo->front_image)
                                            ? Storage::url($photo->front_image)
                                            : asset('no_image/no_photo.png');
                                @endphp

                                <img src="{{ $imageUrl }}" class="img-fluid w-100" alt="{{ $photo->title }}">

                                <div class="overlay-part overlay-top-left d-none d-md-flex">
                                </div>

                                <div class="overlay-part overlay-bottom-right d-none d-md-flex">
                                    <div>
                                        <span>${{ $price }}</span>
                                        <div class="link-btns mt-3">

                                            <a href="{{ route('front-photo_details', $photo->slug) }}"
                                                class="btn btn-outline btn-dark m-0 btn-sm">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="link-btns d-flex align-items-center d-md-none mt-3 w-100">

                                <a href="{{ route('front-photo_details', $photo->slug) }}" class="btn btn-dark m-0 btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </div>
                        </form>
                    </div>
                @endforeach






            </div>
            <div>
                <a href="{{ route('front-photos') }}" class="btn btn-dark btn-md w-auto add-btn-cart mt-5">View More</a>
            </div>

        </div>
    </section>

    <div class=" my-5 p_90 mb-0">
        <div class="row g-4">
            <div class="col-md-7">
                <div class="about-box py-3">
                    <h2 class="mt-2">About Me</h2>
                    <p class="py-4 mb-0">
                        “I’ve always believed that when we leave this world, the only things that truly remain are the
                        memories we create with those we love and the impact we leave behind. That belief inspired my
                        passion for photography — to capture moments that tell stories, evoke emotion, and preserve memories
                        that can last a lifetime. Through my work, I strive to make every photo an everlasting reminder of
                        life’s most meaningful moments.”
                    </p>
                </div>
            </div>

            <div class="col-md-5">
                <div class="row custom-gap">
                    @foreach ($categories as $category)
                        <div class="col-6">
                            <a href="{{ route('front-photos', ['slug' => $category->slug]) }}"
                                class="category text-decoration-none">
                                {{ $category->category }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>


    <div class="testimonial-section position-relative"
        style="background: url('{{ asset('home/images/coustomer_say_bg.jpg') }}') no-repeat center/cover;">
        <div class="testimonial-overlay"></div>

        <div class="p_90 ">
            <div class="row justify-content-center g-4">
                <div class="col-md-5 testimonial-content ">
                    <h2 class="mb-3">What Our Customers Say</h2>
                    <p class="mb-5">Real feedback from real customers.</p>
                </div>

                <div class="col-md-7">
                    <!-- Swiper -->
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            @foreach ($testimonials as $testimonial)
                                <div class="swiper-slide">
                                    <div class="testimonial-card">
                                        <div class="testimonial-header">
                                            <div class="testimonial-user">
                                                {{-- <img src="{{ asset('uploads/testimonials/' . $testimonial->profile_pic) }}" --}}
                                                {{-- alt=""> --}}
                                                <img src="{{ $testimonial->profile_pic ? asset('uploads/testimonials/' . $testimonial->profile_pic) : 'https://i.pravatar.cc/100?u=' . $testimonial->id }}"
                                                    alt="{{ $testimonial->name }}">
                                                <div>
                                                    <strong>{{ $testimonial->name }}</strong><br>
                                                    <small class="text-muted">{{ $testimonial->designation }}</small>
                                                </div>
                                            </div>
                                            <div class="stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $testimonial->star)
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @else
                                                        <i class="bi bi-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="mt-3">“{{ $testimonial->message }}”</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- <div class="swiper-wrapper"> --}}

                        {{-- <div class="swiper-slide">
                                <div class="testimonial-card">
                                    <div class="testimonial-header">
                                        <div class="testimonial-user">
                                            <img src="https://i.pravatar.cc/100?img=12" alt="Mark Smith">
                                            <strong>Mark Smith</strong>
                                        </div>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                    </div>
                                    <p class="mt-3">Absolutely love the quality of the prints!</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-card ">
                                    <div class="testimonial-header">
                                        <div class="testimonial-user">
                                            <img src="https://i.pravatar.cc/100?img=12" alt="Mark Smith">
                                            <strong>Mark Smith</strong>
                                        </div>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                    </div>
                                    <p class="mt-3">Absolutely love the quality of the prints!</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-card">
                                    <div class="testimonial-header">
                                        <div class="testimonial-user">
                                            <img src="https://i.pravatar.cc/100?img=12" alt="Mark Smith">
                                            <strong>Mark Smith</strong>
                                        </div>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                    </div>
                                    <p class="mt-3">Absolutely love the quality of the prints!</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-card">
                                    <div class="testimonial-header">
                                        <div class="testimonial-user">
                                            <img src="https://i.pravatar.cc/100?img=12" alt="Mark Smith">
                                            <strong>Mark Smith</strong>
                                        </div>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                    </div>
                                    <p class="mt-3">Absolutely love the quality of the prints!</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-card">
                                    <div class="testimonial-header">
                                        <div class="testimonial-user">
                                            <img src="https://i.pravatar.cc/100?img=12" alt="Mark Smith">
                                            <strong>Mark Smith</strong>
                                        </div>
                                        <div class="stars">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                    </div>
                                    <p class="mt-3">Absolutely love the quality of the prints!</p>
                                </div>
                            </div> --}}
                        <!-- <div class="swiper-slide">Slide 6</div>
                                                            <div class="swiper-slide">Slide 7</div>
                                                            <div class="swiper-slide">Slide 8</div>
                                                            <div class="swiper-slide">Slide 9</div> -->
                    </div>
                    <!-- <div class="swiper-pagination"></div> -->
                </div>
            </div>
        </div>
    </div>


    </div>


@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 2,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                550: {
                    slidesPerView: 2,
                },
                1550: {
                    slidesPerView: 3,
                }
            }
        });
    </script>
@endpush
