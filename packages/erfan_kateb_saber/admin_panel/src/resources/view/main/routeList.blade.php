@extends('layout::body')
@section('title')
    Route List
@endsection

@if($part=='databases')
    @php
        $tableName = str_replace('table-','',$mode);
        $primaryKeys = array();
        $uniqueKeys = array();
        $foreignKeys = array();

        function getColumnWithKeyName($table_name,&$column,$key_name){
            try{
                foreach (Illuminate\Support\Facades\DB::select("SHOW KEYS FROM `$table_name` WHERE Key_name = '$key_name'") as $primaryKey){
                    array_push($column,$primaryKey->Column_name);
                }
            } catch (Exception $ex){;}
        }

        getColumnWithKeyName($tableName,$primaryKeys,'PRIMARY');
        getColumnWithKeyName($tableName,$uniqueKeys,'%unique%');
        getColumnWithKeyName($tableName,$foreignKeys,'%unique%');
    @endphp
@endif

@section('content')
    @include('layout::nav',['navLinks'=>$navLinks])
    <h1 class="text-center">{!! $title !!}</h1>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th class="text-left">#</th>
                @foreach($head as $item)
                    @if($item === 'Status')
                        <th class="text-center">{{$item}}</th>
                        @continue
                    @endif
                    <th class="text-left">
                        @if($part=='databases')
                            @foreach($primaryKeys as $primaryKey)
                                @if($primaryKey === $item)<img style="width: 1.5rem" src="/admin_panel_resources/primaryKey.svg" alt="primary key">@endif
                            @endforeach

                            @foreach($uniqueKeys as $uniqueKey)
                                @if($uniqueKey === $item)<img style="width: 1.5rem" src="/admin_panel_resources/uniqueKey.svg" alt="unique key">@endif
                            @endforeach

                            @foreach($foreignKeys as $foreignKey)
                                @if($foreignKey === $item)<img style="width: 1.5rem" src="/admin_panel_resources/foreignKey.svg" alt="foreign key">@endif
                            @endforeach
                            <br>
                        @endif
                        {{$item}}
                    </th>
                @endforeach
                @if($mode === "manual-all" || $mode === "manual-prefix")
                    <th class="text-center">Drop</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @php $currntRow=0 @endphp
            @foreach($list as $key=> $item)

                @php $currntRow++ @endphp
                <tr>
                    <td class="text-left">{{$currntRow}}</td>
                        @switch($part)
                            @case('routes')
                                @switch($mode)
                                    @case('all')
                                        <td class="text-left path">{{$item['path']}}</td>
                                        @if(count((array)$item['name']) == 0)
                                            <td class="text-left name all"></td>
                                        @else
                                            <td class="text-left name all">{{$item['name']}}</td>
                                        @endif
                                        @if(count((array)$item['pref']) == 0)
                                            <td class="text-left pref all"></td>
                                        @else
                                        <td class="text-left pref all">{{$item['pref']}}</td>
                                        @endif
                                        <td class="text-left method all">{{$item['method']}}</td>
                                        <td class="text-left controller all">{{$item['controller']}}</td>
                                        <td class="text-center">
                                            <input type="checkbox" class="mb-1 checkbox-input status all" @if($item['status'] == "on") checked @endif name="status">
                                        </td>
                                        @break

                                    @case('manual-all')
                                        <td class="text-left path">{{$key}}</td>
                                        @isset($item['pref'])
                                        @if(count((array)$item['pref']) == 0 || $item['pref'] === "null")

                                            <td class="text-left pref all"></td>
                                        @else
                                            <td class="text-left pref all">{{$item['pref']}}</td>
                                        @endif
                                        @else
                                            <div class="text-left pref all"></div>
                                        @endif
                                        <td class="text-center">
                                            <input type="checkbox" class="mb-1 checkbox-input status all" @if($item['status'] == "on") checked @endif name="status">
                                        </td>
                                        <td class="text-center"><button class="btn btn-danger" onclick="dropManualPath('{{$mode}}','{{$key}}')">Drop</button></td>
                                        @break
                                    @case('prefix')
                                        <td class="text-left prefix pref">{{$item['pref']}}</td>
                                        <td class="text-left prefix count">{{$item['count']}}</td>
                                        <td class="text-center prefix">
                                            <input type="checkbox" class="mb-1 checkbox-input prefix status" @if($item['status'] == "on") checked @endif name="status">
                                        </td>
                                        @break
                                    @case('manual-prefix')
                                        <td class="text-left prefix pref">{{$item['pref']}}</td>
                                        <td class="text-left prefix count">{{$item['count']}}</td>
                                        <td class="text-center prefix">
                                            <input type="checkbox" class="mb-1 checkbox-input prefix status" @if($item['status'] == "on") checked @endif name="status">
                                        </td>
                                        <td class="text-center"><button class="btn btn-danger" onclick="dropManualPath('{{$mode}}','{{$item['pref']}}')">Drop</button></td>
                                        @break
                                    @default
                                        <td class="text-left">null</td>
                                        @break
                                @endswitch
                                @break
                            @case('databases')
                                @if($mode === 'tables')
                                    @php $tableName = $item['table name'] @endphp
                                    <td class="text-left"><a href="{{route('admin_panel.getList', ['databases',"table-$tableName"])}}">{{$tableName}}</a></td>
                                    <td class="text-left">{{$item['count']}}</td>
                                @elseif($mode === 'error')
                                    <td class="text-left">{{$error_message}}</td>
                                @else
                                    @foreach($head as $column)
                                        <td class="text-left">{{$item->$column}}</td>
                                    @endforeach
                                @endif
                                @break
                        @endswitch
                </tr>
            @endforeach
            </tbody>
        </table>

        @if(str_contains($mode,'manual'))
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <hr>
            <h1 class="text-center">Create New</h1>
            @include('layout::.addManualForm',$form_properties)
            <div class="d-block text-center mb-5">
                <a href="{{route('admin_panel.freshDatabase','all')}}" class="btn btn-danger">Fresh All Database (Contains data created by you)</a>
            </div>
        @endif

        @if($part === 'databases' && $mode === 'tables')
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <hr>
            <h1 class="text-center">Create New Table</h1>
            @include('layout::.addManualForm',$form_properties)
        @endif


    </div>
@endsection
