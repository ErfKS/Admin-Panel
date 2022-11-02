<div class="form-check">
    @php
        $id=Illuminate\Support\Str::random(16);
    @endphp
    <button type="button" onclick="{!! $attributes['onclick'] !!}" class="btn btn-{{$attributes['color']}}" id="{{$id}}" name="{!! $attributes['name'] !!}">{{$attributes['text']}}</button>
    <label class="form-check-label" for="{{$id}}">
    @if($attributes['description'])
        <small class="form-text text-muted">{!! $attributes['description'] !!}</small>
    @endif
    @isset($errors)
        @error($attributes['name'])
            <div dir="rtl" class="alert alert-danger">{{ $message }}</div>
        @enderror
    @endisset
</div>
