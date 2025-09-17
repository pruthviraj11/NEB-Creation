@extends('layouts.homeLayout')
@section('title', $pageTitle['page_name']." | ".'NEB Creation')
@push('styles')
<style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .card {
      max-width: 500px;
      margin: 100px auto; /* ⬅ increased top space */
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
      padding: 50px 30px; /* ⬅ more padding makes it taller */
    }
    .card img {
      width: 100px;
      margin-bottom: 20px;
    }
    .card h2 {
      color: #28a745;
      margin-bottom: 15px;
      font-size: 24px;
    }
    .card p {
      color: #555;
      font-size: 16px;
      margin-bottom: 30px;
      line-height: 1.6;
    }
    .card a {
      display: inline-block;
      padding: 12px 24px;
      background: #28a745;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-size: 16px;
      transition: background 0.3s;
    }
    .card a:hover {
      background: #218838;
    }
  </style>
@endpush
@section('content')
 <div class="card text-center p-4" style="max-width:500px; margin:100px auto;">
  <img src="https://img.icons8.com/color/96/000000/checked--v4.png" 
       alt="Success" 
       class="mx-auto d-block mb-3">
  <h2>Your Order Successfully Submitted!</h2>
 
 
</div>

@endsection
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        localStorage.removeItem("guest_id");
        console.log("guest_id removed from localStorage");
    });
</script>
@endpush