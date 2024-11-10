<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function showDashboard()
    {
        
        $users = User::all();
        
        
        return view('dashboard', compact('users'));
    }
}
