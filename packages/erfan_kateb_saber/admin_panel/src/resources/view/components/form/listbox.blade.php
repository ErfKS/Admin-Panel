<h1>{{$attributes['text']}}</h1>
@php
    $id=Illuminate\Support\Str::random(16);
@endphp
@isset($attributes['create_item_button']['color'])
    <a onclick="addElement(1)" class="text-white btn btn-{{$attributes['create_item_button']['color']}}" id="add-{{$id}}">@isset($attributes['create_item_button']['text']){{$attributes['create_item_button']['text']}}@else+@endisset</a>
@endisset

<div id="items-{{$id}}">
    @include('layout::FormBody',['inputs'=>['inputs'=>$attributes['count']]])
</div>
<script>
    let nextElementNumber = 0;
    let numberOfElement = 0;
    const listId = "items-{{$id}}";

    function createElement(elements){
        document.getElementById(listId).innerHTML += elements;
    }
    function DeleteItem(itemNum){
        removeChildElement(itemNum*2-1);
        removeChildElement(itemNum*2-2);
        /*numberOfElement--;*/
    }
    function removeChildElement(childNum){
        const list = document.getElementById(listId);
        list.removeChild(list.children[childNum.toString()]);
    }


    function ReplaceWord(search , replace , text){
        let tempText = '';
        let arrayText = text.split(search);

        arrayText.forEach(item=>{
            tempText += item;
            if(arrayText[arrayText.length -1] !== item) {
                tempText += replace;
            }
        });
        return tempText;

    }
    function addElement(count){

        let textToAdd = `@include('layout::FormBody',['inputs'=>['inputs'=>$attributes['one_item']]])`;

        let htmlElements=``;
        for (let i = 0; i < count; i++) {
            htmlElements +=ReplaceWord("__number__",String(nextElementNumber+1),textToAdd);
            nextElementNumber++;
            numberOfElement++;
        }
        createElement(htmlElements);
    }

    addElement({{$attributes['item_count']}});

</script>
