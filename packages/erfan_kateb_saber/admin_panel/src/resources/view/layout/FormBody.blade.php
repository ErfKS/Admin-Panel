@foreach($inputs as $label => $properties)
    @php
        $id=Illuminate\Support\Str::random(16);
    @endphp
    @switch($properties['type'])
        @case('checkbox')
            <x-form-checkbox :type="$properties['type']" :name="$properties['name']"
                             :required="isset($properties['required'])??null" :label="$properties['label']"
                             :description="isset($properties['description'])??null"/>
            @break
        @case('row')
            <label for="{{$id}}"><h1>{{$properties['text']}}</h1></label>
            <div id="{{$id}}" class="form-row" style="place-items: center">
                @unset($properties['type'])
                @unset($properties['text'])
                @include('layout::FormInnerBody',['inputs'=>$properties['inputs']])
            </div>
            @break
        @case('text')
            <x-form-text :type="$properties['type']" :name="$properties['name']"
                         :required="isset($properties['required'])??null" :label="$properties['label']"
                         :description="isset($properties['description'])??null"/>
            @break
        @case('password')
            <x-form-text :type="$properties['type']" :name="$properties['name']"
                         :required="isset($properties['required'])??null" :label="$properties['label']"
                         :description="isset($properties['description'])??null"/>
            @break
        @case('dropdown')
            <x-form-dropdown :type="$properties['type']" :name="$properties['name']"
                             :required="isset($properties['required'])??null" :label="$properties['label']"
                             :description="isset($properties['description'])??null" :options="$properties['options']"/>
            @break
        @case('hr')
            <hr>
            @break;
        @case('br')
            <br>
            @break;
        @case('button')
            <x-form-button :type="$properties['type']" :name="$properties['name']"
                           :label="$properties['text']" :description="isset($properties['description'])??null"
                           :text="$properties['text']" :color="$properties['color']" :onclick="$properties['onClick']??''"/>
            @break
        @case('number')
            <x-form-text :type="$properties['type']" :name="$properties['name']"
                         :required="isset($properties['required'])??null" :label="$properties['label']"
                         :description="isset($properties['description'])??null"/>
            @break
        @case('listBox')
            @php
                $properties['tableCount'] = [
                    'type'=>'hidden',
                    'name'=>'tableCount',
                    'text'=>$properties['item_count']
                ];
            @endphp
            @include('component::form.listbox',[
                'attributes'=>[
                    'id'=>$id,
                    'text'=>$properties['text'],
                    'create_item_button'=>$properties['create_item_button'],
                    'one_item'=>$properties['one_item'],
                    'count'=> $properties['tableCount'],
                    'item_count' => $properties['item_count']
                ]
            ])
            @break

        @case('hidden')
            <x-form-hidden :type="$properties['type']" :name="$properties['name']" :text="$properties['text']"/>
            @break
    @endswitch
@endforeach
