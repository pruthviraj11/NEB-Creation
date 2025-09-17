@extends('layouts.homeLayout')
@section('title', $pageTitle['page_name']." | ".'NEB Creation')

@section('content')
<div class="banner" style="background: url('{{ asset('home/images/22.jpg')}}') no-repeat center center/cover;">
    <div class="banner-content">
        <h1 class="text-center">About Us</h1>
        <p class="text-center">Discover Who We Are and What We Stand For</p>
    </div>
</div>


<div class="about-section ">
    <div class="">
        <div class="row g-0 about_content">

            <!-- Left Side Image -->
            <div class="col-md-6">
                <div class="about-img h-100">
                    <img src="{{ asset('home/images/about-img.jpg')}}" alt="About Image" class="img-fluid w-100 h-100 object-fit-cover">
                </div>
            </div>

            <!-- Right Side Content -->
            <div class="col-md-6 d-flex align-items-center bg-dark text-white p-5">
                <div class="about-content">
                    <h2 class="fw-bold">About Us</h2>
                    <p class="fst-italic">Look even slightly believable. If you are going to use a passage.</p>
                    <p>
                        There are many variations of passages of Lorem Ipsum available,
                        but the majority have suffered alteration in some form, by injected humour,
                    </p>
                    <p>
                        Integer sed tincidunt dui. Cras tincidunt at risus vitae ultrices.
                        Sed at placerat diam. Nam ornare feugiat blandit. Suspendisse potenti.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="py-5 bg-light">
    <div class="container">
        <div class="row text-center g-4">

            <div class="col-12 col-md-3">
                <div class="p-4 shadow rounded">
                    <!-- <i class="bi bi-person fs-1 mb-3"></i> -->
                    <i class="bi bi-briefcase fs-1 mb-3"></i>

                    <h3 class="fw-bold">+<span id="counter1">0</span></h3>
                    <p class="mb-0 text-center fw-semibold">YEARS EXPERIENCE</p>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="p-4 shadow rounded">
                    <!-- <i class="bi bi-people fs-1 mb-3"></i> -->
                    <i class="bi bi-people fs-1 mb-3"></i>

                    <h3 class="fw-bold">+<span id="counter2">0</span></h3>
                    <p class="mb-0 text-center fw-semibold">CUSTOMER</p>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="p-4 shadow rounded">
                    <!-- <i class="bi bi-bag fs-1 mb-3"></i> -->
                    <i class="bi bi-bag fs-1 mb-3"></i>

                    <h3 class="fw-bold">+<span id="counter3">0</span></h3>
                    <p class="mb-0 text-center fw-semibold">SELL PHOTOS</p>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="p-4  shadow rounded">
                    <!-- <i class="bi bi-image fs-1 mb-3"></i> -->

                    <i class="bi bi-image fs-1 mb-3"></i>

                    <h3 class="fw-bold">+<span id="counter4">0</span></h3>
                    <p class="mb-0 text-center fw-semibold">PHOTOS TAKEN</p>
                </div>
            </div>

        </div>
    </div>
</div>



<!-- Hero Section -->
<div class="hero-section d-flex align-items-center text-center text-white"
    style="background: url('{{ asset('home/images/travel.jpg')}}') center center/cover no-repeat;">
    <div class="container">
        <h2 class="fw-bold mb-3">Join Us</h2>
        <p class="mb-4 text-center">Buy stunning photos and bring your projects to life.</p>
        <a href="#" class="btn btn-dark rounded-pill px-4 py-2 more_photo">Join now</a>
    </div>
</div>





@endsection

@push('scripts')
<script>

    function startCounter(id, targetNumber, duration) {
        let counter = document.getElementById(id);
        let start = 0;
        let stepTime = Math.abs(Math.floor(duration / targetNumber));

        let timer = setInterval(() => {
            start++;
            counter.textContent = start;
            if (start >= targetNumber) {
                clearInterval(timer);
            }
        }, stepTime);
    }

    startCounter('counter1', 10, 2000);
    startCounter('counter2', 100, 2500);
    startCounter('counter3', 100, 2000);
    startCounter('counter4', 1000, 250);

</script>
@endpush





