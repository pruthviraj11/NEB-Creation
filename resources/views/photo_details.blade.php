@extends('layouts.homeLayout')
@section('title', $pageTitle['page_name'] . ' | ' . 'NEB Creation')
<!-- Bootstrap CSS (if not already included) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap-Select CSS -->
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<!-- Bootstrap JS (if not already included) -->
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

<!-- Bootstrap-Select JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@push('styles')
    <style>
        .form-switch .form-check-input {
            border: 2px solid #333;
            --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgb(255,255,255)'/%3e%3c/svg%3e");
            coursor: pointer;
            outline: none;
            background-color: red;
            background-image: var(--bs-form-switch-bg);
        }



        .form-switch .form-check-input:checked {
            background-color: #333;
            border-color: #333;
            background-color: green;
        }
    </style>
    <style>
        .size-box-label {
            cursor: pointer;
            margin-bottom: 0;
        }

        .size-box-input {
            display: none;
        }

        .size-box {
            display: inline-block;
            padding: 3px 6px;
            border: 2px solid #33333357;
            border-radius: 0px;
            background: #f8f9fa;
            color: #333;
            font-weight: 500;
            text-align: center;
            min-width: 32px;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
            font-size: 13px;
        }

        .size-box-input:checked+.size-box {
            background: #333;
            color: #fff;
            border-color: #333;
        }
    </style>

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

        .checkbox_text {
            font-size: 14px
        }

        .final_price {
            width: 160px
        }

        .add-btn-cart {
            font-size: 14px;
        }

        .main-img-product {
            max-height: 350px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .main-img-product img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .list-cards .small-img {
            width: 60px;
            height: 60px;
            overflow: hidden;
            border-radius: 6px;
            border: 1px solid #eee;
            display: flex;
            flex: 0 0 60px;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }

        .list-cards .small-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .list-cards {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            width: 100%;
            flex-wrap: wrap;
            box-sizing: border-box;
        }

        .list-cards h6.card-title {
            flex: 1 1 auto;
            min-width: 80px;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .list-cards .varientPrice {
            flex: 0 0 80px;
            text-align: right;
        }

        .list-cards .d-flex.flex-wrap.gap-2 {
            flex: 1 1 100%;
        }

        .min-h-dvh {
            min-height: calc(100vh - 180px);
        }
    </style>
@endpush

@section('content')

    <div class="photo_details min-h-dvh">
        <div class="container">
            <div class="user-select-none">
                <form action="{{ route('front-add-cart') }}" method="POST" class="row g-lg-4">
                    @csrf
                    <div class="col-lg-5 col-12 mb-4 mb-lg-0">
                        @php
                            $imageUrl =
                                isset($photo->front_image) && Storage::disk('public')->exists($photo->front_image)
                                    ? Storage::url($photo->front_image)
                                    : asset('no_image/no_slider_photo.png');
                        @endphp

                        <div class="main-img-product">
                            <img src="{{ $imageUrl }}" alt="Product Image"
                                class="img-fluid rounded shadow w-100 h-100 object-fit-cover">
                        </div>
                    </div>

                    <!-- Right: Details -->
                    <div class="col-lg-7 col-12 mb-3 mb-lg-0">
                        <h2 class="mb-3">{{ $photo->title }} <span
                                class="badge badge-dark bg-dark fs-6">{{ $photo->category_title }}</span></h2>

                        <p class="text-muted">{{ strip_tags(html_entity_decode($photo->short_description)) }}</p>

                        <div class="d-flex align-items-center justify-content-center">
                            <h4 class="text-dark mb-0 final_price">
                                @php
                                    if ($photo->discount_price != '') {
                                        $mainPrice = $photo->discount_price;
                                        $discountPrice = "$" . $photo->price;
                                        $cartPrice = $photo->discount_price;
                                    } else {
                                        $mainPrice = $photo->price;
                                        $discountPrice = '';
                                        $cartPrice = $photo->price;
                                    }
                                @endphp

                                {{-- <span class="fw-bold text-dark product_price">${{ $mainPrice }}</span> --}}
                                {{-- <del class="me-2 text-danger">{{$discountPrice}}</del> --}}
                            </h4>



                        </div>


                        <input type="hidden" name="photo_id" value="{{ $photo->id }}">
                        <input type="hidden" name="cart_price" value="{{ $cartPrice }}">
                        <input type="hidden" name="total_price" class="total_price" value="{{ $cartPrice }}">
                        <div class="clearfix"></div>

                        <div>
                            {{-- <div>
                                <h5 class="fw-bold" >Creative Arts</h5>
                                @foreach ($creatives as $creative)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input creative_art_checkbox" type="checkbox"
                                            name="creative_art[]" id="creative_art_{{ $creative->id }}"
                                            value="{{ $creative->id }}" data-id="{{ $creative->price }}">

                                        <label class="form-check-label checkbox_text"
                                            for="creative_art_{{ $creative->id }}">
                                            {{ $creative->title }} — ${{ $creative->price }}
                                        </label>
                                    </div>
                                @endforeach
                            </div> --}}

                            <div
                                class="mb-3 d-flex flex-column flex-lg-row align-items-lg-center align-items-start  justify-content-between">
                                <div class="form-check">
                                    <input checked class="form-check-input" type="checkbox" id="downloadCheckbox">
                                    <label class="form-check-label" for="downloadCheckbox" id="downloadLabel">
                                        Download
                                    </label>
                                </div>


                                <div
                                    class="d-flex align-items-center align-items-center justify-content-lg-end justify-content-between gap-3 w-100 w-lg-auto">
                                    <p class="m-0 fs-5 p-0 product_price">${{ $mainPrice }}</p>
                                    <button type="submit" name="action" value="add_to_cart"
                                        class="btn btn-dark btn-md   w-auto add-btn-cart m-0">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </div>

                            </div>


                            <div class="card mb-3">
                                <!-- Clickable Header -->
                                <a class="bg-dark text-white card-header d-flex justify-content-between align-items-center text-decoration-none text-dark"
                                    data-bs-toggle="collapse" href="#printableOptionsCollapse" role="button"
                                    aria-expanded="false" aria-controls="printableOptionsCollapse" style="cursor: pointer;">

                                    <h5 class="fw-bold mb-0">Printable Options</h5>
                                    <i class="bi bi-chevron-down collapse-icon transition"></i>
                                </a>

                                <!-- Collapsible Body -->
                                <div class="collapse" id="printableOptionsCollapse">
                                    <div class="card-body pb-2">
                                        <h6 class="fw-bold">Creative Arts</h6>
                                        <div class="row">
                                            @foreach ($creatives as $index => $creative)
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input creative_art_checkbox"
                                                            type="checkbox" name="creative_art[]"
                                                            id="creative_art_{{ $creative->id }}"
                                                            value="{{ $creative->id }}" data-id="{{ $creative->price }}">

                                                        <label class="form-check-label checkbox_text"
                                                            for="creative_art_{{ $creative->id }}">
                                                            {{ $creative->title }} — ${{ $creative->price }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="row px-2 p-md-0">
                                            <div class="col-md-6 col-12">
                                                <h6 class="fw-bold mt-2">Bulk Purchase</h6>
                                                @foreach ($bulkpurchases as $bulk)
                                                    <div
                                                        class="form-check d-flex align-items-center justify-content-between  mb-2">
                                                        <div>
                                                            <input class="form-check-input bulk-check me-2" type="checkbox"
                                                                name="bulk_id[]" id="{{ $bulk->id }}"
                                                                value="{{ $bulk->id }}"
                                                                data-id ="{{ $bulk->price }}">

                                                            <label class="form-check-label  checkbox_text me-3"
                                                                for="{{ $bulk->id }}">

                                                                {{ $bulk->title }} — {{ $bulk->max_quntity }}+ —
                                                                {{ $bulk->price }} each
                                                            </label>
                                                        </div>

                                                        <input type="number" name="bulk_quntity[]"
                                                            class="form-control bulk-qty" id="{{ $bulk->id }}"
                                                            min="{{ $bulk->max_quntity }}" max="2000"
                                                            value="{{ $bulk->max_quntity }}"
                                                            style="font-size:0.8rem; width: 70px; height: 30px;">
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <h6 class="fw-bold mt-2">Canvas</h6>
                                                <div class="form-check d-flex align-items-center mb-2">
                                                    <input class="form-check-input canvas_input me-2" type="checkbox"
                                                        name="canvas" id="canvas" value="195">

                                                    <label class="form-check-label checkbox_text me-3" for="canvas">
                                                        Buy 2 16x20's $195 Get 1 Free
                                                    </label>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <!-- Clickable Header -->
                                <a class="bg-dark text-white card-header d-flex justify-content-between align-items-center text-decoration-none text-dark"
                                    data-bs-toggle="collapse" href="#sendGiftCollapse" role="button"
                                    aria-expanded="false" aria-controls="sendGiftCollapse" style="cursor: pointer;">

                                    <h5 class="fw-bold mb-0">Send Gift</h5>
                                    <i class="bi bi-chevron-down collapse-icon transition"></i>
                                </a>

                                <!-- Collapsible Body -->
                                <div class="collapse" id="sendGiftCollapse">
                                    <div class="card-body pb-2">

                                        @foreach ($giftProducts as $product)
                                            <div class="d-flex align-items-center list-cards justify-content-between mb-2">
                                                <div class="small-img">
                                                    <img src="{{ Storage::url($product->product_image) }}"
                                                        alt="{{ $product->product_name }}" />
                                                </div>

                                                <h6 class="card-title fw-semibold">{{ $product->product_name }}</h6>

                                                <p class="card-text p-0 m-0 text-dark fw-bold varientPrice"
                                                    id="varientPrice_{{ $product->id }}">
                                                    @if (!empty($productsVarient) && $productsVarient->isNotEmpty())
                                                        ${{ number_format($productsVarient[0]->price, 2) }}
                                                    @else
                                                        ${{ number_format($product->product_price, 2) }}
                                                    @endif
                                                </p>

                                                <div>
                                                    @if ($product->product_varient == 1)
                                                        @php
                                                            $productsVarient = \App\Models\ProductVarientPrice::where(
                                                                'gift_product_id',
                                                                $product->id,
                                                            )
                                                                ->join(
                                                                    'product_varients',
                                                                    'gift_product_varient_prices.gift_varient_id',
                                                                    '=',
                                                                    'product_varients.id',
                                                                )
                                                                ->select(
                                                                    'gift_product_varient_prices.*',
                                                                    'product_varients.title as varient_name',
                                                                )
                                                                ->get();
                                                        @endphp


                                                        <div class="">

                                                            <div class="d-flex flex-wrap  align-items-center gap-2">
                                                                <label
                                                                    class="form-label small text-secondary fw-medium m-0">
                                                                    Select Size
                                                                </label>
                                                                @foreach ($productsVarient as $index => $varient_info)
                                                                    <label class="size-box-label">
                                                                        <input type="radio"
                                                                            name="varient_{{ $product->id }}"
                                                                            value="{{ $varient_info->id }}"
                                                                            data-price="{{ $varient_info->price }}"
                                                                            class="size-box-input varientRadio"
                                                                            @if ($index == 0) checked @endif>
                                                                        <span
                                                                            class="size-box">{{ $varient_info->varient_name }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div>
                                                            @php
                                                                $productsVarient = [];
                                                            @endphp
                                                        </div>
                                                    @endif
                                                </div>


                                                <div class="form-check mb-3">
                                                    <input class="form-check-input gift-checkbox" type="checkbox"
                                                        name="gift_id[]" value="{{ $product->id }}"
                                                        data-id ="{{ $product->product_price }}"
                                                        id="gift_{{ $product->id }}">
                                                    <label class="form-check-label" for="gift_{{ $product->id }}">
                                                        Select
                                                    </label>
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>



                            {{-- {{dd($bulkpurchases)}} --}}
                            {{-- <div>
                                <h5 class="fw-bold mt-2">Bulk Purchase</h5>
                                @foreach ($bulkpurchases as $bulk)
                                    <div class="form-check d-flex align-items-center mb-2">
                                        <input class="form-check-input bulk-check me-2" type="checkbox" name="bulk_id[]"
                                            id="{{ $bulk->id }}" value="{{ $bulk->id }}"
                                            data-id ="{{ $bulk->price }}">

                                        <label class="form-check-label  checkbox_text me-3" for="{{ $bulk->id }}">
                                           
                                            {{ $bulk->title }} — {{ $bulk->max_quntity }}+ — {{ $bulk->price }} each
                                        </label>

                                        <input type="number" name="bulk_quntity[]" class="form-control bulk-qty"
                                            id="{{ $bulk->id }}" min="{{ $bulk->max_quntity }}" max="2000"
                                            value="{{ $bulk->max_quntity }}" style="width: 65px; height: 35px;">
                                    </div>
                                @endforeach
                            </div> --}}
                            {{-- 
                            <div>

                                <h5 class="fw-bold mt-2">Canvas</h5>


                                <div class="form-check d-flex align-items-center mb-2">
                                    <input class="form-check-input canvas_input me-2" type="checkbox" name="canvas"
                                        id="canvas" value="195">

                                    <label class="form-check-label checkbox_text me-3" for="canvas">
                                        Buy 2 16x20's $195 Get 1 Free
                                    </label>


                                </div>
                            </div> --}}

                            {{-- <button type="submit" name="action" value="add_to_cart" class="btn btn-dark btn-md w-sm-auto ms-0">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button> --}}





                            @php
                                $currentUrl = urlencode(url()->current()); // Current page URL
                                $title = urlencode($photo->title ?? ''); // Optional: add photo title if available
                            @endphp

                            <?php
                            /*<div class="my-3">
                    <!-- <h5><i class="bi bi-globe"></i> Follow Us</h5> -->
                    <div class="social-links mt-3">
                        {{-- <a href="https://www.instagram.com/?url={{ $currentUrl }}" target="_blank" target="_blank"><i class="bi bi-instagram"></i></a> --}}
                        <a href="#" target="_blank" target="_blank"><i class="bi bi-instagram"></i></a>
                        <a href="#" target="_blank"><i class="bi bi-facebook"></i></a>
                        <a href="#" target="_blank"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div> */
                            ?>
                            <!-- <div class="mt-5">
                                                                                                                                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Blanditiis corporis libero beatae cum exercitationem officia eligendi voluptates qui quia dignissimos, modi ab tempore esse sequi mollitia facere perspiciatis? Laborum veniam possimus quisquam provident tempore maxime ipsum molestias voluptas, commodi praesentium sed dolores ipsa quasi aliquam temporibus velit, magni corporis reiciendis? Minima placeat id doloremque dicta delectus voluptatibus atque dolores, qui quod culpa facere aspernatur porro molestias. Placeat commodi corrupti earum soluta neque ipsa deserunt nostrum ea eaque inventore, necessitatibus expedita fuga nulla nesciunt. Pariatur, excepturi ipsum, quam accusamus ducimus officiis expedita voluptas at libero magnam facilis earum odit quo nobis.
                                                                                                                                </div> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- <hr class="container my-3"> --}}

    {{-- <div class="form-check form-switch container mb-3 d-flex justify-content-center align-items-center">
            <input type="checkbox" class="form-check-input me-2" id="showBox"> <span class="h4 mb-0">Would you like to
                buy gifts?</span>
        </div> --}}


    {{-- <div class="container mb-5" id="hiddenContent" style="display:block; margin-top:10px;">

            <div class="row g-4"> --}}
    {{-- @foreach ($giftProducts as $product)
                    <div class="col-sm-6 col-md-6">
                        <div class="card h-100 shadow-sm border-0 d-flex flex-row align-items-center p-3">
                            <img src="{{ Storage::url($product->product_image) }}" class="card-img-top img-fluid"
                                alt="{{ $product->product_name }}" style="max-width: 60%; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $product->product_name }}</h5>

                                @if ($product->product_varient == 1)
                                    @php

                                        $pvarients = explode(',', $product->varient_id);
                                        $pvarients = array_map('trim', $pvarients);
                                        $filteredVarients = $varients->whereIn('id', $pvarients);
                                    @endphp
                                    <div class="mb-2">
                                        <label for="varient_{{ $product->id }}"
                                            class="form-label small text-muted">Variant:</label>
                                        <select id="varient_{{ $product->id }}" name="varient[]"
                                            class="form-select form-select-sm">
                                            <option value="">Select Variant</option>
                                            @foreach ($filteredVarients as $varient)
                                                <option value="{{ $varient->id }}">{{ $varient->title }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                @endif

                                <p class="card-text mb-2 fw-bold">Price: ${{ number_format($product->product_price, 2) }}
                                </p>

                                <div class="form-check mb-3">
                                    <input class="form-check-input gift-checkbox" type="checkbox" name="gift_id[]"
                                        value="{{ $product->id }}" data-id ="{{ $product->product_price }}"
                                        id="gift_{{ $product->id }}">
                                    <label class="form-check-label" for="gift_{{ $product->id }}">
                                        Select
                                    </label>
                                </div>


                            </div>
                        </div>


                    </div>
                @endforeach --}}

    {{-- @foreach ($giftProducts as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 shadow-lg border-0 p-3 rounded-4 hover-shadow">
                            <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->product_name }}"
                                class="img-fluid rounded-3"
                                style="max-width: 100%; height: 190px; object-fit: cover; border: 1px solid #eee;">

                            <div class="card-body d-flex flex-column justify-content-between  ps-3">
                                <div class="d-flex justify-content-between align-items-center ">
                                    <h5 class="card-title fw-semibold">{{ $product->product_name }}</h5>

                                    <p class="card-text text-dark fw-bold fs-5 varientPrice"
                                        id="varientPrice_{{ $product->id }}">
                                        @if (!empty($productsVarient) && $productsVarient->isNotEmpty())
                                            ${{ number_format($productsVarient[0]->price, 2) }}
                                        @else
                                            ${{ number_format($product->product_price, 2) }}
                                        @endif
                                    </p>
                                </div>

                                @if ($product->product_varient == 1)
                                    @php
                                        $productsVarient = \App\Models\ProductVarientPrice::where(
                                            'gift_product_id',
                                            $product->id,
                                        )
                                            ->join(
                                                'product_varients',
                                                'gift_product_varient_prices.gift_varient_id',
                                                '=',
                                                'product_varients.id',
                                            )
                                            ->select(
                                                'gift_product_varient_prices.*',
                                                'product_varients.title as varient_name',
                                            )
                                            ->get();
                                    @endphp


                                    <div class="mb-3">
                                        <label class="form-label small text-secondary fw-medium mb-2">
                                            Select Size
                                        </label>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($productsVarient as $index => $varient_info)
                                                <label class="size-box-label">
                                                    <input type="radio" name="varient_{{ $product->id }}"
                                                        value="{{ $varient_info->id }}"
                                                        data-price="{{ $varient_info->price }}"
                                                        class="size-box-input varientRadio"
                                                        @if ($index == 0) checked @endif>
                                                    <span class="size-box">{{ $varient_info->varient_name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $productsVarient = [];
                                    @endphp
                                @endif





                                <div class="form-check form-switch">
                                    <input class="form-check-input gift-checkbox" type="checkbox" name="gift_id[]"
                                        value="{{ $product->id }}" id="gift_{{ $product->id }}"
                                        @if (!empty($productsVarient) && $productsVarient->isNotEmpty()) data-id="{{ number_format($productsVarient[0]->price, 2) }}"
                                    @else
                                        data-id="{{ number_format($product->product_price, 2) }}" @endif>
                                    <label class="form-check-label" for="gift_{{ $product->id }}">
                                        Select Gift
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach --}}


    {{-- 
            </div>


        </div> --}}

    {{-- </form> --}}

    <?php
    /*
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
*/
    ?>





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
                    //total += parseFloat($(this).val()) || 0;
                    total += parseFloat($(this).data('id'));
                    anyChecked = true;
                });

                // Add bulk prices
                $('.bulk-check').each(function() {
                    //const price = parseFloat($(this).val()) || 0;
                    const price = parseFloat($(this).data('id'));
                    const qtyInput = $(this).closest('.form-check').find('.bulk-qty');
                    let qty = parseInt(qtyInput.val()) || 20;


                    // if (qty < 20) {
                    //     qty = 20;
                    //     qtyInput.val(20);
                    // }

                    // if ($(this).is(':checked')) {
                    //     const extraQty = qty - 20;
                    //     if (extraQty > 0) total += extraQty * price;
                    // }

                    if ($(this).is(':checked')) {
                        total += qty * price;
                    }


                });

                // Add canvas price
                if ($('.canvas_input').is(':checked')) {
                    total += parseFloat($('.canvas_input').val()) || 0;
                }


                $('.gift-checkbox').each(function() {
                    //const price = parseFloat($(this).val()) || 0;
                    const price = parseFloat($(this).attr('data-id')) || 0;



                    if ($(this).is(':checked')) {
                        total += price;
                    }
                });





                // Set total price input
                $('.total_price').val(total.toFixed(2));
                $(".product_price").html("$" + total.toFixed(2));


                // Debug log
                console.log("Total Amount:", total.toFixed(2));
            }

            // Event listeners
            $('.creative_art_checkbox, .bulk-check, .bulk-qty, .canvas_input,.gift-checkbox').on('change input',
                updateTotal);

            // Initial calculation
            updateTotal();
        });
    </script>


    <script>
        $(document).ready(function() {
            // On page load, set default price based on checked variant (if any)
            $('.varientRadio:checked').each(function() {
                const price = parseFloat($(this).data('price'));
                const productId = $(this).attr('name').replace('varient_', '');
                $(`#varientPrice_${productId}`).text(`$${price.toFixed(2)}`);
                $(`#gift_${productId}`).attr('data-id', price); // ✅ set checkbox data-id
            });

            // When user selects a new variant
            $(document).on('change', '.varientRadio', function() {
                const price = parseFloat($(this).data('price'));
                const productId = $(this).attr('name').replace('varient_', '');

                // ✅ Update displayed price
                $(`#varientPrice_${productId}`).text(`$${price.toFixed(2)}`);

                // ✅ Update checkbox price data-id dynamically
                $(`#gift_${productId}`).attr('data-id', price);
            });
        });
    </script>


    <script>
        const box = document.getElementById("showBox");
        const content = document.getElementById("hiddenContent");

        box.addEventListener("change", function() {
            content.style.display = this.checked ? "block" : "none";
        });
    </script>
@endpush
