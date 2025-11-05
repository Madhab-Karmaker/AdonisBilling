@extends('layouts.app')
@section('content')
<div class="container"> 
    <div class="row justify-content-center"> 
        <div class="col-md-8">  
            <div class="card"> 
                <div class="card-header">{{ __('Add New Staff') }}</div> 
                <div class="card-body"> 
                    <form method="POST" action="{{ route('manager.staffs.store') }}"> 
                        @csrf 

                        <div class="form-group      mb-3"> 
                            <label for="name" class="form-label">{{ __('Name') }}</label> 
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required> 
                            @error('name') 
                                <span class="invalid-feedback" role="alert"> 
                                    <strong>{{ $message }}</strong> 
                                </span> 
                            @enderror 
                        </div>  
                        <div class="form-group mb-3"> 
                            <label for="email" class="form-label">{{ __('Email') }}</label> 
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required> 
                            @error('email') 
                                <span class="invalid-feedback" role="alert"> 
                                    <strong>{{ $message }}</strong> 
                                </span> 
                            @enderror
                        </div>
                        <div class="form-group mb-3"> 
                            <label for="phone" class="form-label">{{ __('Phone') }}</label> 
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required> 
                            @error('phone') 
                                <span class="invalid-feedback" role="alert"> 
                                    <strong>{{ $message }}</strong> 
                                </span> 
                            @enderror   
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Add Staff') }}</button>
                    </form>
                </div>
            </div>      
        </div>
    </div>
</div>
@endsection