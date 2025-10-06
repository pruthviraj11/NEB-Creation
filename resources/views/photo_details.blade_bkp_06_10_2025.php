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

    /* .photo-card {
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
    } */

    /* .price-tag {
        position: absolute;
        top: 10px;
        left: 10px;
      
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
    } */

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
        @php
            $imageUrl =
                isset($photo->front_image) && Storage::disk('public')->exists($photo->front_image)
                    ? Storage::url($photo->front_image)
                    : asset('no_image/no_slider_photo.png');
        @endphp

    <img src="{{$imageUrl}}" alt="Product Image" class="img-fluid rounded shadow w-100 h-100 object-fit-cover">
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
                    {{-- <del class="me-2 text-danger">{{$discountPrice}}</del> --}}
                </h4>

              
                 <form action="{{ route('front-add-cart') }}" method="POST" class="d-flex flex-column flex-sm-row align-items-start gap-2">
                @csrf 
                <input type="hidden" name="photo_id" value="{{ $photo->id }}">
                <input type="hidden" name="cart_price" value="{{ $cartPrice }}">

                     <div class="row">
                        <div class="col-md-12">
                            <input type="text" name="total_price" class="total_price" value="{{$cartPrice}}" >
                        </div>
                     </div>
               
                
                 <div class="row">
                    <div class="col-md-12">
                        @foreach($creatives as $creative)
                        <div class="form-check mb-2">
                            <input 
                                class="form-check-input creative_art_checkbox" 
                                type="checkbox" 
                                name="creative_art[]" 
                                id="creative_art_{{ $creative->id }}" 
                                value="{{ $creative->price }}">
                            
                            <label class="form-check-label" for="creative_art_{{ $creative->id }}">
                                {{ $creative->title }} — ${{ $creative->price }}
                            </label>
                        </div>
                    @endforeach
                
                </div>

                <div class="col-md-12">
                    <p class="fw-bold mb-2">Bulk Purchase</p>

                        <!-- 5x7 Option -->
                        <div class="form-check d-flex align-items-center mb-2">
                            <input 
                                class="form-check-input bulk-check me-2" 
                                type="checkbox" 
                                id="bulk_5x7" 
                                value="2.00">
                            
                            <label class="form-check-label me-3" for="bulk_5x7">
                            5x7 — 20+ — $2.00 each
                            </label>

                            <input 
                                type="number" 
                                class="form-control bulk-qty" 
                                id="qty_5x7" 
                                min="20" 
                                max="2000" 
                                value="20"
                                style="width: 80px;" 
                                >
                        </div>

                            <!-- 4x6 Option -->
                            <div class="form-check d-flex align-items-center mb-2">
                                <input 
                                    class="form-check-input bulk-check me-2" 
                                    type="checkbox" 
                                    id="bulk_4x6" 
                                    value="0.50">
                                
                                <label class="form-check-label me-3" for="bulk_4x6">
                                    4x6 — 20+ — $0.50 each
                                </label>

                                <input 
                                    type="number" 
                                    class="form-control bulk-qty" 
                                    id="qty_4x6" 
                                    min="20" 
                                    max="2000" 
                                    value="20"
                                    style="width: 80px;" 
                                    >
                            </div>

 
                    </div>

                    <div class="col-md-12">
                         <p class="fw-bold mb-2">Canvas</p>

  <!-- 5x7 Option -->
                        <div class="form-check d-flex align-items-center mb-2">
                            <input 
                                class="form-check-input canvas_input me-2" 
                                type="checkbox" 
                                id="canvas" 
                                value="195">
                            
                            <label class="form-check-label me-3" for="canvas">
                                Buy 2 16x20's  $195 Get 1 Free
                            </label>

                            
                        </div>
                    </div>


               
               
</div>



                



                     

                {{-- <button type="submit" name="action" value="add_to_cart" class="btn btn-dark btn-md w-sm-auto ms-0">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button> --}}
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

<div class="container mb-5">
    <h3 class="mb-4 text-center text-primary">Gift Products</h3>
    <div class="row g-4">
        @foreach($giftProducts as $product)
        <div class="col-sm-6 col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="{{ Storage::url($product->product_image) }}" 
                     class="card-img-top img-fluid" 
                     alt="{{ $product->product_name }}" 
                     style="max-width: 100%; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->product_name }}</h5>

                    @if($product->product_varient)
                    <div class="mb-2">
                        <label for="varient_{{ $product->id }}" class="form-label small text-muted">Variant:</label>
                        <select id="varient_{{ $product->id }}" name="varient" class="form-select form-select-sm">
                            <option value="">Select Variant</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>
                    @endif

                    <p class="card-text mb-2 fw-bold">Price: ${{ number_format($product->product_price,2) }}</p>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input gift-checkbox" 
                               type="checkbox" 
                               value="{{ $product->product_price }}" 
                               id="gift_{{ $product->id }}">
                        <label class="form-check-label" for="gift_{{ $product->id }}">
                            Select
                        </label>
                    </div>

                    
                </div>
            </div>
        </div>
        @endforeach
    </div>

  
</div>




@if(!empty($features) && $features->count() > 0)
<div class="container">
    <h2 class="text-center fs-1 mb-4">Featured Photos</h2>

    <div class="swiper photoSwiper">
        <div class="swiper-wrapper">
           
            <!-- Slide 1 -->
            @foreach($features as $feature)
            @php
            if($feature->discount_price != '')
            {
                $fprice = $feature->discount_price;
                $fcartPrice = $feature->discount_price;
            }
            else
            {
                $fprice = $feature->price;
                $fcartPrice = $feature->price;
            }
            @endphp
            <div class="swiper-slide photo-slide bg-transparent">
                <div class="">

                    
                        <form action="{{ route('front-add-cart') }}" method="POST" class="d-flex flex-column flex-sm-row align-items-start gap-2">
                                @csrf 
                                <input type="hidden" name="photo_id" value="{{ $feature->id }}">
                                <input type="hidden" name="cart_price" value="{{ $fcartPrice }}">

                                <div class="photo-card w-100">
                                    <span class="price-tag">${{$fprice}}</span>

                                        @php
                                        $fimageUrl =
                                            isset($feature->front_image) && Storage::disk('public')->exists($feature->front_image)
                                                ? Storage::url($feature->front_image)
                                                : asset('no_image/no_photo.png');
                                        @endphp

                                    <img src="{{$fimageUrl}}" class="img-fluid w-100" alt="{{$feature->title}}">

                                    <div class="overlay-part overlay-top-left d-none d-md-flex">
                                    </div>

                                    <div class="overlay-part overlay-bottom-right d-none d-md-flex">
                                        <div>
                                            <span>${{$fprice}}</span>
                                            <div class="link-btns mt-3">
                                                <button type="submit" class="btn btn-outline btn-dark m-0 btn-sm">
                                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                                </button>
                                                <a href="{{route('front-photo_details',$feature->slug)}}" class="btn btn-outline btn-dark m-0 btn-sm">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="link-btns d-flex align-items-center d-md-none mt-3 w-100">
                                <button type="submit" class="btn btn-dark m-0 btn-sm">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                                <a href="{{route('front-photo_details',$feature->slug)}}" class="btn btn-dark m-0 btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </div>
                                                
                
                
                        </form>
                   



                    {{-- <div class="photo-card">
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
                    </a> --}}
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize base price properly
    let basePrice = parseFloat($(".total_price").val()) || 0;

    function updateTotal() {
        let total = basePrice;
        let anyChecked = false;

        // Add creative art prices if checked
        $('.creative_art_checkbox:checked').each(function() {
            total += parseFloat($(this).val()) || 0;
            anyChecked = true;
        });

        // Add bulk prices
        $('.bulk-check').each(function() {
            const price = parseFloat($(this).val()) || 0;
            const qtyInput = $(this).closest('.form-check').find('.bulk-qty');
            let qty = parseInt(qtyInput.val()) || 20;

            if (qty < 20) {
                qty = 20;
                qtyInput.val(20);
            }

            if ($(this).is(':checked')) {
                const extraQty = qty - 20;
                if (extraQty > 0) total += extraQty * price;
            }
        });

        // Add canvas price
        if ($('.canvas_input').is(':checked')) {
            total += parseFloat($('.canvas_input').val()) || 0;
        }


        $('.gift-checkbox').each(function() {
            const price = parseFloat($(this).val()) || 0;
           

            if ($(this).is(':checked')) {
               total += price;
            }
        });





        // Set total price input
        $('.total_price').val(total.toFixed(2));

        // Debug log
        console.log("Total Amount:", total.toFixed(2));
    }

    // Event listeners
    $('.creative_art_checkbox, .bulk-check, .bulk-qty, .canvas_input,.gift-checkbox').on('change input', updateTotal);

    // Initial calculation
    updateTotal();
});

</script>




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
