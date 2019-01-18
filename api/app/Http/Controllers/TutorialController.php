<?php

namespace App\Http\Controllers;

use App\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    protected $tutorial;
    protected $rules = [];

    public function __construct(Tutorial $tutorial)
    {
        parent::__construct($tutorial, $this->rules);
        $this->tutorial = $tutorial;
    }

    public function add(Request $request)
    {
        return parent::add($request);
//        foreach ($request['question'])
    }

}
