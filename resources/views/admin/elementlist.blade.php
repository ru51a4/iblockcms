@extends('layouts/admin')

@section('content')
    <div class="row">
        <div class="my-4">
            <a href="/admin/{{$iblock->id}}/addelement"><button type="submit" class="btn btn-primary">Создать элемент</button></a>
        </div>
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
        <div class="d-flex flex-column justify-content-start dashboard">
            @foreach($iblocks as $el)
                <div class="col-12 card d-flex flex-row">
                    <div class="card-body">
                        <a href="/admin/{{$el->id}}/iblockedit">
                            <h5 class="card-title">{{$el->name}}</h5>
                        </a>
                        <a href="/admin/{{$el->id}}/elementlist">
                            <button class="btn btn-primary">элементы</button>
                        </a>
                    </div>
                </div>
            @endforeach

           @foreach($elements as $el)
            <div class="col-12 card d-flex flex-row">
                <div class="card-body">
                    <a href="#">
                        <h5 class="card-title">{{$el->name}}</h5>
                        <ul>
                            @foreach($el->propvalue as $prop)
                            <li>
                                {{$prop->prop->name}} - {{$prop->value}}
                            </li>
                            @endforeach
                        </ul>
                    </a>
                    <a href="/admin/{{$el->id}}/deleteelement">
                        <button class="btn btn-danger">удалить</button>
                    </a>
                </div>
            </div>
            @endforeach

        </div>
    </div>
@endsection
