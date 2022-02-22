<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Requests\StoreRole;
use App\Http\Requests\UpdateRole;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_roles')->only(['index']);
        $this->middleware('permission:create_roles')->only(['create', 'store']);
        $this->middleware('permission:update_roles')->only(['edit', 'update']);
        $this->middleware('permission:delete_roles')->only(['delete', 'bulk_delete']);

    }// end of __construct

    public function index()
    {
        return view('admin.roles.index');

    }// end of index

    public function data()
    {
        $roles = Role::whereNotIn('name', ['super_admin', 'admin', 'user'])
                     ->withCount(['users']);

        return DataTables::of($roles)
                         ->addColumn('record_select', 'admin.roles.data_table.record_select')
                         ->editColumn('created_at', function (Role $role) {
                             return $role->created_at->format('Y-m-d');
                         })
                         ->addColumn('actions', 'admin.roles.data_table.actions')
                         ->rawColumns(['record_select', 'actions'])
                         ->toJson();

    }// end of data

    public function create()
    {
        return view('admin.roles.create');

    }// end of create

    public function store(StoreRole $request)
    {
        $role = Role::create([
                                 'name'         => $request->name,
                                 'display_name' => ucwords($request->name),
                                 'description'  => ucwords($request->name)
                             ]);
        $role->attachPermissions($request->permissions);

        session()->flash('success', __('Added Successfully'));
        return redirect()->route('admin.roles.index');

    }// end of store

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));

    }// end of edit

    public function update(UpdateRole $request, Role $role)
    {
        $role->update($request->only(['name']));
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }
        session()->flash('success', __('Updated successfully'));
        return redirect()->route('admin.roles.index');

    }// end of update

    public function destroy(Role $role)
    {
        $this->delete($role);
        session()->flash('success', __('Deleted Successfully'));
        return response(__('Deleted successfully'));

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $role = Role::FindOrFail($recordId);
            $this->delete($role);

        }//end of for each

        session()->flash('success', __('Deleted successfully'));
        return response(__('Deleted successfully'));

    }// end of bulkDelete

    private function delete(Role $role)
    {
        $role->delete();

    }// end of delete

}//end of controller
