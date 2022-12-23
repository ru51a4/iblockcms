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
                    @if(!$prop["is_number"])
                        <div class="d-flex flex-column multy-{{$prop["id"]}}">
                            @if (!empty($prop["value"]))
                                @foreach($prop["value"] as $id => $p)
                                    <input type="text" name="{{$prop["id"]}}[]" value="{{$p}}">
                                @endforeach
                            @endif
                            @if($prop["is_multy"])
                                <span onclick="add({{$prop["id"]}}, event)">add</span>
                            @endif

                        </div>
                    @else
                        <div class="d-flex flex-column multy-{{$prop["id"]}}">
                            @if (!empty($prop["value"]))
                                @foreach($prop["value"] as $id => $p)
                                    <input type="text" name="{{$prop["id"]}}[]" value="{{$p}}">
                                @endforeach
                            @endif
                            @if($prop["is_multy"])
                                <span onclick="add({{$prop["id"]}}, event)">add</span>
                            @endif

                        </div>
                    @endif
                </div>
            @endforeach
            <button class="btn btn-primary">edit</button>
        </form>
    </div>
    <script>
        function add(id, e) {
            e.preventDefault();
            var parinput = document.createElement('input');
            $(parinput).attr("type", "text");
            $(parinput).attr("name", `${id}[]`);
            $(e.target.parentElement).append(parinput)
        }
    </script>
@endsection
