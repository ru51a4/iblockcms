@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4 d-flex flex-column">
            <div class="card d-flex flex-row">
                @foreach($tree[$id]["path"] as $item)
                    <a class="mx-2" href="/home/{{$item}}/">{{$tree[$item]["key"]}}</a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="mt-5 row">
        <div class="d-flex flex-row justify-content-center">
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
@endsection
