@extends('layouts.homeLayout')
@section('title', $pageTitle['page_name']." | ".'NEB Creation')
@section('content')

<div class="banner" style="background: url('{{asset('home/images/about-img.jpg')}}') no-repeat center center/cover;">
    <div class="banner-content">
        <h1 class="text-center">Cart</h1>
        <p class="text-center">We’d love to hear from you. Get in touch today!</p>
    </div>
</div>

@if(!empty($carts) && $carts->count() > 0)

<div class="container py-5">
    <div class="card shadow">
        <div class="card-body">
            @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif
            <!-- Header -->
            <h3 class="card-title mb-4">Shopping Cart</h3>
            

            <!-- Heading Row -->
            <div class="row d-none d-md-flex border-bottom py-3 fw-bold">
                <div class="col-md-2 text-center">Image</div>
                <div class="col-md-5">Title</div>
                <div class="col-md-1">Quantity</div>
                <div class="col-md-2 text-center">Price</div>
                <div class="col-md-2 text-end">Action</div>
            </div>

          
            @php
                $total = 0;
            @endphp
         
           @foreach($carts as $cart)
                @php
                    $total += $cart->total_amount;
                @endphp
            <div class="row align-items-center border-bottom py-3 cart-item">

                <div class="col-4 col-md-2 text-center mb-2 mb-md-0">
                    <img src="{{Storage::url($cart->front_image)}}" class="img-fluid rounded shadow-sm" style="max-width: 100px;" alt="Cloud with Mountain">
                </div>
                <div class="col-8 col-md-5 mb-2 mb-md-0">
                    <a href="{{route('front-photo_details',$cart->slug)}}" class="text-decoration-none fw-bold" style="color:#212529"><h6 class="mb-1">{{$cart->title}}</h6></a>
                
                     @if($cart->is_creative_art == "yes" && isset($cart->creative_items))
                      <span class="fw-bold">Creative Arts</span>
                    <ul class="ps-3 mb-0">
                        @foreach($cart->creative_items as $creative)
                            <li>
                                <strong>{{ $creative->title }}</strong>
                                @if(isset($creative->price))
                                    - ${{ number_format($creative->price, 2) }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif


                @if($cart->is_bulk_purchase == "yes" && isset($cart->bulk_items))
                      <span class="fw-bold">Bulk Purchase</span>
                    <ul class="ps-3 mb-0">
                       @php
                       
                        $cartexplode = explode(",", $cart->extra_bulk);
                        $cartexplode = array_map('trim', $cartexplode); // remove extra spaces
                    @endphp
                        @foreach($cart->bulk_items as $index => $bulk)
                            <li>
                                <strong>{{ $bulk->title }} — {{ $bulk->max_quntity }}+ — {{$bulk->price}} each</strong>
                                
                                     @if(isset($cartexplode[$index]))
                                         : Quntity({{ $cartexplode[$index] }})
                                    @endif
                                
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if($cart->is_canvas == "yes")
                    <span class="fw-bold">Canvas</span>
                    <h6>Buy 2 16x20's $195 Get 1 Free</h6>
                @endif

                {{-- {{dd($cart->gift_items)}} --}}

                @if($cart->is_gift_product == "yes" && isset($cart->gift_items))

                      <span class="fw-bold">Gift Products</span>


                    @php
                        $gproducts = explode(",", $cart->gift_product_id);
                        $gproducts = array_map('trim', $gproducts);
                        $filteredGiftItems = $cart->gift_items->whereIn('id', $gproducts);
                    @endphp

                    <ul class="ps-3 mb-0">
                        @foreach($filteredGiftItems as $gift)
                            <li class="d-flex align-items-center mb-2">
                                <img src="{{ Storage::url($gift->product_image) }}" 
                                    class="card-img-top img-fluid me-2" 
                                    alt="{{ $gift->product_name }}" 
                                    style="max-width:40px; object-fit: cover;">
                                <strong>{{ $gift->product_name }}</strong>
                                @if(isset($gift->product_price))
                                    - ${{ number_format($gift->product_price, 2) }}
                                @endif

                                @if($gift->product_varient == 1)
                                    @php
                                    
                                
                                    $gvarients = explode(",", $cart->varient_id);
                                    $product_variant =\App\Models\ProductVarient::where('id',$gvarients)->first();
                                    
                                @endphp

                                @if(isset($product_variant))
                                        - Varient Size : {{$product_variant->title}}
                                    @endif

                            
                            @endif


                            </li>
                        @endforeach
                    </ul> 
                @endif   




                </div>
                <div class="col-6 col-md-1 mb-2 mb-md-0">
                    <div class="input-group input-group-sm" style="max-width:120px;">
                        1
                    </div>
                </div>
                <div class="col-6 col-md-2 fw-bold text-md-center mb-2 mb-md-0">
                    $ {{$cart->total_amount}}
                </div>
                <div class="col-12 col-md-2 text-md-end text-start">
                    <a href="{{ route('front-remove-cart', $cart->id) }}" 
       class="btn btn-sm btn-outline-danger remove-item" 
       data-url="{{ route('front-remove-cart', $cart->id) }}">
        <i class="bi bi-x-lg"></i>
    </a>
                </div>
            </div>
           @endforeach 

            

            
          
            <div class="row align-items-center pt-3 fw-bold">
                <h3 class="col-6 col-md-8 text-end">Total:</h3>
                <h3 class="col-6 col-md-2 text-md-center">$ {{ number_format($total, 2) }}</h3>
                <h3 class="col-12 col-md-2"></h3>
            </div>

            <!-- Buttons -->


            <div class="d-flex flex-column flex-md-row justify-content-between mt-4 gap-2">
                <a href="{{route('front-photos')}}" class="btn btn-dark ">Continue Shopping</a>
                <a href="{{route('front-checkout')}}" class="btn btn-warning text-light ">Checkout</a>
            </div>
        </div>
    </div>
</div>
@else
 <h3 class="text-center text-danger fw-bold">Your cart is empty</h3>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".remove-item").forEach(function(button) {
        button.addEventListener("click", function (e) {
            e.preventDefault(); // prevent direct link opening
            let url = this.getAttribute("data-url");

            Swal.fire({
                title: "Are you sure?",
                text: "This photo will be removed from your cart!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // redirect to delete route
                }
            });
        });
    });
});
</script>

@endpush