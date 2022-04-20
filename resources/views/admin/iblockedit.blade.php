@extends('layouts/admin')

@section('content')
    <div class="row">
        <div class="my-4">
            @foreach($breadcrumb as $item)
                <a href="/admin/{{$item["id"]}}/elementlist">{{$item["name"]}}</a>
                @if(end($breadcrumb) != $item)
                    -
                @endif
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="my-4">
            <a href="/admin/{{$iblock->id}}/delete">
                <button class="btn btn-danger">delete</button>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <form action="/admin/{{$iblock->id}}/iblockedit" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">iblock name</label>
                        <input type="text" value="{{$iblock->name}}" class="form-control" name="name">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">изменить</button>
                    </div>
                </form>
                @foreach($iblock->properties as $prop)
                    <form>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">{{$prop->name}}</label>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-danger">удалить</button>
                        </div>
                    </form>
                @endforeach
                <form action="/admin/{{$iblock->id}}/propertyadd" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">название проперти</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">добавить property</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
