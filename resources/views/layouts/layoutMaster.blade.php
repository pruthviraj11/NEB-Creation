@isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
    $configData = Helper::appClasses();
@endphp
<!-- Toastr CSS -->
<style>
    .form-check-input:checked {
        background-color: #28a745 !important;
        /* Green */
        border-color: #28a745 !important;
    }

    .form-check-input:not(:checked) {
        background-color: #dc3546c9 !important;
        /* Red */
        border-color: #dc3546c9 !important;
    }

    /* Optional: Adjust the toggle knob color */
    /* .form-check-input:checked:focus {
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.5);
    }

    .form-check-input:not(:checked):focus {
        box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.5);
    } */
</style>
@isset($configData['layout'])
    @include(
        $configData['layout'] === 'horizontal'
            ? 'layouts.horizontalLayout'
            : ($configData['layout'] === 'blank'
                ? 'layouts.blankLayout'
                : ($configData['layout'] === 'front'
                    ? 'layouts.layoutFront'
                    : 'layouts.contentNavbarLayout')))
@endisset
<!-- jQuery (if not already included) -->
<script>
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @elseif (session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>


<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Toastr configuration (optional) -->
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "85000"
    };
</script>
