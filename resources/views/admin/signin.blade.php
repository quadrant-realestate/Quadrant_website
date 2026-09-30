<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="{{ asset('public/favicon.png') }}" />

  <!-- Core Css -->
  <link rel="stylesheet" href="{{ asset('public/adminassets/css/style.css') }}" />

  <title>Quadrant | Dashboard</title>
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="{{ asset('public/favicon.png') }}" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper">
    <div class="position-relative overflow-hidden auth-bg min-vh-100 w-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100 my-5 my-xl-0">
          <div class="col-md-9 d-flex flex-column justify-content-center">
            <div class="card mb-0 bg-body auth-login m-auto w-100">
              <div class="row gx-0">
                <!-- ------------------------------------------------- -->
                <!-- Part 1 -->
                <!-- ------------------------------------------------- -->
                <div class="col-xl-12">
                  <div class="row justify-content-center py-4">
                    <div class="col-lg-11">
                      <div class="card-body">
                        <a href="{{ URL::to('') }}/adminadmin/sign-in" class="text-nowrap logo-img d-block mb-4 w-100 text-center">
                          <img src="{{ asset('public/logo-quadrant.png') }}" class="dark-logo" alt="Logo-Dark" style="width:25%" />
                        </a>
                        <h2 class="lh-base mb-4">Let's get you signed in</h2>
                        
                        <form action="{{ route('signinrequest') }}" method="POST">
                          @csrf
                          @if(session('noerrors'))
                            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                              {{ session('noerrors') }}
                            </div>                            
                        @endif
                          <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email Address</label>
                            <input type="email" class="form-control"  name="email_address" id="exampleInputEmail1" placeholder="Enter your email" aria-describedby="emailHelp">
                          </div>
                          <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                              <label for="exampleInputPassword1" class="form-label">Password</label>
                              <!-- <a class="text-primary link-dark fs-2" href="../main/authentication-forgot-password2.html">Forgot
                                Password ?</a> -->
                            </div>
                            <input type="password" class="form-control" id="exampleInputPassword1"  name="password" placeholder="Enter your password">
                          </div>
                          <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check">
                              <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                              <label class="form-check-label text-dark" for="flexCheckChecked">
                                Keep me logged in
                              </label>
                            </div>
                          </div>
                          <button type="submit" class="btn btn-dark w-100 py-8 mb-4 rounded-1">Sign In</button>
                          <!-- <div class="d-flex align-items-center">
                            <p class="fs-12 mb-0 fw-medium">Don’t have an account yet?</p>
                            <a class="text-primary fw-bolder ms-2" href="../main/authentication-register2.html">Sign Up Now</a>
                          </div> -->
                        </form>
                      </div>
                    </div>
                  </div>

                </div>
               
               
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="dark-transparent sidebartoggler"></div>
  <!-- Import Js Files -->
  <script src="{{ asset('public/adminassets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('public/adminassets/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('public/adminassets/js/app.init.js') }}"></script>
  <script src="{{ asset('public/adminassets/js/theme.js') }}"></script>
  <script src="{{ asset('public/adminassets/js/app.min.js') }}"></script>

  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>