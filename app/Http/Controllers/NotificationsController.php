<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;

class NotificationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$pusher = new Pusher('341e2a2f5770505ad5e9',
    '92b74556ce1c0676ff14',
    '580515',
    array('cluster' => "us2"));

  	$data['message'] = 'hello world';
	
	$pusher->trigger('my-channel', 'my-event', $data);

        return view('notifications');
    }
}

