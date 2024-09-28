@extends('backend.app')

@section('title', 'Company Dashboard')


@push('style')
    <style>

    </style>
     <link rel="stylesheet" href="{{ url('backend/vendors/datatable/css/datatables.min.css') }}">
@endpush

{{-- @extends('layouts.app') --}}

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h1>Branch</h1>
                    <div style="display: flex;justify-content: end;"><a
                        href="{{ route('companybranch.create') }}" class="btn btn-primary mb-3">Create New</a></div>s
                    <div class="table-responsive mt-4 p-4">
                        <table class="table table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Company Name</th>
                                    <th>Branch Name</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    {{-- Datatable --}}
    <script src="{{ url('backend/vendors/datatable/js/datatables.min.js') }}"></script>
    {{-- sweet alart --}}
    <script src="{{ url('backend/vendors/sweetalert/sweetalert2@11.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            var searchable = [];
            var selectable = [];
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });
            if (!$.fn.DataTable.isDataTable('#data-table')) {
                let dTable = $('#data-table').DataTable({
                    order: [],
                    lengthMenu: [
                        [25, 50, 100, 200, 500, -1],
                        [25, 50, 100, 200, 500, "All"]
                    ],
                    processing: true,
                    responsive: true,
                    serverSide: true,

                    language: {
                        processing: `<div class="text-center">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                          </div>
                            </div>`
                    },

                    scroller: {
                        loadingIndicator: false
                    },
                    pagingType: "full_numbers",
                    dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'l><'col-md-2 col-sm-4 px-0'f>>tipr",
                    ajax: {
                        url: "{{ route('companybrach.index') }}",
                        type: "get",
                    },

                    columns: [

                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'company',
                            name: 'company',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'branch_name',
                            name: 'branch_name',
                            orderable: false,
                            searchable: false
                        },

                        {
                            data: 'address',
                            name: 'address',
                            orderable: false,
                            searchable: false
                        },

                        {
                            data: 'phone',
                            name: 'phone',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'email',
                            name: 'email',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },

                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });

                new DataTable('#example', {
                    responsive: true
                });
            }
        });



                 // Status Change Confirm Alert
         function showStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes update it',
                // cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    status(id);
                }
            });
        }
        // Status Change
        function status(id) {
            var url = '{{ route('companybranch.status', ':id') }}';
            $.ajax({
                type: "GET",
                url: url.replace(':id', id),
                success: function(resp) {
                    console.log(resp);
                    // Reloade DataTable
                    $('#data-table').DataTable().ajax.reload();
                    if (resp.success === true) {
                        // show toast message
                        Swal.fire({
                            title: "Good job!",
                            text: "Unpublished Successfully.",
                            icon: "success"
                        });
                    } else if (resp.success === false) {
                        Swal.fire({
                            title: "Good job!",
                            text: "published Successfully.",
                            icon: "success"
                        });
                    } else {
                        toastr.error(resp.message);
                    }
                }, // success end
                error: function(error) {
                    // location.reload();
                } // Erro
            });
        }

                 // delete Confirm
                 function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this record?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        };

        // Delete Button
        function deleteItem(id) {
            var url = '{{ route('companybranch.delete', ':id') }}';
            var csrfToken = '{{ csrf_token() }}';
                $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    console.log(resp);
                    // Reloade DataTable
                    $('#data-table').DataTable().ajax.reload();

                    if (resp.success == true) {
                        Swal.fire({
                        title: "Good job!",
                        text: "You clicked the button!",
                        icon: "success"

                        });

                    } else if (resp.errors) {
                        toastr.error(resp.errors[0]);
                    } else {
                        toastr.error(resp.message);
                    }
                    location.reload();
                }, // success end

            })
        }

    </script>
@endpush

