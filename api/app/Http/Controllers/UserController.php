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
      'title' => 'LIKE',
      'description' => 'LIKE',
      'reference' => 'LIKE',
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

    public function applyFilters($filters, $table = null) {
        return function ($query) use ($filters, $table) {
            foreach ($filters as $filter) {
                $filter = is_string($filter) ? (array)json_decode($filter) : $filter;
                $query->where($table ? $table.'.'.$filter['name'] : $filter['name'], $filter['op'] ?? $this->filters[$filter['name']] ?? '=', $filter['value']);
            }
        };
    }

    // Filters are passed as array of associative arrays
    // [
    //  ['name' => name, 'op' => op (optional, e.g. '=', '<>'), 'value' => value]
    // ]

    public function getParticipatingTrainings(Request $request, $id) {
        $user = $this->user::findOrFail($id);
        $participating = $user->participating_trainings();
        if ($request->input('t_filters')) {
            $participating->whereHas('training', $this->applyFilters($request->input('t_filters'), 'trainings'));
        }
        if ($request->input('p_filters')) {
            $participating->where($this->applyFilters($request->input('p_filters')));
        }
        $participating->orderBy($request->input('orderBy', 'id'), $request->input('order', 'desc'));
        return $participating->with('training')->paginate($request->input('amount', 20));
    }

    public function getObservingTrainings(Request $request, $id) {

        $user = $this->user::findOrFail($id);
        $observing = $user->observed_trainings();
        $filters = $request->input('filters');
        if ($filters) {
            $observing->where($this->applyFilters($filters));
        }
        $observing->orderBy($request->input('orderBy', 'id'), $request->input('order', 'desc'));
        $result = $observing->with('participants', 'observers.user')
            ->paginate($request->input('amount', 20))->toArray();
        return $result;
    }
}