@extends('layouts/layoutMaster')

@section('title', $page_data['page_title'])

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />

@endsection

@section('page-style')
    {{-- Page Css files --}}
@endsection

@section('content')
    

    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $page_data['form_title'] }}</h4>
                        <a href="{{ route('app-order-list') }}" class="col-md-3 btn btn-primary float-end">View Order Lists</a>

                        {{-- <h4 class="card-title">{{$page_data['form_title']}}</h4> --}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-md-12 details">
                                <h5>Customer Information</h5>
                            </div>
                             <div class="row mb-1">
                                  <div class="col-md-2"><strong>Name</strong></div>
                                  <div class="col-md-1">:</div>
                                  <div class="col-md-9">{{$order->fname.' '.$order->lname}} </div>

                             </div>

                             <div class="row mb-1">
                                  <div class="col-md-2"><strong>Email</strong></div>
                                  <div class="col-md-1">:</div>
                                  <div class="col-md-9">{{$order->email}}</div>

                             </div>

                             <div class="row mb-1">
                                  <div class="col-md-2"><strong>Mobile No</strong></div>
                                  <div class="col-md-1">:</div>
                                  <div class="col-md-9">{{$order->mobile}}</div>

                             </div>

                             <div class="row mb-1">
                                  <div class="col-md-2"><strong>Order Type</strong></div>
                                  <div class="col-md-1">:</div>
                                  <div class="col-md-9">{{ucfirst($order->order_type)}}</div>

                             </div>

                             <div class="row mb-1">
                                  <div class="col-md-2"><strong>Address</strong></div>
                                  <div class="col-md-1">:</div>
                                  <div class="col-md-9">{{$order->address1}}</br>{{$order->address2}}</div>

                             </div>

                            <div class="col-md-12 details mt-3">
    
     <h5>Order Details</h5>

    {{-- Table Header --}}
    <div class="row fw-semibold text-secondary border-bottom pb-2 mb-2">
        <div class="col-md-1 text-center">#</div>
        <div class="col-md-2">Image</div>
        <div class="col-md-4">Title & Details</div>
        <div class="col-md-1 text-center">Qty</div>
        <div class="col-md-2 text-end">Amount ($)</div>
    </div>

    @php $totalAmount = 0; @endphp

    @foreach($carts as $cart)
        @php $totalAmount += $cart->total_amount; @endphp

        <div class="row align-items-start border-bottom py-3 mb-2">
            <div class="col-md-1 text-center">{{ $loop->iteration }}</div>

            <div class="col-md-2">
                <img src="{{ Storage::url($cart->back_image) }}" 
                     alt="{{ $cart->title }}" 
                     class="img-fluid rounded shadow-sm" 
                     style="max-width: 80px; object-fit: cover;">
            </div>

            <div class="col-md-4">
                <h6 class="fw-bold mb-1">{{ $cart->title }}</h6>

                {{-- Creative Section --}}
                @if($cart->is_creative_art == "yes" && isset($cart->creative_items))
                    <div class="ms-2 mb-1">
                        <span class="fw-semibold">Creative Arts:</span>
                        <ul class="list-unstyled mb-0 ms-3">
                            @foreach($cart->creative_items as $creative)
                                <li>
                                    {{ $creative->title }}
                                    @if(isset($creative->price))
                                        - ${{ number_format($creative->price, 2) }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Bulk Purchase Section --}}
                @if($cart->is_bulk_purchase == "yes" && isset($cart->bulk_items))
                    @php
                        $cartexplode = explode(",", $cart->extra_bulk);
                        $cartexplode = array_map('trim', $cartexplode);
                    @endphp
                    <div class="ms-2 mb-1">
                        <span class="fw-semibold">Bulk Purchase:</span>
                        <ul class="list-unstyled mb-0 ms-3">
                            @foreach($cart->bulk_items as $index => $bulk)
                                <li>
                                    {{ $bulk->title }} — {{ $bulk->max_quntity }}+ — ${{ $bulk->price }} each
                                    @if(isset($cartexplode[$index]))
                                        : <em>Qty ({{ $cartexplode[$index] }})</em>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Canvas Section --}}
                @if($cart->is_canvas == "yes")
                    <div class="ms-2 mb-1">
                        <span class="fw-semibold">Canvas Offer:</span>
                        <p class="mb-0 ms-3 small text-muted">Buy 2 16x20's $195 Get 1 Free</p>
                    </div>
                @endif

                {{-- Gift Product Section --}}
                @if($cart->is_gift_product == "yes" && isset($cart->gift_items))
                    @php
                        $gproducts = explode(",", $cart->gift_product_id);
                        $gproducts = array_map('trim', $gproducts);
                        $filteredGiftItems = $cart->gift_items->whereIn('id', $gproducts);
                    @endphp

                    <div class="ms-2">
                        <span class="fw-semibold">Gift Products:</span>
                        <ul class="list-unstyled mb-0 ms-3">
                            @foreach($filteredGiftItems as $gift)
                                <li class="d-flex align-items-center mb-2">
                                    <img src="{{ Storage::url($gift->product_image) }}" 
                                         class="rounded shadow-sm me-2" 
                                         alt="{{ $gift->product_name }}" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <strong>{{ $gift->product_name }}</strong>
                                        @if(isset($gift->product_price))
                                            - ${{ number_format($gift->product_price, 2) }}
                                        @endif
                                        @if($gift->product_varient == 1)
                                            @php
                                                $gvarients = explode(",", $cart->varient_id);
                                                $product_variant = \App\Models\ProductVarient::where('id', $gvarients)->first();
                                            @endphp
                                            @if(isset($product_variant))
                                                <span class="d-block small text-muted">Variant Size: {{ $product_variant->title }}</span>
                                                <span class="d-block small text-muted">Variant Size: {{ $product_variant->title }}</span>
                                            @endif
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="col-md-1 text-center">1</div>
            <div class="col-md-2 text-end fw-semibold">${{ number_format($cart->total_amount, 2) }}</div>
        </div>
    @endforeach

    {{-- Total Row --}}
    <div class="row pt-4 justify-content-end">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-3">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Order Summary</h6>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span class="fw-semibold">${{ number_format($totalAmount, 2) }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Delivery Charge:</span>
                    <span class="fw-semibold">${{ number_format($order->delivery_charge, 2) }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tax (9.25%):</span>
                    <span class="fw-semibold">${{ number_format($order->tax_amount, 2) }}</span>
                </div>

                <div class="border-top mt-3 pt-2 d-flex justify-content-between align-items-center">
                    <span class="fw-bold fs-6 text-dark">Total Amount:</span>
                    <span class="fw-bold fs-6 text-success">
                        ${{ number_format($order->total_amount, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

  

</div>

                            <div class="col-md-12 details">
                                <h5>Payment Information</h5>
                            </div>
                             <div class="row mb-1">
                                  <div class="col-md-2"><strong>Transaction Id</strong></div>
                                  <div class="col-md-1">:</div>
                                  <div class="col-md-9">{{$order->transaction_id}}</div>
                             </div>

                             <div class="row mb-1">
                                  <div class="col-md-2"><strong>Amount</strong></div>
                                  <div class="col-md-1">:</div>
                                  <div class="col-md-9">${{$order->total_amount}}</div>
                             </div>

                             <div class="row mb-1">
                                  <div class="col-md-2"><strong>Status</strong></div>
                                  <div class="col-md-1">:</div>
                                  <div class="col-md-9">{{ucfirst($order->order_status)}}</div>
                             </div>




                        
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </section>
   
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>


@endsection
<style>
    .details h5
    {
        background-color:#242745;
        color:#fff;
        padding:5px; 
    }
    .details h4 {
    color: #2c3e50;
    border-color: #dee2e6;
}

.details img {
    transition: transform 0.2s ease-in-out;
}
.details img:hover {
    transform: scale(1.05);
}

.details ul li {
    font-size: 0.9rem;
    color: #555;
}

.details span.text-primary {
    font-size: 0.95rem;
}

.fw-semibold
{
    color:#242745 !important;
}

</style>    

@section('page-script')
    <script src="{{ asset('assets/js/forms-editors.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on("click", ".delete-file", function(e) {
        e.preventDefault();
        var id = $(this).data("idos");
        var file_type = $(this).data("image");
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ms-1'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                window.location.href = '/admin/partner/remove_files/' + id;
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Record has been deleted.',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your imaginary record is safe :)',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            }
        });
    });
</script>



<!-- Page js files -->
