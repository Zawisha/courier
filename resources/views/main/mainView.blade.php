@extends('layouts.main')

@section('content')
   <div class="d-flex justify-content-center align-items-center cust_main_form">
      <div class="text-center">
         @guest
            <div><a href="{{ url('/register') }}" class="btn btn-primary btn-custom my-2 btn-custom ">Регистрация</a></div>
            <div><a href="{{ url('/login') }}" class="btn btn-primary btn-custom my-2 btn-custom ">Войти</a></div>
         @endguest
         @can('view page')
            <div><a href="{{ url('/users-list') }}" class="btn btn-primary btn-custom my-2 btn-custom ">Список курьеров</a></div>
         @endcan
         @auth
            <div>
               <form id="logout-form" action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-primary btn-custom my-2 btn-custom ">Выйти</button>
               </form>
            </div>
         @endauth
      </div>
   </div>
@endsection
