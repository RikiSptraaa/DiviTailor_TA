<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_pengguna' => ['required', 'string', 'max:20', 'regex:/^\S*$/u'],
            'nama_lengkap' => ['required', 'string', 'max:50'],
            'nomor_telepon' => ['required', 'max:20'],
            'alamat' => ['required'],
            'instansi' => ['required', 'max:30'],
            'jenis_kelamin' => ['required', 'numeric'],
            'kota' => ['required', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->email)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],[
            'nama_pengguna.regex' => 'nama pengguna tidak diperbolehkan berisi spasi' 
        ] );

        $user = User::create([
            'name' => $request->nama_lengkap,
            'username' => $request->nama_pengguna,
            'email' => $request->email,
            'phone_number' => $request->nomor_telepon,
            'address' => $request->alamat,
            'institute' => $request->instansi,
            'gender' => $request->jenis_kelamin,
            'city' => $request->kota,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
