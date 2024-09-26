@extends('backend.app')

@section('content')
@push('style')
@endpush
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Stripe Setting</h4>
                    <p class="card-description">Setup your Stripe, please <code>provide your valid data</code>.</p>
                    <div class="mt-4">
                        <form action="{{ route('stripe.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="my-input">STRIPE KEY</label>
                                <input type="text" class="form-control form-control-md border-left-0 @error('stripe_key') is-invalid @enderror" placeholder="STRIPE KEY" name="stripe_key" value="{{ env('STRIPE_KEY') }}">
                                @error('stripe_key')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="my-input">STRIPE SECRET</label>
                                <input type="text" class="form-control form-control-md border-left-0  @error('stripe_secret') is-invalid @enderror" placeholder="STRIPE SECRET" name="stripe_secret" value="{{ env('STRIPE_SECRET') }}">
                                @error('stripe_secret')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
@endpush
