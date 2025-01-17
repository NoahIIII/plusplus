<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
@include('layouts.head')
<div id="content-page" class="content-page">
    <div class="container-fluid relative">
        <body>
            <section class="sign-in-page">
                <div class="container bg-white mt-5 p-0">
                    <div class="row no-gutters">
                        <div class="col-sm-6 align-self-center">
                            <div class="sign-in-from">
                                <h1 class="mb-0 dark-signin">{{ ___('Sign in') }}</h1>
                                <p>{{ ___('Enter your email address and password to access admin panel.') }}</p>
                                <form class="mt-4" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">{{ ___('Email address') }}</label>
                                        <input name="email" type="email"
                                            class="form-control @error('email')is-invalid @enderror @if (session('error')) is-invalid @endif mb-0"
                                            id="exampleInputEmail1" placeholder="{{ ___('Enter email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                        @if (session('error'))
                                            <div class="invalid-feedback">
                                                {{ session('error') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">{{ ___('Password') }}</label>
                                        {{-- <a href="#" class="float-right">Forgot password?</a> --}}
                                        <input name="password" type="password"
                                            class="form-control @error('password')is-invalid @enderror mb-0"
                                            id="exampleInputPassword1" placeholder="{{ ___('Enter Password') }}">
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="d-inline-block w-100">
                                        <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label"
                                                for="customCheck1">{{ ___('Remember Me') }}</label>
                                        </div>
                                        <button type="submit"
                                            class="btn btn-primary float-right">{{ ___('Sign in') }}</button>
                                    </div>
                                    <div class="sign-info">
                                        <ul class="iq-social-media">
                                            <li><a href="#"><i class="ri-facebook-box-line"></i></a></li>
                                            <li><a href="#"><i class="ri-twitter-line"></i></a></li>
                                            <li><a href="#"><i class="ri-instagram-line"></i></a></li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- <div class="col-sm-6 text-center">
                <div class="sign-in-detail text-white">
                    <a class="sign-in-logo mb-5" href="#"><img src="images/logo-white.png" class="img-fluid" alt="logo"></a>
                    <div class="slick-slider11" data-autoplay="true" data-loop="true" data-nav="false" data-dots="true" data-items="1" data-items-laptop="1" data-items-tab="1" data-items-mobile="1" data-items-mobile-sm="1" data-margin="0">
                        <div class="item">
                            <img src="images/login/1.png" class="img-fluid mb-4" alt="logo">
                            <h4 class="mb-1 text-white">Manage your orders</h4>
                            <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                        </div>
                        <div class="item">
                            <img src="images/login/1.png" class="img-fluid mb-4" alt="logo">
                            <h4 class="mb-1 text-white">Manage your orders</h4>
                            <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                        </div>
                        <div class="item">
                            <img src="images/login/1.png" class="img-fluid mb-4" alt="logo">
                            <h4 class="mb-1 text-white">Manage your orders</h4>
                            <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                        </div>
                    </div>
                </div>
            </div> --}}
                    </div>
                </div>
            </section>
    </div>
</div>
@include('layouts.footer')
</body>

</html>
