<div style="width: 100vw;height: 15vh;">
    <nav style="z-index: 1" class="navbar navbar-expand navbar-dark bg-black border-primary w-100 position-fixed" style="border-bottom: 5px solid;">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin_panel.Logout')}}">Logout</a>
                </li>
                @php $navbarDropdownNumber = 0; @endphp
                @foreach($navLinks as $name => $property)

                    @if($property['type']==='Button')
                        <li class="nav-item dropdown">
                            @isset($property['href'])
                                <a onclick="@isset($property['onClick']){{$property['onClick']}}@endisset" class="nav-link @isset($property['text-color-class']){!! $property['text-color-class']!!} @endisset" href="{!! $property['href'] !!}">{!!$name!!}</a>
                            @else
                                <button onclick="@isset($property['onClick']){{$property['onClick']}}@endisset" class="nav-link @isset($property['text-color-class']){!! $property['text-color-class']!!} @endisset bg-transparent border-0 cursor-pointer">{!!$name!!}</button>
                            @endisset
                        </li>
                    @elseif($property['type']==='Dropdown')

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown-{!! $navbarDropdownNumber !!}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{$name}}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown-{!! $navbarDropdownNumber !!}">
                                @foreach($property['buttons'] as $subName => $subProperty)
                                    @isset($property['href'])
                                    <a @isset($subProperty['onClick']) onclick="{{$subProperty['onClick']}}"@endisset class="dropdown-item" href="{!! $subProperty['href']!!}">{!!$subName!!}</a>
                                    @else
                                        @isset($subProperty['href'])
                                        <a @isset($subProperty['onClick']) onclick="{{$subProperty['onClick']}}"@endisset class="dropdown-item @isset($subProperty['text-color-class']){!!$subProperty['text-color-class']!!} @endisset bg-transparent border-0 cursor-pointer cursor-pointer" href="{!! $subProperty['href'] !!}">{!!$subName!!}</a>

                                        @else
                                            <button @isset($subProperty['onClick']) onclick="{{$subProperty['onClick']}}"@endisset class="dropdown-item @isset($subProperty['text-color-class']){!!$subProperty['text-color-class']!!} @endisset bg-transparent border-0 cursor-pointer cursor-pointer">{!!$subName!!}</button>
                                        @endif
{{--                                        <button @isset($subProperty['onClick']) onclick="{{$subProperty['onClick']}}"@endisset class="dropdown-item @isset($subProperty['text-color-class']){!!$subProperty['text-color-class']!!} @endisset bg-transparent border-0 cursor-pointer cursor-pointer">{!!$subName!!}</button>--}}
                                    @endisset
                                @endforeach
                            </div>
                        </li>
                        @php $navbarDropdownNumber++; @endphp
                    @endif
                @endforeach
            </ul>
        </div>
    </nav>
</div>

