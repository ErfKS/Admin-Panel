<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=5, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{--bootstrap--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/admin_panel/css/bootstrap.compl.css')}}" media="screen">

    <title>admin_panel -> @yield('title')</title>
</head>

{{--bootstrap--}}
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<body style="overflow-x: unset!important;" class="">

@yield('content')
</body>
<script>
    function getAllData(){
        let rowNumber = 0;
        let routeArray = [];
        while (true){
            let currentArray = [];
            if(!$('.pref').get(rowNumber)){
                break;
            }
            if($( ".pref" ).hasClass( "all" )) {
                currentArray = {
                    'path': $('.path').eq(rowNumber).text(),
                    'name': $('.name').eq(rowNumber).text(),
                    'pref': $('.pref').eq(rowNumber).text(),
                    'method': $('.method').eq(rowNumber).text(),
                    'controller': $('.controller').eq(rowNumber).text(),
                    'status': $('.status').eq(rowNumber).is(":checked") ? "on" : "off",
                }
            } else {
                currentArray = {
                    'pref': $('.pref').eq(rowNumber).text(),
                    'count': $('.count').eq(rowNumber).text(),
                    'status': $('.status').eq(rowNumber).is(":checked") ? "on" : "off",
                }
            }
            routeArray.push(currentArray);
            rowNumber++;
        }
        return routeArray;
    }

    function dropManualPath(mode,path){
        console.log(mode,path);
        const data = {
            'data': JSON.stringify(path),
            'mode': mode
        };
        redirect_by_post("{{route("admin_panel.dropRecord")}}",data,false);
    }

    function goEditTotalRoute(mode){
        const data = {
            'data': JSON.stringify(getAllData()),
            'mode': mode
        };

        redirect_by_post("{{route("admin_panel.editTotalValue")}}",data,false);
    }

    function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {}: pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true: in_new_tab;

        pparameters['_token'] ='{{csrf_token()}}';
        console.log(pparameters);
        let form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }

        $.each(pparameters,
            function (key) {
                $(form).append('<input type="text" name="' + key + '" value=\'' + this + '\' />');
            });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);

        return false;
    }

    function ConvertObjectToArray(obj){
        if(!isObject(obj)){
            return obj;
        }
        return Object.keys(obj).map(function (key){
            let thisObj= obj[key];
            if(isObject(thisObj)){
                Object.keys(obj).map(function (key){
                    return ConvertObjectToArray(key);
                });
            }
            return thisObj
        });
    }

    function ConvertObjectToArray_nested(obj) {
        let arr  = [],
            keys = Object.keys(obj);

        for(let i=0,n=keys.length;i<n;i++){
            let key  = keys[i];
            arr[key] = obj[key];
        }
        return arr
    }

    function isObject(obj){
        if(
            typeof obj === 'object' &&
            obj !== null &&
            !Array.isArray(obj)
        ){
            return true;
        }
        return false
    }
</script>
@yield('custom js')
@include('layout::scroll',['always_work'=>true])

@if(session('modal'))
    @include('layout::Modals',session('modal'))
@endif

@if(session('modal'))
    <script type="text/javascript">
        $('#myModal').modal('show');
        $('#myModal').modal({ show: false})
    </script>
@endif
</html>
