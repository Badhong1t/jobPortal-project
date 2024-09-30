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
                        <h4 class="card-title">Find Talent</h4>
                        <p class="card-description">Find Talent, please <code>provide your valid
                                data</code>.</p>
                        <div class="mt-4">
                            <form class="forms-sample" method="POST" action="{{ route('talent.update') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-title">Title :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('title') is-invalid @enderror"
                                            placeholder="Title ..." id="basic-default-title" name="title" value="{{ $findtalent->title ?? '' }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label class="form-lable" for="basic-default-sub_title">Sub title :</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('sub_title') is-invalid @enderror"
                                            placeholder="Sub title ..." id="basic-default-sub_title" name="sub_title" value="{{ $findtalent->sub_title ?? '' }}">
                                        @error('sub_title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description :</label>
                                    <textarea id="basic-default-description" class="form-control @error('description') is-invalid @enderror" name="description">{{ $findtalent->description ?? '' }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label>Image :</label>
                                        <input type="file"
                                            class="form-control form-control-md border-left-0 dropify @error('image_url') is-invalid @enderror"
                                            name="image_url"
                                            data-default-file="@isset($findtalent->image_url){{ asset('backend/uploads/' . $findtalent->image_url) }}@endisset">
                                        @error('image_url')
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
            .create(document.querySelector('#basic-default-description'))
            .then(editor => {
                console.log('Editor was initialized', editor);
            })
            .catch(error => {
                console.error(error.stack);
            });

        $('.dropify').dropify();
    </script>
@endpush
