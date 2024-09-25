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
                            <form class="forms-sample" method="POST" action="{{ route('companyaward.update', $companyaward->id )}}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-award_name">Award Name :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('award_name') is-invalid @enderror"
                                            placeholder="Company Name" id="basic-default-award_name" name="award_name" value="{{ $companyaward->award_name }}">
                                            {{-- value="{{ $setting->system_name ?? '' }}" --}}
                                        @error('award_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row mb-3">
                                        <div class="col">
                                            <label>Award Image :</label>
                                            <input type="file"
                                                class="form-control form-control-md border-left-0 dropify @error('logo') is-invalid @enderror"
                                                name="award_image"
                                                data-default-file="@isset($companyaward){{ url('backend/uploads/' . $companyaward->award_image) }}@endisset">
                                            @error('logo')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col">
                                        <label class="form-lable" for="basic-default-date">Date :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('date') is-invalid @enderror"
                                            placeholder="Date" id="basic-default-date" name="date" value="{{ $companyaward->date }}">
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-month">Month :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('month') is-invalid @enderror"
                                            placeholder="Month ..." id="basic-default-month" name="month" value="{{ $companyaward->month }}">
                                        @error('month')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-year">Year :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('year') is-invalid @enderror"
                                            placeholder="Year ..." id="basic-default-year" name="year" value="{{ $companyaward->year }}">
                                        @error('year')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div>
                                        <div class="col">
                                            <label class="form-lable" for="basic-default-company">Company :</label>
                                                <select name="company" id="basic-default-company" class="form-control form-control-md border-left-0 @error('company') is-invalid @enderror">
                                                    <option value="" selected>Select an option</option>
                                                    @foreach ($companys as $company)
                                                    <option value="{{ $company->id }}"  {{ $company->id == $companyaward->company_id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                                                    @endforeach
                                                </select>
                                            @error('company')
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

