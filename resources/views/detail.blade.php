@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-6 m-2">
            @foreach($tree[$id]["path"] as $item)
                @if(array_values($tree[$id]["path"])[0] != $item)
                    /
                @endif
                @if(count($tree[$item]["slug"]) > 0)
                    <a href="/catalog/{{implode("/", $tree[$item]["slug"])}}/">{{$tree[$item]["key"]}}</a>
                @else
                    <a href="/catalog/">{{$tree[$item]["key"]}}</a>
                @endif
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
                                <b>{{$cel["key"]}}</b>
                            </li>
                        @else
                            <li>
                                @for($i =1; $i<= count($cel["path"]); $i++)
                                    -
                                @endfor
                                <a href="/catalog/{{implode("/", $cel["slug"])}}"> {{$cel["key"]}}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-7">

            <div class="card">
                <div class="p-2 justify-content-start">
                    <div>
                        @if(!empty($el["prop"]["photo"]))
                        <div id="slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "appendArrows" : ""}'>
                            @foreach ($el["prop"]["photo"] as $url)
                            <div><img src="{{$url}}" style="width:300px;">
                            </div>       
                            @endforeach
                          </div>
                          <script>
                            $("#slider").slick({

                            // normal options...
                            infinite: true,
                            dots: true,
                            adaptiveHeight: true,
                            // the magic
                            responsive: [{

                                breakpoint: 1024,
                                settings: {
                                slidesToShow: 3,
                                infinite: true
                                }

                            }, {

                                breakpoint: 600,
                                settings: {
                                slidesToShow: 2,
                                dots: true
                                }

                            }, {

                                breakpoint: 300,
                                settings: "unslick" // destroys slick

                            }]
                            });

                          </script>
                          @else
                        <img src="{{$el["prop"]["DETAIL_PICTURE"]}}" style="width:300px;">
                          @endif
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
    @section('zhsmenu')
        <script>
            let zhs = new zhsmenu({!! $zhsmenu !!});
            zhs.init(".zhs");
        </script>
    @endsection
@endsection


