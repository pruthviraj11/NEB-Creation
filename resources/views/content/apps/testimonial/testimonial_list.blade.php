@extends('layouts/layoutMaster')

@section('title', 'Testimonials List')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
@endsection

@section('vendor-script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
    <section class="app-user-list">
        <div class="card mt-5">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Testimonials List</h4>
                <a href="{{ route('app-testimonial-add') }}" class="btn btn-primary">Add Testimonial</a>
            </div>

            <div class="card-body border-bottom">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered w-100" id="testimonial-table">
                        <thead>
                            <tr>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Message</th>
                                <th>Stars</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $('#testimonial-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('app-testimonial-get-all') }}",
                lengthMenu: [10, 25, 50, 100],
                columns: [{
                        data: 'profile_pic',
                        name: 'profile_pic',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'designation',
                        name: 'designation'
                    },
                    {
                        data: 'message',
                        name: 'message',
                        render: function(data) {
                            return data && data.length > 60 ?
                                data.substring(0, 60) + '...' :
                                data;
                        }
                    },
                    {
                        data: 'star',
                        name: 'star',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                drawCallback: function() {
                    feather.replace();
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            });

            // SweetAlert Delete Confirmation
            $(document).on("click", ".confirm-delete", function(e) {
                e.preventDefault();
                var id = $(this).attr("href").split('/').pop();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.isConfirmed) {
                        window.location.href = '/admin/testimonial/destroy/' + id;
                    }
                });
            });
        });
    </script>
@endsection
