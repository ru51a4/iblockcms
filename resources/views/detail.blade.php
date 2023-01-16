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
                    @foreach($tree as $key => $cel)
                        @if($key == $id)
                            <li>  @for($i =1; $i<= count($cel["path"]); $i++)
                                    -
                                @endfor
                                <b> <a href="/home/{{$key}}"> {{$cel["key"]}}</a></b>
                            </li>
                        @else
                            <li>
                                @for($i =1; $i<= count($cel["path"]); $i++)
                                    -
                                @endfor
                                <a href="/home/{{$key}}"> {{$cel["key"]}}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-7">
            <div class="row">
                <div class="d-flex flex-row justify-content-start">
                    <div>
                        <img src="http://ufland.moy.su/camera_a.gif">
                    </div>
                    <div class="mx-5">
                        <h6>{{$el["name"]}}</h6>
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
                        <button class="btn btn-primary">buy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


