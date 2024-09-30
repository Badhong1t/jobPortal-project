@extends('backend.app')

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
                    <h4 class="card-title">Find Works</h4>
                    <form action="{{ route('backend.findWork.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        @method('POST')
                        <input type="hidden"  name="id" value="1">
                        <div class="form-group mb-3">
                            <label for="title" class="form-lable text-uppercase">Title<span class="text-danger">*</span></span></label>
                            <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $data[0]->title }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="sub_title" class="form-lable text-uppercase">Sub Title<span class="text-danger">*</span></span></label>
                            <input type="text" id="sub_title" class="form-control @error('sub_title') is-invalid @enderror" name="sub_title" value="{{ $data[0]->sub_title }}">
                            @error('sub_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="image" class="form-lable text-uppercase">Image <span class="text-danger">*</span>(max-size-3MB)</label>
                            <input type="file" id="image" class="form-control dropify" name="image"  data-default-file="{{ asset("storage/uploads/" . $data[0]->image_url) }}">
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="short_description" class="form-lable text-uppercase">Short Description <span class="text-danger">*</span></label>
                            <textarea id="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="5" name="short_description">{{ $data[0]->sub_description }}</textarea>
                            @error('short_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="full_description" class="form-lable text-uppercase">Full Description<span class="text-danger">*</span></label>
                            <textarea id="full_description" class="form-control @error('full_description') is-invalid @enderror" rows="5" name="full_description">{{ $data[0]->description }}</textarea>
                            @error('full_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
        .create(document.querySelector('#full_description'), {
            height: '500px',
        })
        .catch(error => {
            console.error(error);
        });

        $('.dropify').dropify();

    });
</script>
@endpush
