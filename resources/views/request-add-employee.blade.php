@extends('home.ar.layout.wrapper')

@section('content')

<!-- رأس الموقع الرئيسي -->
<header class="main-header py-3 bg-light mt-5">
    {{ app()->setLocale(session('locale')) }}
    <div class="container">

    </div>
</header>

<!-- قسم العنوان -->
<section class="page-title page-title--small align-left"
         style="background-image: url({{ asset('home/images/bg-about.png') }}); background-size: cover; background-position: center;">
    <div class="container">
        <div class="page-title__content text-center text-white py-5">
            <h1 class="page-title__name">{{ __('Request form title') }}</h1>
            <p class="page-title__slogan">{{ __('Form description') }}</p>
            <div class="mt-3">
                <a href="{{ route('locale.change', 'en') }}" class="btn btn-outline-light btn-sm">{{ __('English') }}</a>
                <a href="{{ route('locale.change', 'ar') }}" class="btn btn-outline-light btn-sm">{{ __('العربية') }}</a>
            </div>
        </div>
    </div>
</section>



<!-- قسم الفورم -->
<section class="contact-main site-main mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-lg" style="background-color: #7613fb; color: #ffffff; border: 2px solid #7613fb;">
                    <div class="card-header background-color: #7613fb; text-white text-center">
                        <h3 style="color: #ffffff;  border: 2px solid #7613fb;">{{ __('Request form title') }}</h3>
                    </div>
                    <div class="card-body bg-light">
                        @if(session('success'))
                            <div class="alert alert-success text-center" style="background-color: #2ecc71; color: #ffffff;">
                                {{ __('Success Message') }}
                            </div>
                        @endif
                        <form action="{{ route('employee.request.store', $user_id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                       placeholder="{{ __('Name placeholder') }}" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                       placeholder="{{ __('Email placeholder') }}" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('employee.phone') }}</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                       placeholder="{{ __('Phone placeholder') }}" value="{{ old('phone') }}" required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" name="user_id" value="{{ $user_id }}">
                            <input type="hidden" name="number_of_hours_per_day" value="8">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection
