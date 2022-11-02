<div class="form-check">
    @php
        $id=Illuminate\Support\Str::random(16);
    @endphp
    <input type="{{$attributes['type']}}" class="form-check-input" id="{{$id}}"
           name="{!! $attributes['name'] !!}" @if(old($attributes['name']) == "on") checked @endif>
    <label class="form-check-label" for="{{$id}}">
        @if($attributes['required'])<span class="text-danger">*</span>@endif {!! $attributes['label'] !!}</label>
    @if($attributes['description'])
        <small class="form-text text-muted">{!! $attributes['description'] !!}</small>
    @endif
    @isset($errors)
        @error($attributes['name'])
            <div dir="rtl" class="alert alert-danger">{{ $message }}</div>
        @enderror
    @endisset
</div>
