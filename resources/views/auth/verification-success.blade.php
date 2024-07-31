@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Email Verification') }}</div>

                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        {{ $message }}
                    </div>
                    <a href="{{ route('login') }}" class="btn btn-primary">{{ __('Go to Login') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection