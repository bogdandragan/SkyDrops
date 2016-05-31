@extends('master')

@section('title')
    Users Dashboard | SkyDrops Beta
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

    <div class="container noSubHeader wrap">
        <!-- Content start -->
        <div class="box" style="padding: 20px;">
            <h2>Users</h2>
            <table class="table table-bordered">
                <tr>
                    <th>Email</th>
                    <th>Username</th>
                    <th>First name</th>
                    <th>Last name</th>
                </tr>
            @forelse ($users as $user)
                <tr>
                    <td>{{$user->email}}</td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->firstname}}</td>
                    <td>{{$user->lastname}}</td>
                </tr>
            @empty
                No Users
            @endforelse
            </table>
        </div>
        <!-- Content end -->
    </div>
    {!! Form::token() !!}

    <script>

    </script>
@endsection
