<?php
/**
 * Created by PhpStorm.
 * User: fruit
 * Date: 1/22/2019
 * Time: 11:42 PM
 */

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $rules = [];
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
        parent::__construct($user, $this->rules);
    }

    public function getTutorials(Request $request, $id) {
        $user = $this->user::findOrFail($id);
        $own = $user->own_tutorials()->with(['moderators.user', 'observers.user'])->get();
        $moderating = $user->moderating_tutorials()->with(['moderators.user', 'observers.user'])->get();
        $observing = $user->observed_tutorials()->with(['moderators.user', 'observers.user'])->get();
        return array('my' => $own, 'moderating' => $moderating, 'observing' => $observing);
    }
}