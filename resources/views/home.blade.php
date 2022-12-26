@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6 m-2">
            @foreach($tree[$id]["path"] as $item)
                <a href="/home/{{$item}}/">{{$tree[$item]["key"]}}</a>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 d-flex flex-column">
            <div class="card">
                <ul>
                    @foreach($tree as $key => $el)
                        @if($key == $id)
                            <li>  @for($i =1; $i<= count($el["path"]); $i++)
                                    -
                                @endfor
                                <b>{{$el["key"]}}</b>
                            </li>
                        @else
                            <li>
                                @for($i =1; $i<= count($el["path"]); $i++)
                                    -
                                @endfor
                                <a href="/home/{{$key}}"> {{$el["key"]}}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="card">
                <form method="post" action="/home/{{$id}}">
                    @csrf
                    <ul>
                        @foreach($allProps as $prop)
                            @if (!empty($prop->propvalue))
                                @php
                                    {{$c = []; $cc = false;}}
                                @endphp

                                <li>
                                    {{$prop->name}}
                                    <ul>
                                        @if(!$prop->is_number)
                                            @if(isset($allPropValue[$prop->id]))
                                                @foreach($allPropValue[$prop->id] as $value)
                                                    @php
                                                        {{
                                                            $cc = false;
                                                            if(isset($c[$value->value])){
                                                            $cc = true;
                                                        } else{
                                                                $c[$value->value] = true;
                                                        }
                                                        }}
                                                    @endphp
                                                    @if ($cc)
                                                        @continue
                                                    @endif
                                                    <li>
                                                        <div>
                                                            <input type="checkbox"
                                                                   {{((isset($resParams[$value->prop_id])) && in_array($value->id, $resParams[$value->prop_id])) ? "checked" : ""}}  name="{{$value->prop_id}}-{{$value->id}}">
                                                            <label for="scales">{{$value->value}}</label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @endif
                                        @else
                                            <li>
                                                <div>
                                                    <input type="range" id="volume" name="volume"
                                                           min="{{$prop->propvalue["min"]}}"
                                                           max="{{$prop->propvalue["max"]}}">
                                                    <label for="volume">{{$prop->propvalue["max"]}}</label>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                        <button class="btn btn-primary">filter</button>

                    </ul>
                </form>
            </div>
        </div>
        <div class="col-md-7">
            @if($sectionIsset != 0)
                <div class="row mb-4">
                    @foreach($tree[$id] as $key => $el)
                        @if(isset($el["key"]))
                            <div class="card col-2 p-3">
                                @if(isset($sectionsDetail[$key]["prop"]["img"]))
                                    <img src="{{$sectionsDetail[$key]["prop"]["img"]}}" class="card-img-top" alt="...">
                                @endif
                                <a href="/home/{{$key}}">
                                    <span>{{$el["key"]}}</span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <div class="row">
                <ul>
                    @if (!empty($tree[$id]["elements"]))
                        @foreach($tree[$id]["elements"] as $key => $el)
                            @if(isset($el["name"]) && empty($el["prop"]["is_op"]))
                                <li><a href="/detail/{{$el["id"]}}">{{$el["name"]}}</a>
                                    <ul>
                                        @foreach($el["prop"] as $key => $prop)
                                            @if(is_array($prop))
                                                <li>{{$key}}</li>
                                                <select>
                                                    @foreach($prop as $key => $prop)
                                                        <option>{{$prop}}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <li>{{$key}} - {{$prop}}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    @else
                        <h5>empty</h5>
                    @endif
                </ul>
            </div>
        </div>
    </div>

@endsection
