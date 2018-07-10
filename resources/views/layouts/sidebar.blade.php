<nav id="sidebar">
@guest
        
@else
 <!-- Sidebar Navigation-->
 
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar"></div>
        <div class="title">
        <h1 class="h5">{{ auth()->user()->name }}</h1>
        
        </div>
    </div>
    <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
    <ul class="list-unstyled">
            <li class="nav-item">
                <a class="nav-link" href="dashboard">
                <i class="icon-home"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="home">
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="package">
                    Packages
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="stacking">
                    Stacking
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('transaction') }}">
                    Transactions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('affiliate') }}">
                    Affiliate
                </a>
            </li>
            <!-- <li><a href="tables.html"> <i class="icon-grid"></i>Tables </a></li>
            <li><a href="charts.html"> <i class="fa fa-bar-chart"></i>Charts </a></li>
            <li><a href="forms.html"> <i class="icon-padnote"></i>Forms </a></li>
            <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Example dropdown </a>
                <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                <li><a href="#">Page</a></li>
                <li><a href="#">Page</a></li>
                <li><a href="#">Page</a></li>
                </ul>
            </li>
            <li><a href="login.html"> <i class="icon-logout"></i>Login page </a></li> -->
    
    
   
      <!-- Sidebar Navigation end-->
@endguest
</nav>