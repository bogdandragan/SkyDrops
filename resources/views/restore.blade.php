@extends('master')

@section('title')
    Register
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
                Restore password
            </div>
            <div class="boxContent">
                <form action="/user/restore" method="POST" id="restoreForm">
                    <label for="email">Email</label>
                    <input type="email" name="email" autofocus />
                    <input type="submit" id="submitButton" class="button" value="Restore">
                    <p style="display: block; margin-top: 1rem; font-weight: 200" >Already have an account?<a href="/login"> Log In</a></p>
                    {!! Form::token() !!}
                </form>
            </div>
        </div>
        <!-- Content end -->
    </div>
    <script>
        $("#restoreForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "/u/restore",
                data: $("#restoreForm").serialize(),
                success: function(data) {
                    console.log(data);
                    $(".alert-danger, .alert-success").remove();
                    var successHtml = "<div class='alert alert-success' style='margin-top: 10px;'><ul style='list-style-type: none;'>"+
                            "<li>Please check your email to restore your password</li></ul></div>"
                    $("#submitButton").after(successHtml);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status == 401) {
                        $(".alert-danger").remove();
                        var errorHtml = "<div class='alert alert-danger' style='margin-top: 10px;'><ul style='list-style-type: none;'>"+
                                "<li>"+jqXHR.statusText+"</li></ul></div>"
                        $("#submitButton").after(errorHtml);
                    }
                    if(jqXHR.status == 422){
                        window.location = "http://reset.skypro.ch/";
                    }
                }
            });

        });
    </script>
@endsection
