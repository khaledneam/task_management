<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsersToAssignTask(Request $request){

        $perPage = $request->input('per_page', 10); // Number of users per page
        $search = $request->input('search'); // Search keyword

        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        $users = $query->paginate($perPage);

        return response()->json($users);

    }
}
