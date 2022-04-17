@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6 m-2">
            @foreach($resTree[$id]["path"] as $item)
                <a href="/home/{{$item}}/">{{$treeKeys[$item]["value"]}}</a>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <ul>
                    @foreach($treeKeys as $key => $el)
                        @if($el["value"] == $id)
                            <li>  @for($i =1; $i<= $el["lvl"]; $i++)
                                    -
                                @endfor
                                <b>{{$el["value"]}}</b>
                            </li>
                        @else
                            <li>
                                @for($i =1; $i<= $el["lvl"]; $i++)
                                    -
                                @endfor
                                <a href="/home/{{$key}}"> {{$el["value"]}}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-7">
            <div class="row">
                @foreach($resTree[$id] as $key => $el)
                    @if(isset($el["key"]))
                        <div class="card col-2 p-3">
                            <a href="/home/{{$key}}">
                                <span>{{$el["key"]}}</span>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="row mt-4">
                <ul>
                    @foreach($resTree[$id] as $key => $el)
                        @if(isset($el["name"]))
                            <li>{{$el["name"]}}
                                <ul>
                                    @foreach($el["prop"] as $key => $prop)
                                        <li>{{$key}} - {{$prop}}</li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
