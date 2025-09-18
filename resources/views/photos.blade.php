@extends('layouts.homeLayout')
@section('title', $pageTitle['page_name']." | ".'NEB Creation')

@section('content')

<!-- Banner Section -->
<div class="banner" style="background: url('{{asset('home/images/about-img.jpg')}}') no-repeat center center/cover;">
    <div class="banner-content">
        <h1 class="text-center">Photos</h1>
        <p class="text-center">We’d love to hear from you. Get in touch today!</p>
    </div>
</div>

<div class="featured-section p_30 feature_subtitle py-0 bg-light">
    <div class="py-5">
        <h2 class="text-center heading">Featured Photos</h2>
        <p class="text-muted text-center">A selection of our best-selling photos.</p>

        <!-- <div class=" my-5"> -->
        <div class="row g-5 mt-1">
            <!-- Photo Card -->

             @foreach($photos as $photo)
                @php
                if($photo->discount_price != '')
                {
                    $price = $photo->discount_price;
                    $cartPrice = $photo->discount_price;
                }
                else
                {
                    $price = $photo->price;
                    $cartPrice = $photo->price;
                }
            @endphp

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <form action="{{ route('front-add-cart') }}" method="POST" class="d-flex flex-column flex-sm-row align-items-start gap-2">
                @csrf 
                <input type="hidden" name="photo_id" value="{{ $photo->id }}">
                <input type="hidden" name="cart_price" value="{{ $cartPrice }}">
                <div class="photo-card w-100">
                    <span class="price-tag">${{$price}}</span>
                    @php
                        $imageUrl =
                            isset($photo->front_image) && Storage::disk('public')->exists($photo->front_image)
                                ? Storage::url($photo->front_image)
                                : asset('no_image/no_image_photo.png');
                        @endphp

                    <img src="{{$imageUrl}}" class="img-fluid w-100" alt="Photo">

                    <div class="overlay-part overlay-top-left d-none d-md-flex">
                    </div>

                    <div class="overlay-part overlay-bottom-right d-none d-md-flex">
                        <div>
                            <span>${{$price}}</span>
                            <div class="link-btns mt-3">
                                <button type="submit" class="btn btn-outline btn-dark m-0 btn-sm">
                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                </button>
                                <a href="{{route('front-photo_details',$photo->slug)}}" class="btn btn-outline btn-dark m-0 btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="link-btns justify-content-between d-flex d-md-none mt-3">
                    <button type="submit" class="btn btn-dark m-0 btn-sm">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                    <a href="{{route('front-photo_details',$photo->slug)}}" class="btn btn-dark m-0 btn-sm">
                        <i class="bi bi-eye"></i> View
                    </a>
                </div>
            </form>
            </div>

            @endforeach

            
        </div>
        <!-- <div class="mt-5">
            <a href="#" class="btn btn-dark more_photo">More <i class="bi bi-arrow-down"></i></a>
        </div> -->
        <!-- </div> -->
    </div>
</div>

@endsection




