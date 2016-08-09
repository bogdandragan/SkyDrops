@extends('master')

@section('title')
    New password | SKyDrops Beta
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
    <div class="container wrap noSubHeader wrapLogin">

        <!-- Content start -->
        <div class="box">
            <div class="boxHeader">
                New password
            </div>
            <div class="boxContent">
                <form action="/u/newPassword" method="POST" id="newPasswordForm">
                    <label for="password">Password</label>
                    <input type="password" name="password" />
                    <label for="password_confirmation">Confirm password</label>
                    <input type="password" name="password_confirmation"/>
                    <input type="hidden" name="code" value="{{$code}}"/>
                    <input type="submit" id="submitButton" class="button" value="Send">
                    {!! Form::token() !!}
                </form>
            </div>
        </div>
        <!-- Content end -->
    </div>
    <script>
        $("#newPasswordForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "/u/newPassword",
                data: $("#newPasswordForm").serialize(),
                success: function(data) {
                    window.location = "/home";
                },
                error: function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status == 422) {
                        $(".alert-danger, .alert-success").remove();
                        var errors = jqXHR.responseJSON;
                        var errorsHtml = "<div class='alert alert-danger' style='margin-top: 10px;'><ul>";
                        $.each(jqXHR.responseJSON, function (index, value) {
                            errorsHtml += "<li>"+value[0]+"</li>"
                        });
                        errorsHtml += "</ul></div>";
                        $("#submitButton").after(errorsHtml);
                    }
                }
            });

        });
    </script>
@endsection
