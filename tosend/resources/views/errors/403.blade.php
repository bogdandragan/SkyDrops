@extends('master')

@section('title')
Forbidden | SkyDrops Beta
@endsection

@section('style')
    .container h2, .container h3, .container p {
    text-align: center;
    }

    .container h2 {
    margin-top: 10rem;
    margin-bottom: 0;
    font-size: 22rem;
    font-weight: 600;
    }

    .container h3 {
    font-size: 5rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-top: 0;
    margin-bottom: 2rem;
    }

    .container p {
    width: 40rem;
    margin: auto;
    }

    .link {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 550px;
    height: 660px;
    background: url('/img/link-404.png');
    background-position: center;
    background-size: cover;
    }
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

@section('content')

    <div class="container wrap noSubHeader">

        <!-- Content start -->

        <h2>403</h2>
        <h3>Forbidden</h3>
        <p>Access denied</p>
        <div class="link"></div>

        <!-- Content end -->

    </div>
@endsection
