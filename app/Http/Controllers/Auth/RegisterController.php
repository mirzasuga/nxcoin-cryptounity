<?php

namespace Cryptounity\Http\Controllers\Auth;

use Cryptounity\User;
use Cryptounity\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
//use Illuminate\Auth\Events\Registered;
use Cryptounity\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        $reff = $request->ref;

        return view('auth.register',[
            'reff' => $reff
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'username' => 'required|alpha_num|min:6|unique:users',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Cryptounity\User
     */
    protected function create(array $data)
    {
        
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'referral_id' => $data['referral_id']
        ]);
    }
    public function register(Request $request)
    {
        $data = $request->all();
        $referral = User::where('username',$request->referral_id)->first(); //USER OBJECT
        if($referral) {
            $data['referral_id'] = $referral->id;
        } else {
            $data['referral_id'] = 1;
        }
        $referral = User::where('username','mirza')->first(); //USER OBJECT
        
        $this->validator($data)->validate();

        event(new Registered($user = $this->create($data), $referral));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
}
