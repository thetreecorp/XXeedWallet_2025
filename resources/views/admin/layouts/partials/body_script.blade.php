<script src="{{ asset('/dist/js/popper.min.js') }}" type="text/javascript"></script>
<!-- Bootstrap 5.0.2 -->
<script src="{{ asset('/dist/libraries/bootstrap-5.0.2/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!-- Select2 -->
<script src="{{ asset('/dist/plugins/select2-4.1.0-rc.0/js/select2.full.min.js') }}" type="text/javascript"></script>
<!-- moment -->
<script src="{{ asset('/dist/js/moment.min.js') }}" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/admin/templates/js/app.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    "use strict";
    var url = "{{ url('change-lang') }}";
    var token = "{{ csrf_token() }}";
 </script>
<script src="{{ asset('/admin/customs/js/body_script.min.js') }}"></script>
@yield('body_script')
