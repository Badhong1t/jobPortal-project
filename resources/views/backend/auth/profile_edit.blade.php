@extends('backend.app')

@section('content')
@push('style')
@endpush
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Profile Infomation</h4>
                    <p class="card-description">Setup your Profile Information, please <code> provide your valid data</code>.</p>
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-lable text-uppercase">Name</label>
                            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ Auth::user()->name }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="content" class="form-lable text-uppercase">Email</label>
                           <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('script')

@endpush
