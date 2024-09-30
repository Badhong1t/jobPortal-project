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
                    <h4 class="card-title">Edit Services</h4>
                    <p class="card-description">Update your Services , please <code> provide your valid data</code>.</p>
                    <form action="{{ route('services.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="service_name" class="form-lable text-uppercase">Service Name <span class="text-danger">*</span></label>
                            <input type="text" id="service_name" class="form-control @error('service_name') is-invalid @enderror" name="service_name" value="{{ $data->service_name }}">
                            @error('service_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="form-lable text-uppercase">Description</label>
                            <textarea id="description" class="form-control" rows="5" name="description">{{ $data->description }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="icon_path" class="form-lable text-uppercase">Icon <small>(PNG,SVG, Max: 3MB)</small></label>
                            <input type="file" id="icon_path" class="form-control dropify @error('icon_path') is-invalid @enderror" name="icon_path"  data-default-file="{{ $data->icon_path ? asset('storage/services_icons/'.$data->icon_path) : asset('backend/images/image_placeholder.png') }}">
                            <input type="hidden" name="old_icon_path" value="{{ $data->icon_path }}">
                            @error('icon_path')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('services.index') }}" class="btn btn-danger">Cancel</a>
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
    });
    $('.dropify').dropify();
</script>
@endpush
