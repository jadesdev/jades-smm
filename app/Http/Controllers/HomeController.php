<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DepositService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('front.index');
    }

    public function terms()
    {
        return view('front.terms');
    }

    public function privacy()
    {
        return view('front.privacy');
    }

    public function apiDocs()
    {
        return view('front.api-docs');
    }

    public function howItWorks()
    {
        return view('front.how-it-works');
    }
}
