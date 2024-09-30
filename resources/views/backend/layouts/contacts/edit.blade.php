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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit</h4>
                        <p class="card-description">Edit, please <code>provide your valid
                                data</code>.</p>
                        <div class="mt-4">
                            <form class="forms-sample" method="POST" action="{{ route('companybranch.update', $companybranch->id )}}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-company">Company Name:</label>
                                            <select name="company" id="basic-default-company" class="form-control form-control-md border-left-0 @error('company') is-invalid @enderror">
                                                <option value="" selected>Select an option</option>
                                                @foreach ($companys as $company)
                                                <option value="{{ $company->id }}"  {{ $company->id == $companybranch->company_id ? 'selected' : '' }}>{{ $company->company_name }}</option>
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
                                        <label class="form-lable" for="basic-default-branch_name">Branch Name :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('branch_name') is-invalid @enderror"
                                            placeholder="Branch Name" id="basic-default-branch_name" name="branch_name" value="{{ $companybranch->branch_name }}">
                                        @error('branch_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                    <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-address">address :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('date') is-invalid @enderror"
                                            placeholder="Address" id="basic-default-address" name="address" value="{{ $companybranch->address }}">
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
                                            placeholder="Phone ..." id="basic-default-phone" name="phone" value="{{ $companybranch->phone }}">
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
                                            class="form-control form-control-md border-left-0 @error('year') is-invalid @enderror"
                                            placeholder="Email ..." id="basic-default-email" name="email" value="{{ $companybranch->email }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-status">Status :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('year') is-invalid @enderror"
                                            placeholder="Status ..." id="basic-default-status" name="status" value="{{ $companybranch->status }}">
                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
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

