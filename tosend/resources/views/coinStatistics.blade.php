@extends('master')

@section('title')
    My coins balance statistics | SkyDrops Beta
@endsection

@section('createFEButton')
    <a class="button" href="/upload">Create File Exchange</a>
@endsection

@section('scripts')
    {!! HTML::script('/js/jquery.min.js') !!}
    {!! HTML::script('/js/bootstrap.min.js') !!}
    {!! HTML::script('/js/jquery-ui.min.js') !!}
    {!! HTML::script('/js/bootstrap-datepicker.js') !!}
    {!! HTML::script('/js/autosize.js') !!}
    {!! HTML::script('/js/dropzone.js') !!}
    {!! HTML::script('/js/selectize.js') !!}
    {!! HTML::script('/js/sweetalert.min.js') !!}
    {!! HTML::script('/js/chart.min.js') !!}
    {!! HTML::script('/js/jquery.overlay.min.js') !!}
    {!! HTML::script('/js/jquery.textcomplete.min.js') !!}
    {!! HTML::script('/js/skydrops.js') !!}
@endsection

@section('style')
    body{
        background: url({{ asset('/img/banknote-1396352_1920.jpg') }}) repeat-y center center;
    }
@endsection

@section('content')

    <div class="container noSubHeader wrap">
        <!-- Content start -->
        <div class="drop-block clearfix">
            <div class="box" style="padding: 20px;">
                <h2>My coins balance statistics</h2>
                <h4>Current amount: {{Auth::user()->coins}}</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Operation</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
                    @forelse ($coins as $coin)
                        <tr>
                            @if($coin->isAdded)
                            <td>Recharging coins balance</td>
                            @else
                            <td>File exchange <a href="/d/{{$coin->hash}}">#{{$coin->drop_id}}</a></td>
                            @endif
                             <td>{{$coin->created_at->timezone(Auth::user()->timezone)}}</td>
                             @if($coin->isAdded)
                             <td style="color: green;">+{{$coin->amount}}</td>
                             @else
                             <td style="color: red;">-{{$coin->amount}}</td>
                             @endif
                        </tr>
                    @empty
                        No Users
                    @endforelse
                </table>
            </div>
        </div>
        <!-- Content end -->
        {!! Form::token() !!}
    </div>

    <script>

    </script>

@endsection
