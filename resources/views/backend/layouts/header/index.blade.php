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
                    <h4 class="card-title">Media Header Section</h4>
                    <form action="{{ route('cms.media-page.header.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <input type="hidden" name="id" value="10">
                        <div class="form-group mb-3">
                            <label for="title" class="form-lable text-uppercase">Title <span class="text-danger">*</span></span></label>
                            <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $data[9]->title }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="image" class="form-lable text-uppercase">Image  <small>(max-size-3MB)</small></label>
                            <input type="file" id="image" class="form-control dropify" name="image_url"  data-default-file="{{ $data[9]->image_url ? asset('storage/header/'.$data[9]->image_url) : asset('backend/images/image_placeholder.png') }}">
                            <input type="hidden" name="old_image_url" value="{{ $data[9]->image_url }}">
                            @error('image_url')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update</button>
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
