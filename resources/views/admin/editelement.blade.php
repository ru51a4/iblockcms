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
                        <textarea name="{{$prop["id"]}}">{{$prop["value"]}}</textarea>
                    @else
                        <input value="{{$prop["value"]}}" name="{{$prop["id"]}}" type="text">
                    @endif
                 </div>
            @endforeach
            <button class="btn btn-primary">edit</button>
        </form>
    </div>
@endsection
