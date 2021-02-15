<?php

namespace App\Http\Controllers\api\v1\admin\role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public $error = ['message' => 'failed', 'data' => null];
    public $success = ['message' => 'success', 'data' => null];

    public function store(Request $request)
    {
        $result = Role::query()->create([
            'name' => $request->name
        ]);
        return $result ?
            response($this->success,201) :
            response($this->error);
    }
}
