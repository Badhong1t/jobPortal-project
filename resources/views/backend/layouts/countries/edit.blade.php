@extends('backend.app')

@section('title', 'Edit Country')

@push('style')
{{-- dropify cdn --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css" integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
                        <h4 class="card-title">Edit Country</h4>
                        <div class="mt-4">
                            {{-- @dd($data) --}}
                            <form class="forms-sample" action="{{ route('backend.country.update', $data->id) }}"  enctype="multipart/form-data"
                                method="POST">
                                @csrf
                                @method('POST')
                                <div class="form-group mb-3">
                                    <label>Country Name:</label>
                                    <input type="text" class="form-control @error('country_name') is-invalid @enderror"
                                        id="country_name" name="country_name" value="{{ $data->country_name }}">
                                    @error('country_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable">Country Image</label>
                                        <input class="form-control dropify @error('country_image') is-invalid @enderror"
                                            type="file"
                                            data-default-file="{{ asset("storage/uploads/" . $data->country_image) }}"
                                            id="country_name" name="country_image">

                                        @error('country_image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <a href="{{ route('backend.country.index') }}" class="btn btn-danger ">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>

{{-- dropify cdn --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
            .create(document.querySelector('#content'), {
                height: '500px'
            })
            .catch(error => {
                console.error(error);
            });
    });

    $('.dropify').dropify();

</script>
@endpush
