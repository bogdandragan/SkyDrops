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
