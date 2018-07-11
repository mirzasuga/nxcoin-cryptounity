<!DOCTYPE html>
<html>
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="/vendor/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="/vendor/vendor/font-awesome/css/font-awesome.min.css">
    <!-- Custom Font Icons CSS-->
    <link rel="stylesheet" href="/vendor/css/font.css">
    <!-- Google fonts - Muli-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="/vendor/css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="/vendor/css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="/favicon.ico">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="/vendor/https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="/vendor/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <header class="header">   
      <nav class="navbar navbar-expand-lg">
        <div class="search-panel">
          <div class="search-inner d-flex align-items-center justify-content-center">
            <div class="close-btn">Close <i class="fa fa-close"></i></div>
            <form id="searchForm" action="#">
              <div class="form-group">
                <input type="search" name="search" placeholder="What are you searching for...">
                <button type="submit" class="submit">Search</button>
              </div>
            </form>
          </div>
        </div>
        <div class="container-fluid d-flex align-items-center justify-content-between">
          <div class="navbar-header">
            <!-- Navbar Header--><a href="http://cryptounity.co" class="navbar-brand">
              <div class="brand-text brand-big visible text-uppercase"><img src="https://cryptounity.co/img/logo.png" alt="" srcset=""></div>
              <div class="brand-text brand-sm"><strong class="text-primary">C</strong><strong>U</strong></div></a>
            <!-- Sidebar Toggle Btn-->
            <button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
          </div>
          <div class="right-menu list-inline no-margin-bottom">    
            <div class="list-inline-item"><a href="#" class="search-open nav-link"><i class="icon-magnifying-glass-browser"></i></a></div>
            @guest
                <div class="list-inline-item dropdown">
                    <a id="languages" rel="nofollow" href="{{ route('login') }}" class="nav-link language">
                        <span class="d-none d-sm-inline-block">Login</span>
                    </a>
                </div>
                <div class="list-inline-item dropdown">
                    <a id="languages" rel="nofollow" href="{{ route('register') }}" class="nav-link language">
                        <span class="d-none d-sm-inline-block">Register</span>
                    </a>
                </div>
            @else
            <!-- Languages dropdown    -->
            <div class="list-inline-item dropdown">
                <a id="languages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle">
                    
                    <span class="d-none d-sm-inline-block">{{ auth()->user()->name }}</span>
                </a>
                <div aria-labelledby="languages" class="dropdown-menu">
                    <a rel="nofollow" href="{{ route('profile') }}" class="dropdown-item">
                        <span>Profile</span>
                    </a>
                    <a rel="nofollow" href="{{ route('wallet') }}" class="dropdown-item">
                        <span>Wallet</span>
                    </a>
                    
                </div>
            </div>
            <!-- Log out               -->
            <div class="list-inline-item logout">
                <a id="logout" href="login.html" class="nav-link">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <i class="icon-logout"></i>
                </a>
                
            </div>
            @endguest
          </div>
        </div>
      </nav>
    </header>
    <div class="d-flex align-items-stretch">
     @include('layouts.sidebar')
      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">{{ config('app.name', 'Laravel') }}</h2>
          </div>
        </div>
        
        @yield('content')

        <footer class="footer">
          <div class="footer__block block no-margin-bottom">
            <div class="container-fluid text-center">
              <!-- Please do not remove the backlink to us unless you support us at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
              <p class="no-margin-bottom">2018 &copy; Your company. Design by <a href="#">{{ config('app.name', 'Laravel') }}</a>.</p>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="/vendor/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="/vendor/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/vendor/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="/vendor/vendor/chart.js/Chart.min.js"></script>
    <script src="/vendor/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="/vendor/js/charts-home.js"></script>
    <script src="/vendor/js/front.js"></script>
  </body>
</html>