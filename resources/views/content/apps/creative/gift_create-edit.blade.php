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
                                    value="{{ old('size') ?? ($gift != '' ? $gift->title : '') }}">
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
                            </div>

                            <div class="col-md-12 col-sm-6 mb-1">
                                <label class="form-label" for="price">
                                    Price($)</label>
                                <input type="text" id="price" class="form-control" placeholder="Price"
                                    name="price"
                                    value="{{ old('price') ?? ($gift != '' ? $gift->title : '') }}">
                                <span class="text-danger">
                                    @error('price')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="row">
                            <div class="col-md-6 col-sm-6 mb-1">
                            <label class="form-label" for="is_varient">
                                    Is Varient?</label>
                                <select name="is_varient" id="is_varient" class="form-control is_varient" >
                                 <option value="0">No</option>
                                 <option value="1">Yes</option>
                                </select>

                            </div>  
                            
                            <div class="col-md-6 col-sm-6 mb-1 varientSection" style="display:none">
                                 <label class="form-label" for="varients">
                                    Select Varient</label>
                                <select name="varients[]" id="varients" class="form-control varients multiple" >
                                    @foreach($varients as $varient)
                                    <option value="{{$varient->id}}">{{$varient->title}}</option>
                                    @endforeach
                                 
                                </select>

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
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function(){
    
    $(".is_varient").change(function(){
        $val = $(this).val();
        if($val == 1)
        {
            $(".varientSection").show();
            
        }
        else
        {
           $(".varientSection").hide(); 
        }

    });
   });
</script>



<!-- Page js files -->
