@extends('backend.app')

@section('title', 'Dashboard')

@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 150px;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Branch</h4>
                        <p class="card-description">Create branch, please <code>provide your valid
                                data</code>.</p>
                        <div class="mt-4">
                            <form class="forms-sample" method="POST" action="{{ route('companybranch.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <div class="col  mb-3">
                                        <label class="form-lable" for="basic-default-company">Company :</label>
                                            <select name="company" id="basic-default-company" class="form-control form-control-md border-left-0 @error('company') is-invalid @enderror">
                                                <option value="" selected>Select an option</option>
                                                @foreach ($companys as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                                @endforeach
                                            </select>
                                        @error('company')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-branch_name">Branch name:</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('branch_name') is-invalid @enderror"
                                            placeholder="Branch Name" id="basic-default-branch_name" name="branch_name" >
                                        @error('branch_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>

                                {{-- <div class="form-group row mb-3">
                                    <div class="col">
                                        <label>Award image:</label>
                                        <input type="file"
                                            class="form-control form-control-md border-left-0 dropify @error('award_image') is-invalid @enderror"
                                            name="award_image"
                                            data-default-file="@isset($setting){{ asset('backend/uploads/' . $setting->logo) }}@endisset">
                                        @error('award_image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div> --}}

                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-address">Address :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('address') is-invalid @enderror"
                                            placeholder="Address ..." id="basic-default-address" name="address" >
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-phone">Phone :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('phone') is-invalid @enderror"
                                            placeholder="Phone ..." id="basic-default-phone" name="phone" >
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-email">Email :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('email') is-invalid @enderror"
                                            placeholder="email ..." id="basic-default-email" name="email" >
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col">
                                    <label>Status :</label>
                                    <input class=" border-left-0 @error('status') is-invalid @enderror" type="radio" name="status" value="1"> Active
                                    <input type="radio" name="status" value="0"> Inactive
                                    @error('status')
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

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> --}}

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log('Editor was initialized', editor);
            })
            .catch(error => {
                console.error(error.stack);
            });

        $('.dropify').dropify();
    </script>
@endpush

