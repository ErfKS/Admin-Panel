<script src="{{asset('vendor/admin_panel/js/scroll_manager.js')}}"></script>
@isset($errors)
    @if($errors->any() || isset($always_work))
        <script>
            $(window).on('load', function() {
                Scroll_manager.getScroll();
            });
        </script>
    @endif
@endisset
