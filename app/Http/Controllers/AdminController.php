<?php namespace App\Http\Controllers;

use App\DropStatistic;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Input;
use App\Drop as Drop;
use App\File as File;
use App\DropTag as DropTag;
use App\Tag as Tag;
use App\User as User;
use App\Group as Group;
use App\UserGroup as UserGroup;
use DB;
use Mail;
use Auth;
use Hash;
use Redirect;
use Log;
use Config;

class AdminController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        /*if(Auth::check()){
            return User::get();
        }*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    public function adminDashboard()
    {
        if(!Auth::user()->isAdmin){
            abort('403');
        }

        $users = User::select('*')->get();

        return view('dashboard', array('users' => $users));
    }

    public function adminStatistic()
    {
        if(!Auth::user()->isAdmin){
            abort('403');
        }
        return view('statistic');
    }

    public function getDropsStatistic()
    {
        $drops = DropStatistic::select(DB::raw('count(id) as count, created_at'))
            ->whereBetween('created_at' , [$_GET['from'], $_GET['to']])
            ->groupBy(DB::raw('date(created_at)'))
            ->get();

        return $drops;
    }

    public function getUserAgentStatistic()
    {
        $userAgents = DropStatistic::select(DB::raw('count(userAgent) as count, userAgent'))
            ->whereBetween('created_at' , [$_GET['from'], $_GET['to']])
            ->groupBy(DB::raw('userAgent'))
            ->get();

        return $userAgents;
    }

}
