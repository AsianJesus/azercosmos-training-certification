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
    protected $filters = [
      'start_date' => '<=',
      'end_date' => '>=',

    ];
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

    public function applyFilters($where, $filters) {
        foreach($filters as $filter) {
            $where->where($filter['name'], $filter['op'] ?? $this->filters[$filter['name']] ?? '=', $filter['value']);
        }
        return $where;
    }

    // Filters are passed as array of associative arrays
    // [
    //  ['name' => name, 'op' => op (optional, e.g. '=', '<>'), 'value' => value]
    // ]
    // Four types of filters: g(global)_filters, o(observing)_filters, m(my)_filters, p(participating)_filters

    public function getMyTrainings(Request $request, $id) {
        $user = $this->user::findOrFail($id);
        $my = $this->applyFilters($user->own_trainings(), $request->input('filters', []));
        $my->orderBy($request->input('orderBy', 'id'), $request->input('order', 'desc'));
        return $my->with('participants')->paginate($request->input('amount', 5));
    }
    public function getParticipatingTrainings(Request $request, $id) {
        $user = $this->user::findOrFail($id);
        //$participating = $user->participating_trainings();
        $participating = $this->applyFilters($user->participating_trainings(), $request->input('filters', []));
        $participating->orderBy($request->input('orderBy', 'id'), $request->input('order', 'desc'));
        return $participating->with('training')->paginate($request->input('amount', 5));
    }

    public function getObservingTrainings(Request $request, $id) {
        $user = $this->user::findOrFail($id);
        $observing = $this->applyFilters($user->observed_trainings(), $request->input('g_filters', []));
        $observing->orderBy($request->input('orderBy', 'id'), $request->input('order', 'desc'));
        return $observing->with('participants')->paginate($request->input('amount', 5));
    }
}