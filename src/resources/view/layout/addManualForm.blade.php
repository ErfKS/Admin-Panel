<form class="container" method="{{($method=='get') ? $method : 'post'}}" action="{{$action}}">
    @if('csrf')
        @csrf
    @endif
    @if($method != 'post' && $method != 'get')
        @method(strtoupper($method))
    @endif
    @isset($inputs)
        @include('layout::FormBody',['inputs'=>$inputs])
    @endisset
    @isset($submit_button)
        <button type="submit" class="btn btn-{!! $submit_button['color'] !!}">{!! $submit_button['text'] !!}</button>
    @endisset
</form>
