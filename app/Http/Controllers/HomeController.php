<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function shoutHome()
    {
        $userId = Auth::id();
        $status = Status::where('user_id', $userId)->orderBy('id', 'desc')->get();

        $avatar = empty(Auth::user()->avatar) ? asset("images/default_user.jpg") : Auth::user()->avatar;

        return view("shouthome", [
            'status' => $status,
            'avatar' => $avatar
        ]);
    }

    public function publicTimeline($nickname)
    {
        $user = User::where('nickname', $nickname)->first();
        $name = $user->name;

        if ($user) {
            $status = Status::where('user_id', $user->id)->orderBy('id', 'desc')->get();
            $avatar = empty($user->avatar) ? asset("images/default_user.jpg") : $user->avatar;
            $displayActions = false;
            if (Auth::check()) {
                if (Auth::user()->id != $user->id) {
                    $displayActions = true;
                }
            }

            return view("shoutpublic", [
                'status' => $status,
                'avatar' => $avatar,
                'name' => $name,
                'displayActions' => $displayActions,
                'friendId' => $user->id
            ]);
        } else {
            return redirect('/');
        }
    }

    public function saveStatus(Request $request)
    {
        if (Auth::check()) {
            $status = $request->post('status');
            $userId = Auth::id();

            $statusModel = new Status();
            $statusModel->status = $status;
            $statusModel->user_id = $userId;
            $statusModel->save();
            return redirect()->route('shout');
        }
    }

    public function saveProfile(Request $request)
    {
        if (Auth::check()) {

            $user = Auth::user();
            $user->name = $request->name;
            $user->nickname = $request->nickname;
            $user->email = $request->email;

            $porfilImage = 'user' . $user->id . "." . $request->image->extension();
            $request->image->move(public_path('images'), $porfilImage);

            $user->avatar = asset("images/{$porfilImage}");

            $user->save();
            return redirect()->route('shout.profile');
        }
    }

    public function profile()
    {
        return view('profile');
    }
}
