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
                    <h1>Award</h1>
                    <div style="display: flex;justify-content: end;"><a
                        href="{{ route('companyaward.create') }}" class="btn btn-primary mb-3">Create New</a></div>

                    <div class="table-responsive mt-4 p-4">
                        <table class="table table-hover" id="data-tables">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Award Name</th>
                                    <th>Award Image</th>
                                    <th>Company Name</th>
                                    <th>Date</th>
                                    <th>Month</th>
                                    <th>Year</th>
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
            if (!$.fn.DataTable.isDataTable('#data-tables')) {
                let dTable = $('#data-tables').DataTable({
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
                        url: "{{ route('companyaward.index') }}",
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
                            data: 'award_name',
                            name: 'award_name',
                            orderable: false,
                            searchable: false
                        },

                        {
                            data: 'award_image',
                            name: 'award_image',
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
                            data: 'date',
                            name: 'date',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'month',
                            name: 'month',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'year',
                            name: 'year',
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
            var url = '{{ route('companyaward.delete', ':id') }}';
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
                    $('#data-tables').DataTable().ajax.reload();

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
                    // location.reload();
                }, // success end

            })
        }

    </script>
@endpush

