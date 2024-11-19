<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'accountID' => 'required|string|max:255|unique:Accounts',
            'password' => 'required|string|min:8|confirmed',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'dateOfBirth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|string|max:8',
            'email' => 'required|string|email|max:100|unique:Patients,Email',
        ]);

        DB::transaction(function () use ($request) {
            DB::table('Accounts')->insert([
                'AccountID' => $request->accountID,
                'Password' => Hash::make($request->password),
                'Role' => 'Patient',
                'AccountStatus' => 'Active',
                'Credentials' => '',
                'IV' => random_bytes(16),
            ]);

            DB::table('Patients')->insert([
                'AccountID' => $request->accountID,
                'FirstName' => $request->firstName,
                'LastName' => $request->lastName,
                'DateOfBirth' => $request->dateOfBirth,
                'Gender' => $request->gender,
                'Phone' => $request->phone,
                'Email' => $request->email,
            ]);
        });

        return redirect()->route('login')->with('success', 'Account created successfully.');
    }
}