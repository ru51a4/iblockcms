@extends('layouts/admin')

@section('content')
    <div class="row">
        <form action="/admin/{{$iblock_element->id}}/editelement" method="post">
            @csrf
            <div class="form-group">
                <label>Название</label>
                <input name="name" value="{{$iblock_element->name}}" type="text">
            </div>
            @foreach($resProp as $prop)
                <div class="form-group">
                    <label>{{$prop["name"]}}</label>
                    @if($prop["is_number"])
                        <input value="{{$prop["value"]}}" name="{{$prop["id"]}}" type="text">
                    @elseif($prop["is_multy"])
                        <div class="inputs d-flex flex-column col-4">
                            @foreach($prop["value"] as $item)
                                <input value="{{$item}}" name="{{$prop["id"]}}[]" type="text">
                            @endforeach
                        </div>
                        <button onclick="addInput(event)">add</button>

                    @else
                        <textarea name="{{$prop["id"]}}">{{$prop["value"]}}</textarea>
                    @endif
                </div>
            @endforeach
            <button class="btn btn-primary">edit</button>
        </form>
    </div>
@endsection
<script>
    function addInput(event) {
        event.preventDefault();
        var input = document.createElement("input");
        input.type = "text";
        input.name = `${event.target.parentElement.querySelector("input").name}`;
        event.target.parentElement.querySelector(".inputs").append(input);
    }
</script>

