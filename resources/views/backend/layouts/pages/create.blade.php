@extends('backend.app')

@section('content')
@push('style')
    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 150px;
        }
    </style>
@endpush

<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Dynamic Pages</h4>
                    <p class="card-description">Setup your dynamic page, please <code> provide your valid data</code>.</p>
                    <form action="{{ route('dynamic_page.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="title" class="form-lable text-uppercase">Page Title:</label>
                            <input type="text" id="title" class="form-control @error('page_title') is-invalid @enderror" name="page_title" placeholder="Page Title" value="{{ old('page_title') }}">
                            @error('page_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="content" class="form-lable text-uppercase">Page Content:</label>
                            <textarea id="content" class="form-control @error('page_content') is-invalid @enderror" placeholder="Page Content" rows="5" name="page_content">{{ old('page_content') }}</textarea>
                            @error('page_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('dynamic_page.index') }}" class="btn btn-danger">Cancel</a>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
            .create(document.querySelector('#content'), {
                height: '500px',
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
@endpush
