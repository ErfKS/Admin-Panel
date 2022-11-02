<div class="form-group">
    @php
        $id=Illuminate\Support\Str::random(16);
    @endphp
    <label for="{{$id}}">
        @isset($attributes['required']) @if($attributes['required'])<span class="text-danger">*</span>@endif @endisset {!! $attributes['label'] !!}</label>

    <select id="{{$id}}" name="{!! $attributes['name'] !!}" class="form-control">
        @foreach($attributes['options'] as $optionName => $optionValue)
            <option value="{!! $optionValue !!}">{!! $optionName !!}</option>
        @endforeach
    </select>
    @isset($attributes['description'])
        <small class="form-text text-muted">{!! $attributes['description'] !!}</small>
    @endisset
    @isset($errors)
        @error($attributes['name'])
            <div dir="rtl" class="alert alert-danger">{{ $message }}</div>
        @enderror
    @endisset
</div>

