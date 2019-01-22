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
        $own = $user->own_tutorials()->get();
        $ids = array_map(function ($t) {
            return $t['id'];
        }, $own->toArray());
        $moderating = $user->moderating_tutorials()->whereNotIn('tutorial_id', $ids)->get();
        foreach($moderating->toArray() as $t) {
            array_push($ids, $t['id']);
        }
        $observed = $user->observed_tutorials()->whereNotIn('tutorial_id', $ids)->get();
        return array('my' => $own, 'moderating' => $moderating, 'observed' => $observed);
    }
}