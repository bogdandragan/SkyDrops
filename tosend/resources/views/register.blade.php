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
                Registration
            </div>
            <div class="boxContent">
                <form action="/auth/register" method="POST" id="registrationForm">
                    <label for="username">Username</label>
                    <input type="text" name="username" autofocus />
                    <label for="email">Email</label>
                    <input type="email" name="email" />
                    <label for="first_name">First name</label>
                    <input type="text" name="first_name" />
                    <label for="last_name">Last name</label>
                    <input type="text" name="last_name" />
                    <label for="password">Password</label>
                    <input type="password" name="password" />
                    <label for="password_confirmation">Confirm password</label>
                    <input type="password" name="password_confirmation" />
                    <input type="submit" id="submitButton" class="button" value="Register">
                    <p style="display: block; margin-top: 1rem; font-weight: 200" >Already have an account?<a href="/login"> Log In</a></p>
                    {!! Form::token() !!}
                </form>
            </div>
        </div>
        <!-- Content end -->
    </div>
    <script>
        $("#registrationForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "/auth/register",
                data: $("#registrationForm").serialize(),
                success: function(data) {
                    console.log(data);
                    $(".alert-danger, .alert-success").remove();
                    var successHtml = "<div class='alert alert-success' style='margin-top: 10px;'><ul style='list-style-type: none;'>"+
                            "<li>Please check your email to confirm registration</li></ul></div>"
                    $("#submitButton").after(successHtml);
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
