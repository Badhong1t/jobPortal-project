@extends('backend.app')

@section('content')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">

@endpush

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Gallery Image</h4>
                    <form action="{{ route('gallery_image.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <input type="hidden" name="id" value="{{ $gallery_image->id }}">
                        <div class="form-group mb-3">
                            <label for="title" class="form-lable text-uppercase">Title</label>
                            <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $gallery_image->title }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="image" class="form-lable text-uppercase">Gallery Image <span class="text-danger">*</span>  <small>(max-size-3MB)</small></label>
                            <input type="file" id="image" class="form-control dropify" name="image"  data-default-file="{{ $gallery_image->image ? asset('storage/gallery/'.$gallery_image->image) : asset('backend/images/image_placeholder.png') }}">
                            <input type="hidden" name="old_image" value="{{ $gallery_image->image }}">
                            @error('image')
                                <div class="text-danger mb-3">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('gallery_image.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
<script>

    $('.dropify').dropify();

</script>
@endpush
