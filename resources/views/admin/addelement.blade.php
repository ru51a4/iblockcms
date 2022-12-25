@extends('layouts/admin')

@section('content')
    <div class="row">
        <form action="/admin/{{$iblock->id}}/addelement" method="post">
            @csrf
            <div class="form-group">
                <label>Название</label>
                <input name="name" type="text">
            </div>
            @foreach($iblock->getPropWithParrents(true) as $prop)
                <div class="form-group">
                    <label>{{$prop->name}}</label>
                    @if(!$prop->is_number)
                        @if(!$prop->is_multy)
                            <textarea name="{{$prop->id}}"></textarea>
                        @else
                            <div class="d-flex flex-column multy-{{$prop->name}}">
                                <span onclick="add({{$prop->id}}, event)">add</span>
                            </div>
                        @endif
                    @else
                        @if(!$prop->is_multy)
                            <input name="{{$prop->id}}" type="text">
                        @else
                            <div class="d-flex flex-column multy-{{$prop->name}}">
                                <span onclick="add({{$prop->id}}, event)">add</span>
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
            <button class="btn btn-primary">add</button>
        </form>
    </div>
    <script>
        function add(id, e){
            e.preventDefault();
            var parinput=document.createElement('input');
            $(parinput).attr("type","text");
            $(parinput).attr("name",`${id}[]`);
            $(e.target.parentElement).append(parinput)
        }
    </script>
@endsection
