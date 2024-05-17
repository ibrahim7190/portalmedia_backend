<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::all();
        return response()->json($accounts);
    }

    public function store(Request $request)
    {
        $account = new Account();
        $account->fill($request->all());
        $account->save();
        return response()->json($account, 201);
    }

    public function show($id)
    {
        $account = Account::findOrFail($id);
        return response()->json($account);
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);
        $account->update($request->all());
        return response()->json($account);
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return response()->json(null, 204);
    }

    // public function register(Request $request)
    // {
    //     // Validate incoming data
    //     $validatedData = $request->validate([
    //         'firstname' => 'required|string',
    //         'lastname' => 'required|string',
    //         'username' => 'required|string|unique:accounts',
    //         'email' => 'required|email|unique:accounts',
    //         'phone' => 'nullable|string',
    //         'password' => 'required|string|min:6',
    //         'role' => 'nullable|string', // Define appropriate validation rules
    //     ]);

    //     // Create a new account
    //     $account = new Account();
    //     $account->fill($validatedData);
    //     $account->save();

    //     // Return the newly created account
    //     return response()->json($account, 201);
    // }
    public function register(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|unique:accounts',
            'email' => 'required|email|unique:accounts',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string', // Define appropriate validation rules
        ]);

        // Hash the password before saving
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Create a new account
        $account = new Account();
        $account->fill($validatedData);
        $account->save();

        // Return the newly created account
        return response()->json($account, 201);
    }
    // public function login(Request $request)
    // {
    //     // Validate incoming data
    //     $credentials = $request->validate([
    //         'email' => 'required|string',
    //         'password' => 'required|string',
    //     ]);
    
    //     // Find the user by email
    //     $user = Account::where('email', $credentials['email'])->first();
    
    //     // Check if the user exists and the password matches
    //     if ($user && $user->password === $credentials['password']) {
    //         // Authentication successful
    //         return response()->json(['user' => $user], 200);
    //     }
    
    //     // Return error response if login fails
    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
    public function login(Request $request)
    {
        // Validate incoming data
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Find the user by email
        $user = Account::where('email', $credentials['email'])->first();
    
        // Check if the user exists and the password matches
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Authentication successful
            return response()->json(['user' => $user], 200);
        }
    
        // Return error response if login fails
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
