<?php

namespace App\Http\Controllers\Auth;
use App\Labor;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => ['required', 'string', 'max:16', 'alpha_num', 'unique:users,name'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user =  User::create([
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
        ]);
        $id = $user->id;

        $labor = new Labor;
        $labor->userID = $id;
        $labor->actionsUntilSkill = 1000;
        $labor->save();

        $labor = \App\Labor::find($labor->id);
        $labor->actionsUntilSkill = $labor->workHours / $labor->maxSkillPoints;
        $labor->save();

        $skillTypes = \App\SkillTypes::all();
        foreach($skillTypes as $skillType){
          $skill = new \App\Skills;
          $skill->skillTypeID = $skillType->id;
          $skill->userID = $id;
          $skill->save();
        }
        $itemTypes = \App\ItemTypes::all();
        foreach($itemTypes as $itemType){
          $item = new \App\Items;
          $item->itemTypeID = $itemType->id;
          $item->countable = $itemType->countable;
          $item->userID = $id;
          $item->save();
        }



        return $user;


    }
}
