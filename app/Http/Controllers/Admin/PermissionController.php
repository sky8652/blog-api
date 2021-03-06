<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Transformers\Admin\PermissionTransformer;
use Illuminate\Database\Eloquent\Builder;

class PermissionController extends Controller
{
    public function index()
    {
        $pageSize = min(request('pageSize', 10), 20);

        $permissions = Permission::query()
            ->when(request('name'), function (Builder $builder, $name) {
                $builder->where('name', $name);
            })
            ->paginate($pageSize);

        return $this->response->paginator($permissions, new PermissionTransformer);

    }

    public function store(PermissionRequest $request)
    {
        $permission = new Permission;
        $permission->name = $request->input('name');
        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permission->save();

        $data = ['status' => true];

        return $this->response->created('', compact('data'));
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->name = $request->input('name');
        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permission->save();

        $data = ['status' => true];

        return compact('data');
    }

    public function allPermissions()
    {
        $permissions = Permission::all();

        return $this->response->collection($permissions, new PermissionTransformer);
    }
}