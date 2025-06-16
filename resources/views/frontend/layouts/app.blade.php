<!DOCTYPE html>
<html lang="en" class="scrol-pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ isset($exceptionMeta) ? $exceptionMeta->description : meta(Route::current()->uri(), 'description') }}">
    <meta name="keywords" content="{{ isset($exceptionMeta) ? $exceptionMeta->keywords : meta(Route::current()->uri(), 'keywords') }}">
    <title>{{ isset($exceptionMeta) ? $exceptionMeta->title : meta(Route::current()->uri(), 'title') }}<?= isset($additionalTitle) ? ' | '.$additionalTitle : '' ?></title>
    @include('frontend.layouts.common.style')

    <script type="text/javascript">
        var SITE_URL = "{{ url('/') }}";
        var svg = `
          <svg width="50" height="50" viewBox="0 0 50 50">
            <circle cx="25" cy="25" r="20" fill="none" stroke-width="2" stroke="#fff" />
          </svg>
        `;
    </script>
    
    <style>
        .loading-overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.5);
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 1000;
        }
        
        .loading-overlay svg {
          width: 50px;
          height: 50px;
          animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
          0% {
            transform: rotate(0deg);
          }
          100% {
            transform: rotate(360deg);
          }
        }
        
        .loading-overlay svg circle {
          stroke-dasharray: 100;
          stroke-dashoffset: 0;
          animation: dash 1s linear infinite;
        }
        
        @keyframes dash {
          0% {
            stroke-dashoffset: 0;
          }
          100% {
            stroke-dashoffset: 100;
          }
        }
        @media (max-width: 767px) {
          .desktop-mobile-view {
            display: none
          }
          .center-mb-view {
            text-align: center;
          }
          .center-mb-view .small-border.bgd-blue,
          .center-mb-view .leading-26,
          .center-mb-view .learn-btn {
            margin: 0 auto;
          }
          .center-mb-view .mb-32 .d-flex,
          .center-mb-view .mt-161 .d-flex {
            display: block !important;
          }
    
        }
    </style>

</head>
<body>
	
     <!-- Start scroll-top button -->
    <div id="scroll-top-area">
        <a href="{{url()->current()}}#top-header"><i class="fas fa-arrow-up"></i></a>
    </div>
    <!-- End scroll-top button -->
    
    <!-- Start Header -->
    @include('frontend.layouts.common.header')
    <!-- End Header -->

    @yield('content')

    <!-- Start Footer-->
    @include('frontend.layouts.common.footer_menu')
    <!-- End Footer -->

    @include('frontend.layouts.common.script')

    @yield('js')
</body>
</html>