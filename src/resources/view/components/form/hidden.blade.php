@php
    $id=Illuminate\Support\Str::random(16);
@endphp
<input type="{{$attributes['type']}}" class="form-control" id="{{$id}}" name="{!! $attributes['name'] !!}" value="{{$attributes['text']}}">
