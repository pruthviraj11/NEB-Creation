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
    @if ($page_data['form_title'] == 'Add New Gift Product')
        <form action="{{ route('app-gift_product-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        @else
            <form action="{{ route('app-gift_product-update', encrypt($gift->id)) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
    @endif

    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $page_data['form_title'] }}</h4>
                        <a href="{{ route('app-varient-list') }}" class="col-md-2 btn btn-primary float-end">Varient Lists</a>

                        {{-- <h4 class="card-title">{{$page_data['form_title']}}</h4> --}}
                    </div>
                    <div class="card-body">
                        <div class="row">


                            <div class="col-md-12 col-sm-12 mb-1">
                                <label class="form-label" for="product_name">
                                    Product Name</label>
                                <input type="text" id="product_name" class="form-control" placeholder="Product Name"
                                    name="product_name"
                                    value="{{ old('size') ?? ($gift != '' ? $gift->product_name : '') }}">
                                <span class="text-danger">
                                    @error('product_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="col-md-12 col-sm-6 my-2">
                                <label class="form-label" for="image"><strong>Photo Image<strong></label>
                                <input type="file" id="image" name="image" class="form-control"
                                    accept="image/*">

                                <span class="text-danger">
                                    @error('image')
                                        {{ $message }}
                                    @enderror
                                </span>


                                @if ($gift && $gift->product_image)
                                    @php
                                        $GiftimageUrl =
                                            isset($gift->product_image) && Storage::disk('public')->exists($gift->product_image)
                                                ? Storage::url($gift->product_image)
                                                : asset('no_image/no_image.png');

                                    @endphp


                                    <div class="position-relative d-inline-block project-image-wrapper"
                                        style="width: 150px;">

                                        <img src="{{ $GiftimageUrl }}" class="img-fluid"
                                            style="height: 150px; object-fit: cover; width: 100%;">

                                        <a href="javascript:void(0);"
                                            class="btn btn-sm btn-danger delete-file position-absolute top-0 end-0 m-1"
                                            data-idos="{{ $gift->id }}" data-image="banner"
                                            style="padding: 2px 6px; border-radius: 50%;">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                fill="white" viewBox="0 0 24 24">
                                                <path d="M3 6h18v2H3zm2 3h14l-1.5 13.5h-11L5 9zm5-6h4v2h-4z" />
                                            </svg>
                                        </a>


                                        </div>

                                @endif


                            </div>

                            <div class="col-md-12 col-sm-6 mb-1">
                                <label class="form-label" for="price">
                                    Price($)</label>
                                <input type="text" id="price" class="form-control" placeholder="Price"
                                    name="price"
                                    value="{{ old('price') ?? ($gift != '' ? $gift->product_price : '') }}">
                                <span class="text-danger">
                                    @error('price')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="row">
                            <div class="col-md-12 col-sm-6 mb-1">
                            <label class="form-label" for="is_varient">
                                    Is Varient?</label>
                            <select name="is_varient" id="is_varient" class="form-control">
                                <option value="0" {{ isset($gift) && is_object($gift) && $gift->product_varient == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ isset($gift) && is_object($gift) && $gift->product_varient == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                            </div>



                              <!---  Dynamic Varient wise Flow ------>
                              <div class="container mt-3">


                                      <div id="variantContainer">
                                          {{-- 🔹 If editing: show existing variant-price records --}}
                                          @if(isset($product_varients) && count($product_varients) > 0)
                                              @foreach($product_varients as $key => $pv)
                                                    <div class="row varientRow mb-2">
                                                        <input type="hidden" name="variant_row_id[]" value="{{ $pv->id }}">

                                                        <div class="col-md-6 col-sm-6 mb-1 mt-2">
                                                            <label class="form-label">Select Variant</label>
                                                            <select name="varients[]" class="form-control varients">
                                                                @foreach($varients as $varient)
                                                                    <option value="{{ $varient->id }}" {{ $pv->gift_varient_id == $varient->id ? 'selected' : '' }}>
                                                                        {{ $varient->title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-5 col-sm-5 mb-1 mt-2">
                                                            <label class="form-label">Price</label>
                                                            <input type="text" name="varient_price[]" class="form-control varient_price" value="{{ $pv->price }}" />
                                                        </div>

                                                        <div class="col-md-1 col-sm-1 mb-1 mt-4">
                                                            <button type="button" class="btn btn-danger btn-sm removeRow" data-id="{{ $pv->id }}">✕</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                          @else
                                              {{-- 🔹 Default empty row (for Add mode or no variants found) --}}
                                              <div class="row varientRow mb-2">
                                                  <div class="col-md-6 col-sm-6 mb-1 mt-2">
                                                      <label class="form-label">Select Variant</label>
                                                      <select name="varients[]" class="form-control varients">
                                                          @foreach($varients as $varient)
                                                              <option value="{{ $varient->id }}">{{ $varient->title }}</option>
                                                          @endforeach
                                                      </select>
                                                  </div>

                                                  <div class="col-md-5 col-sm-5 mb-1 mt-2">
                                                      <label class="form-label">Price</label>
                                                      <input type="text" name="varient_price[]" class="form-control varient_price" />
                                                  </div>

                                                  <div class="col-md-1 col-sm-1 mb-1 mt-4">
                                                      <button type="button" class="btn btn-danger btn-sm removeRow" style="display:none;">✕</button>
                                                  </div>
                                              </div>
                                          @endif
                                      </div>

                                      <div class="mt-3">
                                          <button type="button" class="btn btn-success btn-sm" id="addRow">+ Add More</button>
                                      </div>
                                  </div>




                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status"
                                        {{ old('status', $gift->status ?? 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                            </div>





                        </div>

                        <div class="col-12">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary me-1">Submit
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </form>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>


@endsection

@section('page-script')
    <script src="{{ asset('assets/js/forms-editors.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function(){

  $('#is_varient').change(function() {
    if ($(this).val() == '1') {
        $('.container.mt-3').show();
    } else {
        $('.container.mt-3').hide();
    }
}).trigger('change');
   });
</script>

<script>
$(document).ready(function() {
    // 🔹 Template for new row
    let variantTemplate = `
        <div class="row varientRow mb-2">
            <div class="col-md-6 col-sm-6 mb-1 mt-2">
                <label class="form-label">Select Variant</label>
                <select name="varients[]" class="form-control varients">
                    @foreach($varients as $varient)
                        <option value="{{ $varient->id }}">{{ $varient->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5 col-sm-5 mb-1 mt-2">
                <label class="form-label">Price</label>
                <input type="text" name="varient_price[]" class="form-control varient_price" />
            </div>

            <div class="col-md-1 col-sm-1 mb-1 mt-4">
                <button type="button" class="btn btn-danger btn-sm removeRow">✕</button>
            </div>
        </div>
    `;

    // 🔹 Add new row
    $('#addRow').click(function() {
        $('#variantContainer').append(variantTemplate);
        $('.removeRow').show(); // always show remove button on new rows
    });

    // 🔹 Remove row
    // $(document).on('click', '.removeRow', function() {
    //     $(this).closest('.varientRow').remove();
    // });


    $(document).on('click', '.removeRow', function() {
    const variantId = $(this).data('id');
    if (variantId) {
        // Track deleted row IDs
        $('#variantContainer').append(`<input type="hidden" name="deleted_variant_ids[]" value="${variantId}">`);
    }
    $(this).closest('.varientRow').remove();
});

    // 🔹 Initially hide remove button if only one row exists
    if ($('.varientRow').length <= 1) {
        $('.removeRow').hide();
    } else {
        $('.removeRow').show();
    }
});




</script>


<script>
    $(document).on("click", ".delete-file", function(e) {
        e.preventDefault();
        var id = $(this).data("idos");
        
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
                window.location.href = '/admin/gift_product/remove_files/' + id;
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
