@extends('backend.app')

@section('title', 'Company Dashboard')

@section('content')
@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 150px;
        }
    </style>
@endpush

    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Company Facilities</h4>
                        <p class="card-description">Create company Facilities, please <code>provide your valid
                                data</code>.</p>
                        <div class="mt-4">
                            <form class="forms-sample mt-4" method="POST" action="{{ route('backend.companyFacilities.store') }}">
                                @csrf
                                    <div class="form-group mb-3">

                                            <label class="form-lable" for="basic-default-facility_title">Facility Title:</label>
                                            <input type="text"
                                                class="form-control form-control-md border-left-0 @error('facility_title') is-invalid @enderror"
                                                placeholder="facility_title" id="basic-default-facility_title" name="facility_title" >
                                            @error('facility_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="facility_description" class="form-lable text-uppercase">Facility Description:</label>
                                        <textarea id="facility_description" class="form-control @error('facility_description') is-invalid @enderror" rows="5" name="facility_description"></textarea>
                                        @error('facility_description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection







@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/41.3.1/ckeditor.min.js"></script>

    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
        .create(document.querySelector('#facility_description'), {
            height: '500px',
        })
        .catch(error => {
            console.error(error);
        });
    });

        $('.dropify').dropify();
    </script>
@endpush

