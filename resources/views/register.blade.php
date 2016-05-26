@extends('master')

@section('title')
    Register
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
                    <label for="password_confirmation">Password</label>
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
                },
                error: function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status == 422) {
                        $(".alert-danger").remove();
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
