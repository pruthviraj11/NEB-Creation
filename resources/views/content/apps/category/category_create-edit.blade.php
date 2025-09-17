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
    @if ($page_data['form_title'] == 'Add New Category')
        <form action="{{ route('app-category-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        @else
            <form action="{{ route('app-category-update', encrypt($category->id)) }}" method="POST"
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
                        <a href="{{ route('app-category-list') }}" class="col-md-2 btn btn-primary float-end">Category
                            Lists</a>

                        {{-- <h4 class="card-title">{{$page_data['form_title']}}</h4> --}}
                    </div>
                    <div class="card-body">
                        <div class="row">


                            <div class="col-md-6 col-sm-12 mb-1">
                                <label class="form-label" for="first_name">
                                    Category</label>
                                <input type="text" id="title" class="form-control" placeholder="Category Name"
                                    name="category"
                                    value="{{ old('category') ?? ($category != '' ? $category->category : '') }}">
                                <span class="text-danger">
                                    @error('category')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="col-md-6 col-sm-12 mb-1">
                                <label class="form-label" for="first_name">
                                    Parent Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Parent Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ isset($category->id) && $category->parent_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->category }}
                                        </option>
                                    @endforeach
                                </select>


                                <span class="text-danger">
                                    @error('partner_type')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>



                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status"
                                        {{ old('status', $category->status ?? 1) ? 'checked' : '' }}>
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
