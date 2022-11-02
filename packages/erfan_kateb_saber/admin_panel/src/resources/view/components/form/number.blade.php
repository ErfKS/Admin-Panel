<div class="form-group">
    @php
        $id=Illuminate\Support\Str::random(16);
    @endphp
    <label for="{{$id}}">
        @isset($attributes['required'])<span class="text-danger">*</span>@endisset {!! $attributes['label'] !!}</label>
    <input type="{{$attributes['type']}}" class="form-control" id="{{$id}}"
           @isset($attributes['placeholder']) placeholder="{!! $attributes['placeholder'] !!}"
           @endisset name="{!! $attributes['name'] !!}" value="{{old($attributes['name'])}}">
    @isset($attributes['description'])
        <small class="form-text text-muted">{!! $attributes['description'] !!}</small>
    @endisset
    @isset($errors)
        @error($attributes['name'])
            <div dir="rtl" class="alert alert-danger">{{ $message }}</div>
        @enderror
    @endisset
</div>
