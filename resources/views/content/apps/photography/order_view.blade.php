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

                             <div class="col-md-12 details mt-1">
                                <h5>Order Details</h5>
                                <div class="row mb-2">
                                      <div class="col-md-1"><strong>#</strong></div>
                                      <div class="col-md-2"><strong>Image</strong></div>
                                      <div class="col-md-4"><strong>Title</strong></div>
                                      <div class="col-md-1"><strong>Quntity</strong></div>
                                      <div class="col-md-1"><strong>Amount</strong></div>
                                </div>
                                @php
                                    $totalAmount = 0;
                                @endphp
                                @foreach($carts as $cart)
                                    @php
                                        $totalAmount += $cart->amount;
                                    @endphp
                                <div class="row mb-2">
                                      <div class="col-md-1">{{ $loop->iteration }}</div>
                                      <div class="col-md-2"><img src="{{Storage::url($cart->back_image)}}" class="w-50"></div>
                                      <div class="col-md-4">{{$cart->title}}</div>
                                      <div class="col-md-1">1</div>
                                      <div class="col-md-1">{{$cart->amount}}</div>
                                </div>
                                @endforeach

                                <div class="row mb-2">
                                      <div class="col-md-1"></div>
                                      <div class="col-md-2"></div>
                                      <div class="col-md-4"></div>
                                      <div class="col-md-1"><strong>Total</strong></div>
                                      <div class="col-md-1"><strong>${{$totalAmount}}</strong></div>
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
