@extends('layouts/layoutMaster')

@section('title', $page_data['page_title'])

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


@endsection





@section('page-style')
    {{-- Page Css files --}}
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@section('page-script')
    <script>
        (function() {
            const fullToolbar = [
                [{
                    font: []
                }, {
                    size: []
                }],
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    color: []
                }, {
                    background: []
                }],
                [{
                    script: 'super'
                }, {
                    script: 'sub'
                }],
                [{
                    header: '1'
                }, {
                    header: '2'
                }, 'blockquote', 'code-block'],
                [{
                    list: 'ordered'
                }, {
                    list: 'bullet'
                }, {
                    indent: '-1'
                }, {
                    indent: '+1'
                }],
                [{
                    direction: 'rtl'
                }],
                ['link', 'image', 'video', 'formula'],
                ['clean']
            ];

            var quill = new Quill('#quillDescription_new', {
                bounds: '#quillDescription_new',
                placeholder: 'Type Something...',
                modules: {
                    formula: true,
                    toolbar: fullToolbar
                },
                theme: 'snow'
            });

            var hiddenInput = document.querySelector('#description');

            // Sync content to hidden textarea on text change
            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML;
            });

            // Sync hidden textarea right before submit as fallback
            document.querySelector('form').addEventListener('submit', function() {
                hiddenInput.value = quill.root.innerHTML;
            });

            // Initialize hidden textarea content to Quill content on page load
            hiddenInput.value = quill.root.innerHTML;



            /*----- ShortDescription ---------------*/
            var quill_short = new Quill('#quillDescription_short', {
                bounds: '#quillDescription_short',
                placeholder: 'Type Something...',
                modules: {
                    formula: true,
                    toolbar: fullToolbar
                },
                theme: 'snow'
            });

            var hiddenInput1 = document.querySelector('#short_description');

            // Sync content to hidden textarea on text change
            quill_short.on('text-change', function() {
                hiddenInput1.value = quill_short.root.innerHTML;
            });

            // Sync hidden textarea right before submit as fallback
            document.querySelector('form').addEventListener('submit', function() {
                hiddenInput1.value = quill_short.root.innerHTML;
            });

            // Initialize hidden textarea content to Quill content on page load
            hiddenInput1.value = quill_short.root.innerHTML;





        })();
    </script>
@endsection

@section('content')
    @if ($page_data['form_title'] == 'Add New Photography')
        <form action="{{ route('app-photography-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        @else
            <form action="{{ route('app-photography-update', encrypt($photograpghy->id)) }}" method="POST"
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
                        <a href="{{ route('app-photography-list') }}" class="col-md-2 btn btn-primary float-end">Photography
                            Lists</a>

                        {{-- <h4 class="card-title">{{$page_data['form_title']}}</h4> --}}
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12 col-sm-6 my-2">
                                <label class="form-label" for="first-name-column">Category</label>

                                <select name="category" class="select2 category_id form-select">
                                    <option value=''>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @if (!empty($photograpghy) && $photograpghy->category_id == $category->id) selected @endif>
                                            {{ $category->category }}
                                        </option>
                                    @endforeach
                                </select>

                                <span class="text-danger">
                                    @error('category')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>


                            {{-- <div class="col-md-6 col-sm-6 my-2">
                                <label class="form-label" for="first-name-column">Sub Category</label>

                                
                                <select name="parent_category" class="select2 form-select parent_category_id">
                                    <option value=''>Select Parent Category</option>

                                    @if (!empty($parentCat) && count($parentCat) > 0)
                                        @foreach ($parentCat as $pcat)
                                            <option value="{{ $pcat->id }}"
                                                @if (!empty($photograpghy) && $photograpghy->parent_id == $pcat->id) selected @endif>{{ $pcat->category }}
                                            </option>
                                        @endforeach
                                    @endif
                                 
                                </select>
                                <span class="text-danger">
                                    @error('parent_category')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div> --}}

                            <div class="col-md-12 col-sm-12 mb-1">
                                <label class="form-label" for="first_name">
                                    Photography Title</label>
                                <input type="text" id="title" class="form-control" placeholder="Title"
                                    name="title" value="{{ old('title') ?? ($photograpghy != '' ? $photograpghy->title : '') }}">
                                <span class="text-danger">
                                    @error('title')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="col-md-12 col-sm-12 my-2">
                                <label class="form-label" for="image"><strong>Photo Image<strong></label>
                                <input type="file" id="image" name="image" class="form-control"
                                    accept="image/*">
                               
                                <span class="text-danger">
                                    @error('image')
                                        {{ $message }}
                                    @enderror
                                </span>

                                @if ($photograpghy && $photograpghy->back_image)
                                    @php
                                        $PhotoimageUrl =
                                            isset($photograpghy->back_image) && Storage::disk('public')->exists($photograpghy->back_image)
                                                ? Storage::url($photograpghy->back_image)
                                                : asset('no_image/no_image.png');

                                    @endphp
                                   

                                    <div class="position-relative d-inline-block project-image-wrapper"
                                        style="width: 150px;">
                                        
                                        <img src="{{ $PhotoimageUrl }}" class="img-fluid"
                                            style="height: 150px; object-fit: cover; width: 100%;">

                                        <a href="javascript:void(0);"
                                            class="btn btn-sm btn-danger delete-file position-absolute top-0 end-0 m-1"
                                            data-idos="{{ $photograpghy->id }}" data-image="banner"
                                            style="padding: 2px 6px; border-radius: 50%;">
                                            {{-- SVG remove icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                fill="white" viewBox="0 0 24 24">
                                                <path d="M3 6h18v2H3zm2 3h14l-1.5 13.5h-11L5 9zm5-6h4v2h-4z" />
                                            </svg>
                                        </a>

                                        

                                        
                                        </div>

                                        <a href="{{Storage::url($photograpghy->front_image)}}" class="ml-5 watermark" target="_blank">View Watermark Photo</a>

                                        
                                @endif
                                
                            </div>
                              <div class="row">

                                    <div class="col-md-6 col-sm-12 mb-1">
                                        <label class="form-label" for="first_name">
                                            Price</label>
                                        <input type="text" id="price" class="form-control" placeholder="Price"
                                            name="price" value="{{ old('price') ?? ($photograpghy != '' ? $photograpghy->price : '') }}">
                                        <span class="text-danger">
                                            @error('price')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>

                                <div class="col-md-6 col-sm-12 mb-1">
                                    <label class="form-label" for="first_name">
                                        Discounted Price</label>
                                    <input type="text" id="discount_price" class="form-control" placeholder="Discount Price"
                                        name="discount_price" value="{{ old('discount_price') ?? ($photograpghy != '' ? $photograpghy->discount_price : '') }}">
                                    <span class="text-danger">
                                        @error('discount_price')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                </div>


                                <div class="col-md-12 col-sm-12 mt- mb-1">
                                <label class="form-label" for="short_description">Short Description</label>

                                <!-- Hidden textarea for form submission -->
                                <textarea name="short_description" id="short_description" class="d-none">
                                    {{ old('short_description') ?? ($photograpghy != '' ? $photograpghy->short_description : '') }}
                                </textarea>

                                <!-- Quill editor container -->
                                <div id="quillDescription_short" style="min-height: 200px;">
                                    {!! old('short_description') ?? ($photograpghy != '' ? $photograpghy->short_description : '') !!}
                                </div>




                                <div class="col-md-12 col-sm-12 mb-1">
                                <label class="form-label" for="description">Description</label>

                                <!-- Hidden textarea for form submission -->
                                <textarea name="description" id="description" class="d-none">
                                    {{ old('description') ?? ($photograpghy != '' ? $photograpghy->description : '') }}
                                </textarea>

                                <!-- Quill editor container -->
                                <div id="quillDescription_new" style="min-height: 200px;">
                                    {!! old('description') ?? ($photograpghy != '' ? $photograpghy->description : '') !!}
                                </div>



                                <span class="text-danger">
                                    @error('description')
                                        {{ $message }}
                                    @enderror
                                </span>

                                <style>
                                .light-style .ql-snow .ql-editor {
                                    padding: 1.25rem;
                                    max-height: 200px;
                                }


                                #quillDescription_new,#quillDescription_short {
                                    max-height: 300px;
                                }
                            </style>
                            </div>


                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="first_name">Display Home Page?</label>
                                <select name="is_home" class="form-control">
                                <option value="no" {{ ($photograpghy != '' && $photograpghy->is_home == 'no') ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ ($photograpghy != '' && $photograpghy->is_home == 'yes') ? 'selected' : '' }}>Yes</option>
                                </select>

                            </div>
                            
                            



                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status"
                                        {{ old('status', $photograpghy->status ?? 1) ? 'checked' : '' }}>
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
<style>
 .watermark
 {
    color:blue !important;
 }   
</style>

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
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select Category"
        });
    });
</script>


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
                window.location.href = '/admin/photography/remove_files/' + id + '/' + file_type;
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


<script>
    $(document).on('change', '.category_id', function() {
        var catId = $(this).val();

        if (!catId) {
            $('.parent_category_id').empty().append('<option value="">Select Parent Category</option>');
            return;
        }

        $.ajax({
            url: '{{ route('app-photography-parent-category', ['catId' => ':catId']) }}'.replace(':catId',
                catId),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var subcategoryDropdown = $('.parent_category_id');
                subcategoryDropdown.empty().append(
                    '<option value="">Select Parent Category</option>');

                $.each(data, function(key, value) {
                    subcategoryDropdown.append('<option value="' + value.id + '">' + value
                        .category + '</option>');
                });

                subcategoryDropdown.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching records:', error);
            }
        });
    });
</script>



<!-- Page js files -->
