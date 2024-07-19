<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->id === 3) {
            return view('admin.statistic.index');
        }
        return view('admin.home.index');
    }

    public function orders()
    {
        if(Auth::user()->id === 3) {
            return view('admin.statistic.index');
        }

        return view('admin.orders.index');
    }

    public function reservation()
    {
        if(Auth::user()->id != 2) {
            return  redirect(route('admin.home'));
        }
        return view('admin.reservation.index');
    }
    public function statistic()
    {
        if((Auth::user()->id === 2) || (Auth::user()->id === 3)) {
            return view('admin.statistic.index');
        } else {
            return  redirect(route('admin.home'));
        }
    }

    public function settings()
    {
        if(Auth::user()->id != 2) {
            return  redirect(route('admin.home'));
        }
        return view('admin.settings.index');
    }


    public function manual()
    {
        if(Auth::user()->id != 2) {
            return  redirect(route('admin.home'));
        }
        return view('admin.manual-orders.index');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin');
    }

}
