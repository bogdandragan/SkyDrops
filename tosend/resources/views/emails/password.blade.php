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
<h2>Restore password request</h2>
<div>
    <p>Hello, {{$username}}. Please follow the link below to reset your password:</p>
    <a href="{{\Illuminate\Support\Facades\Config::get('app.domain')}}/u/restoreConfirm/{{$code}}">{{\Illuminate\Support\Facades\Config::get('app.domain')}}/u/restoreConfirm/{{$code}}</a>
</div>
</body>
