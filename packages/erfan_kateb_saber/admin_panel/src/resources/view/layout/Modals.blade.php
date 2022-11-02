<div class="modal @if($isShow) show @endif" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{!!$title!!}</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">{!!$text!!}</div>
            <div class="modal-footer">
                <button class="btn btn-seccess" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>


@if($isShow)
    <script type="text/javascript">
        $('#myModal').modal('show');
        $('#myModal').modal({ show: false})
    </script>
@endif
