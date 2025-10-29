@extends('layouts/layoutMaster')

@section('title', $page_data['page_title'])

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <style>
        .star-rating {
            direction: rtl;
            display: inline-flex;
            gap: 5px;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            font-size: 1.8rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffca08;
        }
    </style>
@endsection

@section('content')
    @if ($page_data['form_title'] == 'Add New Testimonial')
        <form action="{{ route('app-testimonial-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        @else
            <form action="{{ route('app-testimonial-update', encrypt($testimonial->id)) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
    @endif

    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $page_data['form_title'] }}</h4>
                        <a href="{{ route('app-testimonial-list') }}" class="btn btn-primary">Testimonial List</a>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            {{-- Name --}}
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Client Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Enter client name" value="{{ old('name', $testimonial->name ?? '') }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Designation --}}
                            <div class="col-md-6 mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="text" id="designation" name="designation" class="form-control"
                                    placeholder="Enter client designation"
                                    value="{{ old('designation', $testimonial->designation ?? '') }}">
                                @error('designation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Message --}}
                            <div class="col-md-12 mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea id="message" name="message" class="form-control" rows="4" placeholder="Write client feedback...">{{ old('message', $testimonial->message ?? '') }}</textarea>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- ⭐ Star Rating --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Rating</label>
                                <div class="star-rating">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="star"
                                            value="{{ $i }}"
                                            {{ old('star', $testimonial->rating ?? '') == $i ? 'checked' : '' }}>
                                        <label for="star{{ $i }}">★</label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Image Upload --}}
                            <div class="col-md-6 mb-3">
                                <label for="profile_pic" class="form-label">Client Image</label>
                                <input type="file" id="profile_pic" name="profile_pic" class="form-control"
                                    accept="image/*">
                                @if (!empty($testimonial->image))
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/testimonials/' . $testimonial->image) }}"
                                            alt="Client Image" class="rounded"
                                            style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                @endif
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label d-block">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status"
                                        {{ old('status', $testimonial->status ?? 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                            </div>

                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-1">Submit</button>
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

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on("click", ".delete-file", function(e) {
            e.preventDefault();
            var id = $(this).data("id");
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
                    window.location.href = '/admin/testimonial/remove_image/' + id;
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Cancelled',
                        text: 'Your image is safe :)',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                }
            });
        });
    </script>
@endpush
