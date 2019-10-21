@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        <div class="container">
       <div class="row">
         <div class="col-md-12 row-block">
          <a href="{{ url('auth/google') }}" class="btn btn-lg btn-primary btn-block">
          <strong>Login With Google</strong>
          </a> 
         </div>
       </div>
    </div>

            
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
