<style>
    body{
        color: #333;
        background: #ffffff;
        font-family: Arial, Tahoma;
        font-size: 14px;
    }
</style>

<body>
<div class="logo">
    <img src="http://skydrops.skypro.ch/img/email-logo.png" />
</div>
<h2>Welcome to SKyDrops</h2>
<div>
    <p>Thanks for creating an account</p>
    <p>Please follow the link below to verify your email address:</p>
    <a href="{{\Illuminate\Support\Facades\Config::get('app.domain')}}/auth/confirm/{{$code}}">{{\Illuminate\Support\Facades\Config::get('app.domain')}}/auth/confirm/{{$code}}</a>
</div>
</body>