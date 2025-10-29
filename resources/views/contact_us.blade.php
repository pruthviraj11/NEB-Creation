@extends('layouts.homeLayout')
@section('title', $pageTitle['page_name'] . ' | ' . 'NEB Creation')

@section('content')


    <div class="banner" style="background: url('{{ asset('home/images/22.jpg') }}') no-repeat center center/cover;">
        <div class="banner-content">
            <h1 class="text-center">Contact Us</h1>
            <p class="text-center">We’d love to hear from you. Get in touch today!</p>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="contact-section">
        <div class="container contact_form_border shadow-lg rounded p-4 bg-white">
            <div class="row align-items-center">

                <!-- Left side: Image -->
                <div class="col-md-4 contact-img mb-4 mb-md-0">
                    <img src="{{ asset('home/images/57.jpg') }}" alt="Contact Image" class="img-fluid rounded shadow">
                </div>

                <!-- Right side -->
                <div class="col-md-8 mb-4 mb-md-0">
                    <div class="row p-4">

                        <!-- Form -->
                        <div class="col-md-7">
                            <div class="contact-form">
                                <h3 class="mb-4 fw-bold">Get In Touch</h3>

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                                    </div>
                                @endif

                                {{-- Error Message --}}
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                                    </div>
                                @endif



                                <form action="{{ route('contact-store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" name="name" class="form-control" placeholder="Full Name">
                                        <span class="text-danger">
                                            @error('name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control" placeholder="E-mail">
                                        <span class="text-danger">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control" name="message" rows="3" placeholder="Message"></textarea>
                                        <span class="text-danger">
                                            @error('message')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <button type="submit">Contact Us</button>
                                </form>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        {{-- <div class="col-md-5 d-flex flex-column justify-content-center">
                            <div class="contact-info ps-md-4 mt-4 mt-md-0">

                                <h5><i class="bi bi-geo-alt"></i> Address</h5>
                                <p class="w-75">40/b Rahgari Chok <br>San Francisco, California</p>

                            </div>
                        </div> --}}

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- <div class="container pb-5">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3696.4903852638163!2d72.49994837524649!3d23.00245391707881!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9b66c3a71071%3A0x9a653fcd8ea4b10e!2sDecent%20Infoways!5e1!3m2!1sen!2sin!4v1757582951411!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div> --}}






@endsection
