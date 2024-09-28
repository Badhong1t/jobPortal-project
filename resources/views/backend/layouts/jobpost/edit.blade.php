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
                    <h4 class="card-title">Jobpost Edit</h4>
                    <form action="{{ route('jobpost.update',$data->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="job_name" class="form-lable text-uppercase">Job Name <span class="text-danger">*</span></span></label>
                            <input type="text" id="job_name" class="form-control @error('job_name') is-invalid @enderror" name="job_name" value="{{ $data->job_name }}">
                            @error('job_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-lable text-uppercase">Company Name <span class="text-danger">*</span></label>
                            <select class="form-control @error('company_id') is-invalid @enderror" id="company_id" name="company_id">
                                <option>Select Company Name</option>
                                @foreach ($company as $row)
                                    <option value="{{ $row->id }}" @if($data->company_id == $row->id) selected @endif>{{ $row->company_name }}</option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="image" class="form-lable text-uppercase">Image <span class="text-danger">*</span>(max-size-3MB)</label>
                            <input type="file" id="image" class="form-control dropify" name="image"  data-default-file="{{ $data->image ? asset('storage/jobpost/'.$data->image) : asset('backend/images/image_placeholder.png') }}">
                            <input type="hidden" name="old_image" value="{{ $data->image }}">
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="job_details" class="form-lable text-uppercase">Job Details <span class="text-danger">*</span></label>
                            <textarea id="job_details" class="form-control @error('job_details') is-invalid @enderror" rows="5" name="job_details">{{ $data->job_details }}</textarea>
                            @error('job_details')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="short_description" class="form-lable text-uppercase">Short Description <span class="text-danger">*</span></label>
                            <textarea id="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="5" name="short_description">{{ $data->short_description }}</textarea>
                            @error('short_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-lable text-uppercase">Job Category <span class="text-danger">*</span></label>
                            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                <option>Select Job Category</option>
                                @foreach ($category as $cat)
                                    <option value="{{ $cat->id }}" @if($data->category_id == $cat->id) selected @endif>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-lable text-uppercase">Country <span class="text-danger">*</span></label>
                            <select class="form-control @error('country_id') is-invalid @enderror" id="country_id" name="country_id">
                                <option>Select Country</option>
                                @foreach ($country as $row)
                                    <option value="{{ $row->id }}" @if($data->country_id == $row->id) selected @endif>{{ $row->country_name }}</option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-lable">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ $data->address }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-lable">Salary</label>
                            <input type="text" class="form-control @error('salary') is-invalid @enderror" id="salary" name="salary" value="{{ $data->salary }}">
                            @error('salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-lable">Experience</label>
                            <input type="text" class="form-control @error('experience') is-invalid @enderror" id="experience" name="experience" value="{{ $data->experience }}">
                            @error('experience')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col">
                                <label class="form-lable">Deadline <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ $data->end_date }}">
                                @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('jobpost.index') }}" class="btn btn-danger">Cancel</a>
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
        .create(document.querySelector('#job_details'), {
            height: '500px',
        })
        .catch(error => {
            console.error(error);
        });

        $('.dropify').dropify();

    });
</script>
@endpush
