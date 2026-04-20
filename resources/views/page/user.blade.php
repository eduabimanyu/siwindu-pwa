 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'User')
 @section('conten')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>User Pengguna</h1>
         <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item ">User</div>
        </div>
          </div>
        <!--  <div class="card">-->
        <!--    <div class="card-body">-->
        <!--      <ul class="nav nav-pills">-->
        <!--      <li class="nav-item">-->
        <!--        <a class="nav-link {{ request()->is('user') ? 'active' : '' }}" href="/user"><i class="fas fa-user"></i>  User</a>-->
        <!--      </li>-->
        <!--      <li class="nav-item">-->
        <!--        <a class="nav-link {{ request()->is('permission') ? 'active' : '' }} " href="/permission"><i class="fa fa-universal-access"></i>  Permission</a>-->
        <!--      </li>-->
        <!--      <li class="nav-item">-->
        <!--        <a class="nav-link {{ request()->is('userrol') ? 'active' : '' }} " href="/userrol"><i class="fas fa-code-branch"></i>  Roll</a>-->
        <!--      </li>-->
        <!--    </ul>-->
        <!--  </div>-->
        <!--</div>-->
        @yield('user')
        </section>
      </div>
  @endsection

