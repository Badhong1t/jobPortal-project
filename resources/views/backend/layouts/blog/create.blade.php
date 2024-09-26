@extends('backend.app')

@section('content')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
                    <h4 class="card-title">Post New Blog</h4>
                    <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="title" class="form-lable text-uppercase">Title <span class="text-danger">*</span></span></label>
                            <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Post Title" value="{{ old('title') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="image" class="form-lable text-uppercase">Image <span class="text-danger">*</span>(max-size-3MB)</label>
                            <input type="file" id="image" class="form-control dropify" name="image"  data-default-file="{{ asset('backend/images/image_placeholder.png') }}">
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="dascption" class="form-lable text-uppercase">Description <span class="text-danger">*</span></label>
                            <textarea id="description" class="form-control" rows="5" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-lable text-uppercase">Tags <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="tags" name="tags[]" multiple>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ collect(old('tags'))->contains($tag->id) ? 'selected' : '' }}> {{ $tag->name }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-lable">Youtube Video link:</label>
                            <input type="text" class="form-control @error('youtube_link') is-invalid @enderror" id="name" name="youtube_link" value="{{ old('youtube_link') }}">
                            @error('youtube_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-lable">Meta Title:</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="name" name="meta_title" value="{{ old('meta_title') }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-lable">Meta Keyword:</label>
                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="name" name="meta_keywords" value="{{ old('meta_keywords') }}">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col">
                                <label class="form-lable">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" id="description1">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="required">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror select2" required>
                                @php($status = old('status', isset($data) ? $data->status : ''))
                                @foreach (['Active', 'Inactive'] as $sts)
                                    <option value="{{ $sts }}" {{ $status == $sts ? 'selected' : '' }}>
                                        {{ $sts }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('blog.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
        .create(document.querySelector('#description'), {
            height: '500px',
        })
        .catch(error => {
            console.error(error);
        });
        ClassicEditor
        .create(document.querySelector('#description1'), {
            height: '500px',
        })
        .catch(error => {
            console.error(error);
        });

        $('.dropify').dropify();

        $(document).ready(function() {
            $('.select2').select2();
        });
    });
</script>
@endpush
