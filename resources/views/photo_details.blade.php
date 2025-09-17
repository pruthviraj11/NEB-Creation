@extends('layouts.homeLayout')
@section('title', $pageTitle['page_name']." | ".'NEB Creation')
@push('styles')
<style>
.photoSwiper {
        width: 100%;
        padding: 40px 0;
        position: relative;
    }

    .photo-slide {
        display: flex;
        justify-content: center;
        align-items: stretch;
    }

    .photo-card {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        border-radius: 8px;
    }

    .photo-card img {
        width: 342px;
        display: block;
        transition: transform 0.3s ease;
    }

    .photo-card:hover img {
        transform: scale(1.1);
    }

    .price-tag {
        position: absolute;
        top: 10px;
        left: 10px;
        /* background-color: rgba(0, 0, 0, 0.7); */
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: bold;
    }

    .overlay-part {
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.3);
        pointer-events: none;
    }

    .overlay-top-left {
        top: 0;
        left: 0;
    }

    .overlay-bottom-right {
        bottom: 0;
        right: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        color: #fff;
        font-weight: bold;
        font-size: 16px;
    }

    /* Swiper arrows */
    .swiper-button-next,
    .swiper-button-prev {
        color: #000;
    }

    .swiper-pagination-bullet {
        background-color: #000;
        opacity: 0.7;
    }

    .swiper-pagination-bullet-active {
        background-color: #000;
        opacity: 1;
    }
</style>
@endpush

@section('content')

<div class="photo_details">
    <div class="container">
        <div class="row align-items-center">

         

<div class="col-md-6 p-0">
    <img src="{{Storage::url($photo->front_image)}}" alt="Product Image" class="img-fluid rounded shadow w-100 h-100 object-fit-cover">
</div>

            <!-- Right: Details -->
         <div class="col-md-6 ps-md-5">
                <h2 class="mb-3">{{$photo->title}} <span class="badge badge-dark bg-dark fs-6">{{$photo->category_title}}</span></h2>

                <p class="text-muted">{{ strip_tags(html_entity_decode($photo->short_description)) }}</p>

                <h4 class="text-dark mb-4">
                    @php
                        if($photo->discount_price !='')
                        {
                            $mainPrice = "$".$photo->discount_price;
                            $discountPrice = "$".$photo->price;
                            $cartPrice = $photo->discount_price;
                        }
                        else
                        {
                            $mainPrice = "$".$photo->price;
                            $discountPrice = '';
                            $cartPrice = $photo->price;
                        }
                    @endphp
                    
                    <span class="fw-bold text-dark">{{$mainPrice}}</span>
                    <del class="me-2 text-danger">{{$discountPrice}}</del>
                </h4>

              
                 <form action="{{ route('front-add-cart') }}" method="POST" class="d-flex flex-column flex-sm-row align-items-start gap-2">
                @csrf 
                <input type="hidden" name="photo_id" value="{{ $photo->id }}">
                <input type="hidden" name="cart_price" value="{{ $cartPrice }}">
                
               
                {{-- <button type="submit" name="action" value="buy_now" class="btn btn-dark btn-md w-sm-auto ms-0 me-2">
                    <i class="bi bi-bag"></i> Buy now
                </button> --}}

               
                <button type="submit" name="action" value="add_to_cart" class="btn btn-dark btn-md w-sm-auto ms-0">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </form>

            @php
    $currentUrl = urlencode(url()->current()); // Current page URL
    $title = urlencode($photo->title ?? '');   // Optional: add photo title if available
@endphp

                <div class="my-3">
                    <!-- <h5><i class="bi bi-globe"></i> Follow Us</h5> -->
                    <div class="social-links mt-3">
                        {{-- <a href="https://www.instagram.com/?url={{ $currentUrl }}" target="_blank" target="_blank"><i class="bi bi-instagram"></i></a> --}}
                        <a href="#" target="_blank" target="_blank"><i class="bi bi-instagram"></i></a>
                        <a href="#" target="_blank"><i class="bi bi-facebook"></i></a>
                        <a href="#" target="_blank"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <!-- <div class="mt-5">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Blanditiis corporis libero beatae cum exercitationem officia eligendi voluptates qui quia dignissimos, modi ab tempore esse sequi mollitia facere perspiciatis? Laborum veniam possimus quisquam provident tempore maxime ipsum molestias voluptas, commodi praesentium sed dolores ipsa quasi aliquam temporibus velit, magni corporis reiciendis? Minima placeat id doloremque dicta delectus voluptatibus atque dolores, qui quod culpa facere aspernatur porro molestias. Placeat commodi corrupti earum soluta neque ipsa deserunt nostrum ea eaque inventore, necessitatibus expedita fuga nulla nesciunt. Pariatur, excepturi ipsum, quam accusamus ducimus officiis expedita voluptas at libero magnam facilis earum odit quo nobis.
                </div> -->
            </div>

        </div>

    </div>
</div>

<hr class="container my-3">

<div class="container mb-3 text-justify text-muted long-des">

     {{strip_tags(html_entity_decode($photo->description))}}
    {{-- <p class=" ">
        <!-- For more details <a href="contact.php" class="text-decoration-none">contact us</a> -->
       
    </p> --}}
</div>


@if(!empty($features) && $features->count() > 0)
<div class="container">
    <h2 class="text-center fs-1 mb-4">Featured Photos</h2>

    <div class="swiper photoSwiper">
        <div class="swiper-wrapper">
           
            <!-- Slide 1 -->
            @foreach($features as $feature)
            <div class="swiper-slide photo-slide bg-transparent">
                <div class="">
                    <div class="photo-card">
                        <a href="{{route('front-photo_details',$feature->slug)}}" title={{$feature->title}}>
                            
                            @php
                            if($feature->discount_price != '')
                            {
                                $price = $feature->discount_price;
                            }
                            else
                            {
                                $price = $feature->price;
                            }
                        @endphp
                            
                            
                            <span class="price-tag">${{$price}}</span>
                            <img src="{{Storage::url($feature->front_image)}}" class="img-fluid" alt="Photo">
                            <div class="overlay-part overlay-top-left"></div>
                            <div class="overlay-part overlay-bottom-right">
                                <span>${{$price}}</span>
                                <i class="bi bi-cart-plus"></i>
                            </div>
                        </a>
                    </div>
                    <a href="{{route('front-photo_details',$feature->slug)}}" title={{$feature->title}} class="btn btn-dark btn-sm ms-0 mt-2">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </a>
                </div>
            </div>
            @endforeach

            

        </div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>

        <!-- Navigation arrows -->
        {{-- <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div> --}}
    </div>
</div>
@endif





@endsection

@push('scripts')
<script src="{{asset('home/js/swiper-bundle.min.js')}}"></script>


<script>
    var swiper = new Swiper(".photoSwiper", {
        slidesPerView: {{$features->count()}},
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 1
            },
            768: {
                slidesPerView: 2
            },
            992: {
                slidesPerView: 3
            },
        },
    });
</script>

@endpush
