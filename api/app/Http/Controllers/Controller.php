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
}
