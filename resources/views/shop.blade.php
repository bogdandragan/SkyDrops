@extends('master')

@section('title')
    Buy coins | SkyDrops Beta
@endsection

@section('createFEButton')
    <a class="button" href="/upload">Create Drop</a>
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
        background: url({{ asset('/img/banknote-1396352_19201.jpg') }}) repeat-y center center;
        background-position: 10% 20%;
    }

    .coin10{
    height: 200px;
    }

    .coin30{
    height: 200px;
    }

    .coin100{
    height: 200px;
    }

    .coinPackage{
        border: 4px solid #414896;
        border-radius: 15px;
        margin: 0 auto;
        padding-bottom: 25px;
        margin-bottom: 20px;
        background-color: #ececf4;
    }

    .coinPackage h1{
        color: #000 !important;
        font-size: 40px !important;
        font-weight: bold !important;
        margin-left: 20px;
    }

    .addShadow{
        box-shadow: 0px 0px 10px 1px rgba(65,72,150,1);
    }

    .price{
        display: inline-block;
    }

    input[type='radio']{
        display: block !important;
        margin: 5px;
    }
@endsection

@section('content')

    <div class="container noSubHeader wrap">
        <h2 class="text-center">SKyDrops Shop</h2>
        <table style="margin: 0 auto;">
            <tr>
                <td class="product">Add  <strong>10 </strong> coins to your account  <strong>(5$) </strong></td>
                <td class="price5">
                    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="KFD7FELF58Z4U">
                        <input type="hidden" name="custom" value="{{$payment_id}}0">
                        <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                        <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    </form>
                </td>
            </tr>
            <tr>
                <td class="product">Add  <strong>30 </strong> coins to your account  <strong>(12$) </strong></td>
                <td class="price12">
                    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="KFD7FELF58Z4U">
                        <input type="hidden" name="custom" value="{{$payment_id}}1">
                        <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                        <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    </form>
                </td>
            </tr>
            <tr>
                <td class="product">Add  <strong>100 </strong> coins to your account <strong>(30$)</strong></td>
                <td class="price30">
                    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="KFD7FELF58Z4U">
                        <input type="hidden" name="custom" value="{{$payment_id}}2">
                        <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                        <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    </form>
                </td>
            </tr>
        </table>


        {!! Form::token() !!}

    </div>

    @if(Auth::check())

    @endif

    <script>

        $(".price5 input[name = 'submit']").click(function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });


            $.ajax({
                type:	'POST',
                url:	'/shop/startPayment',
                data:	{ payment_id : $('.price5 input[name="custom"]').val() },
                success: function(data){

                },
                error: function(data){

                }
            });

        });

        $(".price12 input[name = 'submit']").click(function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });


            $.ajax({
                type:	'POST',
                url:	'/shop/startPayment',
                data:	{ payment_id : $('.price12 input[name="custom"]').val() },
                success: function(data){

                },
                error: function(data){

                }
            });

        });

        $(".price30 input[name = 'submit']").click(function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });


            $.ajax({
                type:	'POST',
                url:	'/shop/startPayment',
                data:	{ payment_id : $('.price30 input[name="custom"]').val() },
                success: function(data){

                },
                error: function(data){

                }
            });

        });

    </script>
@endsection
