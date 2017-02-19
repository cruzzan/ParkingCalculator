@extends('layout.base')

@section('content')

    <div class="title">Parking calculator</div>
    <div class="parking-calculator-form">
        <form name="parking-calculator-form" action="{{$url}}" method="post">
            {{ csrf_field() }}
            <input type="text" name="time-start" placeholder="Timestamp start">
            <input type="text" name="time-end" placeholder="Timestamp end">
            <input type="submit" value="Calculate">
        </form>
    </div>

    @if(isset($result))
        <div id="results">
            {{ $result }}
        </div>
    @endif

@endsection
