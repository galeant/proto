<!DOCTYPE html>
<!--
* CoreUI Free Laravel Bootstrap Admin Template
* @version v2.0.1
* @link https://coreui.io
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>CoreUI Free Bootstrap Admin Template</title>
    <link rel="manifest" href="assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icons-->
    <link href="{{ asset('css/free.min.css') }}" rel="stylesheet"> <!-- icons -->
    <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet"> <!-- icons -->
    <!-- Main styles for this application-->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/coreui-chartjs.css') }}" rel="stylesheet">
    <style>
    .loading-spinner {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid black;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        display: block;
        margin: 0 auto;
    }
    @keyframes spin {
        from {
            transform:rotate(0deg);
        }
        to {
            transform:rotate(360deg);
        }
    }

    </style>
    @yield('css')
  </head>



  <body class="c-app">
    <div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">

      @include('layouts.nav-builder')

      @include('layouts.header')

      <div class="c-body">

        <main class="c-main pt-3">

          @yield('content')

        </main>
        {{-- @include('layouts.footer') --}}
      </div>
    </div>



    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('js/coreui.bundle.min.js') }}"></script>
    <script src="{{ asset('js/coreui-utils.js') }}"></script>
    <script src="{{ asset('js/fontawesome.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('javascript')




  </body>
</html>
