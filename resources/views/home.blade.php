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
        <div class="col-md-4">
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
        </div>
        <div class="col-md-7">
            @if($sectionIsset != 0)
                <div class="row mb-4">
                    @foreach($tree[$id] as $key => $el)
                        @if(isset($el["key"]))
                            <div class="card col-2 p-3">
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
                            @if(isset($el["name"]))
                                <li><a href="/detail/{{$el["id"]}}">{{$el["name"]}}</a>
                                    <ul>
                                        @foreach($el["prop"] as $key => $prop)
                                            @if(is_array($prop))
                                                <li>{{$key}}</li>
                                                <ul>
                                                @foreach($prop as $key => $prop)
                                                    <li>{{$key}} - {{$prop}}</li>
                                                @endforeach
                                                </ul>
                                            @else
                                                <li>{{$key}} - {{$prop}}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection
