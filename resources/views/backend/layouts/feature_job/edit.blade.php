@extends('backend.app')

@section('title', 'Company Dashboard')

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
                        <h4 class="card-title">Edit </h4>
                        <p class="card-description">Edit feature job, please <code>provide your valid
                                data</code>.</p>
                        <div class="mt-4">
                            <form class="forms-sample" method="POST" action="{{ route('featurejob.update', $job->id )}}"
                            {{-- {{ route('company.store') }} --}}
                                enctype="multipart/form-data">
                                {{-- {{ route('system.update') }} --}}
                                @csrf
                                @method('patch')
                                <div class="form-group row mb-3">

                                    <div class="col">
                                        <label class="form-lable" for="basic-default-feature_name">Feature Name:</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('feature_name') is-invalid @enderror"
                                            placeholder="Feature Name" id="basic-default-feature_name" name="feature" value="{{ $job->feature }}">
                                        @error('feature_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-price">Price:</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('price') is-invalid @enderror"
                                            placeholder="Price ..." id="basic-default-price" name="price" value="{{ $job->price }}">
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label>Status:</label>
                                        <input class=" border-left-0 @error('status') is-invalid @enderror" type="radio" name="status" value="1" {{ $job->status == 1 ? 'checked' : '' }}> Active
                                        <input type="radio" name="status" value="0" {{ $job->status == 0 ? 'checked' : '' }}> Inactive
                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Feature Details:</label>
                                    <textarea id="editor" class="form-control @error('description') is-invalid @enderror" name="details" >{{ $job->details ?? '' }}</textarea>
                                    @error('description')
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

