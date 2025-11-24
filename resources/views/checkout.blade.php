@extends('layouts.homeLayout')
@section('title', $pageTitle['page_name']." | ".'NEB Creation')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .promo-code-applied {
        background-color: #d1edff !important;
        border-color: #0d6efd !important;
        color: #0d6efd !important;
    }
</style>

@endpush

@section('content')
<!-- Banner Section -->
<div class="banner" style="background: url('{{asset('home/images/about-img.jpg')}}') no-repeat center center/cover;">
    <div class="banner-content">
        <h1 class="text-center">Checkout</h1>
        <p class="text-center">We'd love to hear from you. Get in touch today!</p>
    </div>
</div>

<!-- Flash Messages -->
@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="container mt-3">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="container mt-3">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<div class="container py-5">
    <form action="{{ route('front-add-checkout') }}" method="POST" class="row g-5">
        @csrf

        <!-- Left Column: Billing Address & Payment -->
        <div class="col-md-9 col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 p-4">
                <h3 class="mb-4 border-bottom pb-2">Billing Address</h3>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="firstName" class="form-label">First name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="firstName" name="first_name" 
                               value="{{ old('first_name', $billingDetails['first_name'] ?? '') }}" 
                               placeholder="Enter first name" required>
                    </div>

                    <div class="col-sm-6">
                        <label for="lastName" class="form-label">Last name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lastName" name="last_name" 
                               value="{{ old('last_name', $billingDetails['last_name'] ?? '') }}" 
                               placeholder="Enter last name" required>
                    </div>

                    <div class="col-sm-6">
                        <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="mobile" name="mobile" 
                               value="{{ old('mobile', $billingDetails['mobile'] ?? '') }}" 
                               placeholder="Enter mobile" required>
                    </div>

                    <div class="col-sm-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email', $billingDetails['email'] ?? '') }}" 
                               placeholder="Enter email" required>
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="address" name="address" 
                               value="{{ old('address', $billingDetails['address'] ?? '') }}" 
                               placeholder="Street address" required>
                    </div>

                    <div class="col-12">
                        <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control" id="address2" name="address2" 
                               value="{{ old('address2', $billingDetails['address2'] ?? '') }}" 
                               placeholder="Apartment or suite">
                    </div>

                    <div class="col-md-5">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="country" name="country" required>
                            <option value="">Choose Your Country</option>
                              
                                @foreach($countries as $country)
                                    <option value="{{ $country['code2'] }}" {{ $country['code2'] == 'US' ? 'selected' : '' }}>
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach


                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="state" name="state" required>
                            <option value="">-- Select State --</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="zip" class="form-label">Zip <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="zip" name="zip" 
                               value="{{ old('zip', $billingDetails['zip'] ?? '') }}" required>
                    </div>
                </div>

                <!-- Extra Billing Address Toggle -->
                

               
                <!-- Payment -->
                {{-- <h4 class="mt-4 mb-3 border-bottom pb-2">Payment Method</h4>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment" id="cod" value="cod" checked>
                    <label class="form-check-label" for="cod">Cash on Delivery</label>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="radio" name="payment" id="online" value="online">
                    <label class="form-check-label" for="online">Online</label>
                </div> --}}

            </div>
        </div>

        <!-- Right Column: Cart -->
        <div class="col-md-3 col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <h4 class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <span>Your Cart</span>
                </h4>
                <ul class="list-group mb-3">
                    @php $total = 0; @endphp
                    @foreach($carts as $cart)
                        @php $total += $cart->total_amount; @endphp
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div><h6 class="my-0">{{ $cart->title }}</h6></div>
                            <span>${{ $cart->total_amount }}</span>
                        </li>
                    @endforeach
                    
                    <!-- Promo Code Section -->
                    <li class="list-group-item">
                        <div class="mb-3">
                            <label for="promo_code" class="form-label fw-bold">Promo Code</label>
                            <input type="text" class="form-control mb-2" id="promo_code" name="promo_code" 
                                   value="{{ old('promo_code', $billingDetails['promo_code'] ?? '') }}" 
                                   placeholder="Enter promo code">
                            <button type="button" id="apply_promo" class="btn btn-outline-primary">Apply</button>
                            <small class="text-muted d-block mt-2" id="promo_help_text">Enter a valid promo code to get a discount</small>
                        </div>
                        <div id="promo_result" class="mt-2" style="display: none;">
                            <!-- Promo code result will be shown here -->
                        </div>
                    </li>

                    <!-- Pricing Breakdown -->
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Product Total</span>
                            <span id="product_total">${{ $total }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Delivery Charge</span>
                            <span id="delivery_charge">${{ number_format($delivery_charge, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small border-bottom pb-2 mb-2">
                            <span>Tax ({{ $tax_rate }}%)</span>
                            <span id="tax_amount">${{ number_format(($total + $delivery_charge) * ($tax_rate / 100), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Subtotal</span>
                            <span id="subtotal">${{ number_format($total + $delivery_charge + (($total + $delivery_charge) * ($tax_rate / 100)), 2) }}</span>
                        </div>
                        <div id="discount_section" class="d-flex justify-content-between text-success" style="display: none;">
                            <span>Coupon Discount (<span id="discount_code"></span>)</span>
                            <span id="discount_amount">-₹0</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2 mt-2 text-primary">
                            <span>Final Total</span>
                            <span id="final_total">${{ number_format($total + $delivery_charge + (($total + $delivery_charge) * ($tax_rate / 100)), 2) }}</span>
                        </div>
                    </li>
                </ul>
                
                <!-- Place Order Button -->
                <div class="mt-3">
                    <button type="submit" class="btn btn-dark btn-lg w-100">PLACE ORDER</button>
                </div>
            </div>
        </div>
          <input type="hidden" name="product_total" value="{{$total}}" id="product_total_input" />
          <input type="hidden" name="delivery_charge" value="{{ $delivery_charge }}" id="delivery_charge_input" />
          <input type="hidden" name="tax_rate" value="{{ $tax_rate }}" id="tax_rate_input" />
          <input type="hidden" name="tax_amount" value="{{ number_format(($total + $delivery_charge) * ($tax_rate / 100), 2) }}" id="tax_amount_input" />
          <input type="hidden" name="total_before_discount" value="{{ number_format($total + $delivery_charge + (($total + $delivery_charge) * ($tax_rate / 100)), 2) }}" id="total_before_discount_input" />
          <input type="hidden" name="total_amount" value="{{ number_format($total + $delivery_charge + (($total + $delivery_charge) * ($tax_rate / 100)), 2) }}" id="total_amount_input" />
          <input type="hidden" name="discount_amount" value="0" id="discount_amount_input" />
          <input type="hidden" name="applied_promo_code" value="" id="applied_promo_code_input" />
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Pricing structure constants
    const PRODUCT_TOTAL = {{ $total }};
    const DELIVERY_CHARGE = {{ $delivery_charge }};
    const TAX_RATE = {{ $tax_rate }}; // Tax rate from settings
    
    // Calculate base pricing - Subtotal includes Product + Delivery + Tax
    const subtotalWithDelivery = PRODUCT_TOTAL + DELIVERY_CHARGE;
    const taxAmount = subtotalWithDelivery * (TAX_RATE / 100);
    const subtotal = subtotalWithDelivery + taxAmount; // This is the subtotal (everything included)
    
    let currentDiscount = 0;
    let appliedPromoCode = '';

    function calculateAndUpdatePricing() {
        // Calculate current final total (subtotal minus discount)
        const finalTotal = subtotal - currentDiscount;
        
        // Update display
        $('#product_total').text(`$${PRODUCT_TOTAL.toFixed(2)}`);
        $('#delivery_charge').text(`$${DELIVERY_CHARGE.toFixed(2)}`);
        $('#tax_amount').text(`$${taxAmount.toFixed(2)}`);
        $('#subtotal').text(`$${subtotal.toFixed(2)}`);
        $('#final_total').text(`$${finalTotal.toFixed(2)}`);
        
        // Update hidden form fields
        $('#product_total_input').val(PRODUCT_TOTAL);
        $('#delivery_charge_input').val(DELIVERY_CHARGE);
        $('#tax_rate_input').val(TAX_RATE);
        $('#tax_amount_input').val(taxAmount.toFixed(2));
        $('#total_before_discount_input').val(subtotal.toFixed(2));
        $('#total_amount_input').val(finalTotal.toFixed(2));
        $('#discount_amount_input').val(currentDiscount.toFixed(2));
        $('#applied_promo_code_input').val(appliedPromoCode);
    }

    function applyPromoCode() {
        const promoCode = $('#promo_code').val().trim();
        
        if (!promoCode) {
            showPromoResult('Please enter a promo code', 'error');
            return;
        }

        // Show loading state
        $('#apply_promo').prop('disabled', true).text('Applying...');
        
        // Make AJAX request to validate promo code
        $.ajax({
            url: '{{ route("validate-promo-code") }}',
            method: 'POST',
            data: {
                promo_code: promoCode,
                total_before_discount: subtotal,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    applyDiscount(response.discount_amount, response.discount_percentage, promoCode, response.coupon_name);
                    showPromoResult(`${promoCode} applied! You saved $${response.discount_amount}`, 'success');
                } else {
                    showPromoResult(response.message, 'error');
                    resetDiscount();
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Failed to apply promo code. Please try again.';
                showPromoResult(message, 'error');
                resetDiscount();
            },
            complete: function() {
                // Only reset if promo code was not successfully applied (button should be "Remove" if successful)
                if (!currentDiscount) {
                    $('#apply_promo').prop('disabled', false).text('Apply');
                }
            }
        });
    }

    function removePromoCode() {
        resetDiscount();
        showPromoResult(`${appliedPromoCode} discount removed`, 'info');
    }

    $('#apply_promo').on('click', applyPromoCode);

    // Allow applying promo code with Enter key
    $('#promo_code').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#apply_promo').click();
        }
    });

    function applyDiscount(discountAmount, discountPercentage, promoCode, couponName) {
        currentDiscount = discountAmount;
        appliedPromoCode = promoCode;
        
        // Update display
        $('#discount_section').show();
        $('#discount_code').text(couponName || promoCode);
        $('#discount_amount').text(`-₹${discountAmount}`);
        
        // Recalculate and update pricing
        calculateAndUpdatePricing();
        
        // Disable promo code field and change button to "Remove"
        $('#promo_code').prop('readonly', true).addClass('promo-code-applied fw-bold');
        $('#apply_promo').removeClass('btn-outline-primary').addClass('btn-outline-danger').text('Remove');
        $('#promo_help_text').text('Promo code applied successfully! Click Remove to remove the discount.');
        
        // Change button behavior to remove promo code
        $('#apply_promo').off('click').on('click', removePromoCode);
    }

    function resetDiscount() {
        currentDiscount = 0;
        appliedPromoCode = '';
        
        // Reset display
        $('#discount_section').hide();
        
        // Recalculate and update pricing
        calculateAndUpdatePricing();
        
        // Reset promo code field and button
        $('#promo_code').prop('readonly', false).removeClass('promo-code-applied fw-bold').val('');
        $('#apply_promo').removeClass('btn-outline-danger').addClass('btn-outline-primary').text('Apply');
        $('#promo_help_text').text('Enter a valid promo code to get a discount');
        
        // Restore original apply button behavior
        $('#apply_promo').off('click').on('click', applyPromoCode);
    }

    function showPromoResult(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 'alert-info';
        
        $('#promo_result').html(`
            <div class="alert ${alertClass} alert-sm py-2 mb-0" role="alert">
                <small>${message}</small>
            </div>
        `).show();
        
        // Auto-hide after 5 seconds for success/info messages
        if (type !== 'error') {
            setTimeout(() => {
                $('#promo_result').fadeOut();
            }, 5000);
        }
    }

    // Initialize pricing on page load
    calculateAndUpdatePricing();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

$(document).ready(function () {

        $('#country, #state').select2({
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        });



    function loadStates(countryCode) {
        var $stateSelect = $('#state');
     
        // Clear existing options
        $stateSelect.empty().append('<option value="">-- Select State --</option>');

        if (!countryCode) return;

        $.ajax({
            url: "{{ route('states.byCountry') }}",
            type: 'GET',
            data: { code2: countryCode },
            success: function (states) {
                $.each(states, function (index, state) {
                    $stateSelect.append(
                        $('<option>', {
                            value: state.code,
                            text: state.name
                        })
                    );
                });

                // Auto-select old value if exists
                let selectedState = "{{ old('state', $billingDetails['state'] ?? '') }}";
                if (selectedState) {
                    $stateSelect.val(selectedState);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    // 🔥 Load states on page load (default: US already selected in Blade)
    var initialCountry = $('#country').val();
    loadStates(initialCountry);

    // 🔥 Load states when user changes country
    $('#country').on('change', function () {
        var newCountry = $(this).val();
        loadStates(newCountry);
    });

});
</script>


@endpush
 
@endsection

