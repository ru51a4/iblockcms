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
                    @if(!$prop->is_number)
                        <textarea name="{{$prop->id}}"></textarea>
                    @else
                        <input name="{{$prop->id}}" type="text">
                    @endif
                </div>
            @endforeach
            <button class="btn btn-primary">add</button>
        </form>
    </div>
@endsection
