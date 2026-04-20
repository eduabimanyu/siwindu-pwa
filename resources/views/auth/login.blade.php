
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; SIWINDU POS</title>
  <link rel="icon" href="{{ $setting->path_logo}}">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{ asset('template') }}/node_modules/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('template') }}/assets/css/style.css">
  <link rel="stylesheet" href="{{ asset('template') }}/assets/css/components.css">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="d-flex flex-wrap align-items-stretch">
        {{-- <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white"> --}}
         <div class="container mt-2">
         <div class="col-12 col-sm-12 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 bg-white">
          <div class="p-4 m-3">
            <img src="{{ $setting->path_logo}}" alt="logo" width="90" class="shadow-light rounded-circle mb-5 mt-2">
            <h4 class="text-dark font-weight-normal"> Selamat Datang Di <span class="font-weight-bold">SIWINDU POS</span></h4>
            <p class="text-muted">Siwindu Poin Of Sales</p>
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <form method="POST" action="{{ route('login') }}" >
                @csrf
              <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control" :value="old('email')" name="email" tabindex="1" required autofocus>
                <div class="invalid-feedback">
                  Please fill in your email
                </div>
              </div>

              <div class="form-group">
                <div class="d-block">
                  <label for="password" class="control-label">Password</label>
                </div>
                <input id="password" type="password" class="form-control" name="password" tabindex="2" required autocomplete="current-password" >
                <div class="invalid-feedback">
                  please fill in your password
                </div>
              </div>

              {{-- <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                  <label class="custom-control-label" for="remember-me">Remember Me</label>
                </div>
              </div> --}}

              <div class="form-group text-right">
                @if (Route::has('password.request')) 
                <a href="{{ route('password.request') }}" class="float-left mt-3">
                  Lupa Password?
                </a>
                @endif
                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                  Login
                </button>
              </div>
            </form>

            <div class="text-center mt-5 text-small">
              Copyright &copy; Siwindu
            </div>
          </div>
        </div>
        {{-- <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom" data-background="{{ asset('template') }}/assets/img/unsplash/login-bg2.jpg">
          <div class="absolute-bottom-left index-2"> --}}
          </div>
        </div>
      </div>
    </section>
  </div>

  @include('layouts.js')
</body>
</html>
