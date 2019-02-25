<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    private $model;
    private $rules;

    public function __construct($model, $rules)
    {
        $this->model = $model;
        $this->rules = $rules;
    }

    public function get()
    {
        return $this->model::all();
    }

    public function getById($id)
    {
        return $this->model::findOrFail($id);
    }

    public function add(Request $request)
    {
        $this->validate($request, $this->rules);
        $lastInsertedRow = $this->model::create($request->all());
        return $lastInsertedRow;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);
        $update = $this->model::findOrFail($id);
        $update->fill($request->all())->save();
        return $update;
    }

    public function delete($id)
    {
        $delete = $this->model::findOrFail($id);
        $result = $delete->delete();
        return response()->json($result);
    }

    public function deleteMany(Request $request) {
        $result = true;
        foreach($request->input('ids', []) as $id) {
            $result &= $this->model::findOrFail($id)->delete();
        }
        return response()->json($result);
    }

    public function updateMany(Request $request) {
        $result = 0;
        foreach($request->all() as $id => $value) {
            $result += $this->model::where('id', $id)->update($value);
        }
        return $result;
    }
}
