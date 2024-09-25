<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('backend') }}/assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>
    <meta name="description" content="" />
    <!-- Page CSS -->
    @include('backend.partials.style')
    @vite(['resources/css/app.css', 'resources/js/app.js'])


  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Sidebar HTML -->
        @include('backend.partials.sideber')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navber HTML -->
          @include('backend.partials.navbar')
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            @yield('content')

            <!-- Footer -->
            @include('backend.partials.footer')

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>
      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    @include('backend.partials.script')
  </body>
</html>
