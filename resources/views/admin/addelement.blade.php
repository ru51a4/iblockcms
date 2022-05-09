@extends('layouts/admin')

@section('content')
    <div class="row">
        <form action="/admin/{{$iblock->id}}/addelement" method="post">
            @csrf
            <div class="form-group">
                <label>Название</label>
                <input name="name" type="text">
            </div>
            @foreach($iblock->getPropWithParrents() as $prop)
                <div class="form-group">
                    <label>{{$prop->name}}</label>
                    @if($prop->is_number)
                        <input name="{{$prop->id}}" type="text">
                    @elseif($prop->is_multy)
                        <div class="inputs d-flex flex-column col-4">
                            <input name="{{$prop->id}}[]" type="text">
                        </div>
                        <button onclick="addInput(event)">add</button>
                    @else
                        <textarea name="{{$prop->id}}"></textarea>
                    @endif
                </div>
            @endforeach
            <button class="btn btn-primary">add</button>
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
