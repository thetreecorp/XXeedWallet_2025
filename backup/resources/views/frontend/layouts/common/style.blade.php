
<script src="{{ asset('public/frontend/templates/js/flashesh-dark.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('public/dist/libraries/bootstrap-5.0.2/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontend/templates/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontend/templates/css/owl-css/owl.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/plugins/select2-4.1.0-rc.0/css/select2.min.css') }}">
<link rel="shortcut icon" href="{{ faviconPath() }}" />

@yield('styles')

<style>
    .navbar-brand img.img-none {
        display: none;
    }
    .top-banner {
        position: relative;
        overflow: hidden;
    }
    .button-widths-custom {
        width: 280px;
    }
    .gtco-testimonials .card p {
        margin-top: 20px;
        
    }
</style>