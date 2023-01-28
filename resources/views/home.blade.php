@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-6 m-2">
            @foreach($tree[$id]["path"] as $item)
                @if(array_values($tree[$id]["path"])[0] != $item)
                    /
                @endif
                <a href="/catalog/{{implode("/", $tree[$item]["slug"])}}/">{{$tree[$item]["key"]}}</a>
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
                                <a href="/catalog/{{implode("/", $el["slug"])}}"> {{$el["key"]}}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <form method="get" action="/catalog/{{implode("/", $tree[$id]["slug"])}}">
                <div class="card" style="overflow: auto;max-height: 70vh;">
                    <ul>
                        @foreach($allProps as $prop)
                            @if (!empty($prop->propvalue))
                                <li>
                                    {{$prop->name}}
                                    <ul>
                                        @if(!$prop->is_number)
                                            @if(isset($allPropValue[$prop->id]))
                                                @foreach($allPropValue[$prop->id] as $value)
                                                    <li>
                                                        <div>
                                                            <input type="checkbox"
                                                                   {{((isset($resParams["param"][$value->prop_id])) && in_array($value->id, $resParams["param"][$value->prop_id])) ? "checked" : ""}}  value="{{$value->id}}"
                                                                   name="prop_{{$value->prop_id}}[]">
                                                            <label for="scales">{{$value->value}}</label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @endif
                                        @else
                                            <li>
                                                <div>
                                                    <input type="text" class="js-range-slider"
                                                           name="range_{{$prop->id}}" value=""/>
                                                    @if(isset($resParams["range"][$prop->id]))
                                                        <script> $("[name=range_{{$prop->id}}]").ionRangeSlider({
                                                                type: "double",
                                                                grid: true,
                                                                min: {{$prop->propvalue["min"]}},
                                                                max: {{$prop->propvalue["max"]}},
                                                                from: {{$resParams["range"][$prop->id]["from"]}},
                                                                to: {{$resParams["range"][$prop->id]["to"]}},
                                                                prefix: "",
                                                            });</script>

                                                    @else
                                                        <script> $("[name=range_{{$prop->id}}]").ionRangeSlider({
                                                                type: "double",
                                                                grid: true,
                                                                min: {{$prop->propvalue["min"]}},
                                                                max: {{$prop->propvalue["max"]}},
                                                                from: {{$prop->propvalue["min"]}},
                                                                to: {{$prop->propvalue["max"]}},
                                                                prefix: "",
                                                            });</script>
                                                    @endif
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                        @endforeach

                    </ul>
                </div>
                @if(!empty($allProps))
                    <button class="btn btn-primary">filter</button>
                @endif
            </form>

        </div>
        <div class="col-md-7">
            @if($sectionIsset != 0)
                <div class="mb-4">
                    @foreach($tree[$id] as $key => $el)
                        @if(isset($el["key"]))
                            <div class="card col-2 p-3">
                                @if(isset($sectionsDetail[$key]["prop"]["img"]))
                                    <img src="{{$sectionsDetail[$key]["prop"]["img"]}}" class="card-img-top" alt="...">
                                @endif
                                <a href="/catalog/{{implode("/",$el["slug"])}}">
                                    <span>{{$el["key"]}}</span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <style>
                .el li {
                    list-style-type: none; /* Убираем маркеры */
                }

                .el ul {
                    margin-left: 0; /* Отступ слева в браузере IE и Opera */
                    padding-left: 0; /* Отступ слева в браузере Firefox, Safari, Chrome */
                }
            </style>
            <div class="el">
                <ul>
                    @if (!empty($els))
                        @foreach($els as $key => $el)
                            @if(isset($el["name"]) && empty($el["prop"]["is_op"]))
                                <li class="card mb-4">
                                    <div class="p-2">
                                        <a href="/catalog/{{implode("/", $tree[$el["iblock_id"]]["slug"])}}/{{$el["slug"]}}">{{$el["name"]}}</a>
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
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    @else
                        <h5>empty</h5>
                    @endif
                </ul>
                @if(isset($page))
                    <div class="d-flex flex-column">
                        <div style="margin-left: auto;" class="mb-4 pagination">
                            total - {{$count}}
                        </div>
                        <div style="margin-left: auto;" class="pagination">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    @if($page - 1 >= 1)
                                        <li class="page-item"><a class="page-link"
                                                                 href="/catalog/{{implode("/", $tree[$id]["slug"])}}/{{$page-1}}{{$getParams}}"><span>prev</span></a>
                                        </li>

                                    @endif
                                    <li class="page-item page-link active"><span>{{$page}}</span></li>
                                    @if($page + 1 <= ceil($count / 5))
                                        <li class="page-item"><a class="page-link"
                                                                 href="/catalog/{{implode("/", $tree[$id]["slug"])}}/{{$page+1}}{{$getParams}}"><span>next</span></a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @section('zhsmenu')
        <script>
            let zhs = new zhsmenu({!! $zhsmenu !!});
            zhs.init(".zhs");
        </script>
    @endsection
@endsection
