<?php

namespace App\Http\Controllers\api\v1\admin\role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\DeleteRoleValidation;
use App\Http\Requests\Role\StoreRoleValidation;
use App\Http\Requests\Role\UpdateRoleValidation;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    //TODO DEFINE THE ALL PERMISSIONS OF THE SYSTEM TO NOW
    public $error = ['message' => 'failed', 'data' => null];
    public $success = ['message' => 'success', 'data' => null];

    public function index(Request $request){

//        is the request is not  for search
        if (!$request->has('search')){

//            are we need for the select box ?
            if($request->has('select_box')){
                $all_roles=Role::all();
            }else{
                $all_roles=Role::query()->paginate(1);
            }
        }else{

//            search in roles
            $all_roles=Role::query()->where('name','like','%'.$request->search.'%')
                ->orWhere('fa_name','like','%'.$request->search.'%')
            ->get();
        }
        return $all_roles->isNotEmpty()?
            response(['message'=>'success','data'=>$all_roles]):
            response(['message'=>'success','data'=>null],204);
    }


    public function store(StoreRoleValidation $request)
    {
        $result = Role::query()->create([
            'name' => $request->name,
            'fa_name'=>$request->fa_name,
        ]);
        return $result ?
            response($this->success, 201) :
            response($this->error);
    }


    public function update(Role $role, UpdateRoleValidation $request)
    {

        $result = $role->update([
            'name' => $request->name,
            'fa_name'=>$request->fa_name,
        ]);
        return $result ?
            response($this->success) :
            response($this->error);
    }




    public function deleteMultiple(DeleteRoleValidation $request)
    {

        $result = Role::query()->whereIn('id', $request->ids)->delete();

        return $result ?
            response($this->success) :
            response($this->error);
    }
}
