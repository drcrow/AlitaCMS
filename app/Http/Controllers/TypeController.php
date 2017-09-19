<?php

namespace App\Http\Controllers;
use App\Type as Type;

class TypeController extends Controller
{
    public function show($id)
    {
        return Type::findOrFail($id);
    }

    public function all(){
    	return Type::getAllTypes();
    }
}
