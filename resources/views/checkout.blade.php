@extends('layouts.homeLayout')
@section('title', $pageTitle['page_name']." | ".'NEB Creation')
@section('content')
<!-- Banner Section -->
<div class="banner" style="background: url('{{asset('home/images/about-img.jpg')}}') no-repeat center center/cover;">
    <div class="banner-content">
        <h1 class="text-center">Checkout</h1>
        <p class="text-center">We’d love to hear from you. Get in touch today!</p>
    </div>
</div>




<div class="container py-5">
    <form action="{{ route('front-add-checkout') }}" method="POST" class="row g-5">
        @csrf

        <!-- Left Column: Billing Address & Payment -->
        <div class="col-md-9 col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 p-4">
                <h3 class="mb-4 border-bottom pb-2">Billing Address</h3>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="firstName" class="form-label">First name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="firstName" name="first_name" placeholder="Enter first name" required>
                    </div>

                    <div class="col-sm-6">
                        <label for="lastName" class="form-label">Last name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Enter last name" required>
                    </div>

                    <div class="col-sm-6">
                        <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile" required>
                    </div>

                    <div class="col-sm-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Street address" required>
                    </div>

                    <div class="col-12">
                        <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control" id="address2" name="address2" placeholder="Apartment or suite">
                    </div>

                    <div class="col-md-5">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-select" id="country" name="country" required>
                            <option value="">Choose Your Country</option>
                            <option>India</option>
                            <option>Dubai</option>
                            <option>America</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                        <select class="form-select" id="state" name="state" required>
                            <option value="">Choose Your State</option>
                            <option>Gujarat</option>
                            <option>Mumbai</option>
                            <option>Bangalore</option>
                            <option>Pune</option>
                            <option>Delhi</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="zip" class="form-label">Zip <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="zip" name="zip" required>
                    </div>
                </div>

                <!-- Extra Billing Address Toggle -->
                

               
                <!-- Payment -->
                {{-- <h4 class="mt-4 mb-3 border-bottom pb-2">Payment Method</h4>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment" id="cod" value="cod" checked>
                    <label class="form-check-label" for="cod">Cash on Delivery</label>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="radio" name="payment" id="online" value="online">
                    <label class="form-check-label" for="online">Online</label>
                </div> --}}

                <!-- Submit -->
                <button type="submit" class="btn btn-dark btn-lg w-100">Place Order</button>
            </div>
        </div>

        <!-- Right Column: Cart -->
        <div class="col-md-3 col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <h4 class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <span>Your Cart</span>
                </h4>
                <ul class="list-group mb-3">
                    @php $total = 0; @endphp
                    @foreach($carts as $cart)
                        @php $total += $cart->amount; @endphp
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div><h6 class="my-0">{{ $cart->title }}</h6></div>
                            <span>₹{{ $cart->amount }}</span>
                        </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Total (₹)</strong>
                        <strong>₹{{ $total }}</strong>
                    </li>
                </ul>
            </div>
        </div>
          <input type="hidden" name="total_amount" value="{{$total}}" />
    </form>
</div>

 
@endsection

