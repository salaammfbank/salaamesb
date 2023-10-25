<?php

namespace Noorfarooqy\SalaamEsb\Services;

use Illuminate\Support\Facades\Hash;
use Noorfarooqy\LaravelOnfon\Services\DefaultService;
use Noorfarooqy\LaravelOnfon\Traits\RequestHandler;
use Noorfarooqy\SalaamEsb\Contracts\ModulesEnum;
use Noorfarooqy\SalaamEsb\Contracts\PermissionsEnum;
use Noorfarooqy\SalaamEsb\Models\User;
use Spatie\Permission\Models\Role;

class UserServices extends DefaultService
{
    use RequestHandler;
    public function GetUsersList($request)
    {
        $this->request = $request;

        $this->setResponseType();

        if ($request->self == 1) {
            $users = User::latest()->paginate($request->paginate ?? 20);
        } else {
            $users = User::where('id', '!=', $request?->user()?->id)->latest()->paginate($request->paginate ?? 20);
        }


        return $this->getResponse($users);
    }

    public function AddNewUser($request)
    {
        $this->request = $request;
        $this->setResponseType();

        $authServices = new AuthenticationServices();

        return $authServices->register($request);
    }

    public function UpdateUserStatus($request, $user_id)
    {
        $this->request = $request;
        $this->setResponseType();

        if (!$user_id) {
            $this->setError($m = "User id is required");
            return $this->getResponse();
        }

        $user = User::find($user_id);
        if (!$user) {
            $this->setError($m = "User provided cannot be found");
            return  $this->getResponse();
        }
        $user->is_active = !$user->is_active;
        $user->save();

        $this->setError('', 0);
        $this->setSuccess('success');
        return $this->getResponse($user);
    }

    public function GetRolesList($request)
    {
        $this->request = $request;
        $this->setResponseType();

        $roles = Role::latest()->paginate($request->paginate ?? 20);

        $this->setError('', 0);
        $this->setSuccess('success');

        return  $this->getResponse($roles);
    }

    public function AddNewRole($request)
    {
        $this->request = $request;
        $this->setResponseType();

        $this->rules = [
            'name' => 'required|string|unique:roles,name',
        ];

        $this->CustomValidate();
        if ($this->has_failed) {
            $this->setError($this->getMessage());
            return $this->getResponse();
        }
        $data = $this->ValidatedData();

        try {
            $role = Role::create(['name' => $data['name']]);

            $this->setError('', 0);
            $this->setSuccess('success');

            return $this->getResponse($role);
        } catch (\Throwable $th) {
            $this->setError($m = env('APP_DEBUG') ? $th->getMessage() : 'Oops! Server error. Cannot create role. Contact IT support');
            return $this->getResponse();
        }
    }

    public function GetPermissions($request)
    {
        $this->request = $request;
        $this->setResponseType();

        $permissions = PermissionsEnum::toJson();
        $modules = ModulesEnum::toJson();
        $data = [
            'permissions' => $permissions,
            'modules' => $modules,
        ];

        $this->setError('', 0);
        $this->setSuccess('success');
        return $this->getResponse($data);
    }

    public function GetRolePermissions($request, $role_id)
    {
        $this->request = $request;
        $this->setResponseType();

        $role = Role::find($role_id);

        $permissions = $role?->permissions?->pluck('name') ?? [];

        $this->setError('', 0);
        $this->setSuccess('success');

        return $this->getResponse($permissions);
    }

    public function UpdateRolePermission($request, $role_id)
    {
        $this->request = $request;
        $this->setResponseType();

        $this->rules = [
            'permission' => 'required|string|in:' . PermissionsEnum::toString(),
            'module' => 'required|string|in:' . ModulesEnum::toString(),
            'action' => 'required|integer|in:0,1',
        ];
        $this->CustomValidate();
        if ($this->has_failed) {
            $this->setError($this->getMessage());
            return $this->getResponse();
        }

        $data = $this->ValidatedData();

        $role = Role::find($role_id);
        if (!$role) {
            $this->setError($m = "The role does not exists");
            return $this->getResponse();
        }

        $permission = $data['permission'] . '_' . $data['module'];
        if ($role->hasPermissionTo($permission) && $data['action'] == 1) {
            $this->setError($m = 'Role has permission assigned already');
            return $this->getResponse();
        }
        if (!$role->hasPermissionTo($permission) && $data['action'] == 0) {
            $this->setError($m = 'Permission is not given to the role previously. Cannot revoke');
            return $this->getResponse();
        }

        if ($data['action']) {
            $role->givePermissionTo($permission);
        } else {
            $role->revokePermissionTo($permission);
        }
        $role = Role::find($role_id);

        $permissions = $role?->permissions?->pluck('name') ?? [];

        $this->setError('', 0);
        $this->setSuccess('success');

        return $this->getResponse($permissions);
    }

    public function GetUserRoles($request, $user_id)
    {
        $this->request = $request;
        $this->setResponseType();

        $user = User::find($user_id);
        if (!$user) {
            $this->setError($m = "User not found");
            return $this->getResponse();
        }
        $roles = $user->roles->pluck('name');

        $this->setError('', 0);
        $this->setSuccess('success');

        return $this->getResponse($roles);
    }

    public function UpdateUserRoles($request, $user_id)
    {
        $this->request = $request;
        $this->setResponseType();

        $this->rules = [
            'role' => 'required|integer|exists:roles,id',
            'action' => 'required|integer|in:0,1',
        ];
        $this->CustomValidate();
        if ($this->has_failed) {
            $this->setError($this->getMessage());
            return $this->getResponse();
        }
        $data = $this->ValidatedData();

        $user = User::find($user_id);
        if (!$user) {
            $this->setError($m = "User not found");
            return $this->getResponse();
        }
        $role = Role::find($data['role']);
        if ($user->hasRole($role) && $data['action']) {
            $this->setError($m = "User is already assigned to this role");
            return $this->getResponse();
        }

        if (!$user->hasRole($role) && !$data['action']) {
            $this->setError($m = "User is not assigned to this role. Cannot revoke ");
            return $this->getResponse();
        }
        if ($data['action']) {
            $user->assignRole($role);
        } else {
            $user->removeRole($role);
        }

        $roles = $user->roles->pluck('name');

        $this->setError('', 0);
        $this->setSuccess('success');

        return  $this->getResponse($roles);
    }

    public function DroprolePermission($request, $role_id)
    {
        $this->request = $request;
        $this->setResponseType();

        $this->rules = [
            'role' => 'required|integer|exists:roles,id',
        ];
        $this->CustomValidate();
        if ($this->has_failed) {
            $this->setError($this->getMessage());
            return $this->getResponse();
        }

        $data = $this->ValidatedData();

        $role = Role::find($role_id);
        if (!$role) {
            $this->setError($m = "The role does not exists");
            return $this->getResponse();
        }

        $permissions = $role->permissions;
        $role->revokePermissionTo($permissions);

        $this->setError('', 0);
        $this->setSuccess('success');

        return $this->getResponse($permissions);
    }

    public function UpdateUserSystemStatus($request, $user_id)
    {
        $this->request = $request;
        $this->setResponseType();

        $user = User::find($user_id);
        if (!$user) {
            $this->setError($m = "User not found");
            return $this->getResponse();
        }

        // $user->update(['is_system' => !$user->is_system]);
        $user->is_system = !$user->is_system;
        $user->save();
        $this->setError('', 0);
        $this->setSuccess('success');

        $action = $user->is_system ? 'System user' : 'Non-system user';
        $data = [
            'message' => 'The user has been set to ' . $action,
            'user' => $user,
        ];
        return $this->getResponse($data);
    }

    public function GetProfileDetails($request)
    {
        $this->request = $request;
        $this->setResponseType();

        $user = $request->user();

        return $this->getResponse($user);
    }

    public function ResetProfilePassword($request)
    {
        $this->request = $request;
        $this->setResponseType();

        $this->rules = [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ];

        $this->customValidate();
        if ($this->has_failed) {
            $this->setError($m = $this->getMessage());
            return  $this->getResponse();
        }
        $data = $this->validatedData();

        $user = User::find($request->user()->id);
        if (!$user) {
            $this->setError($m = 'User not found');
            return  $this->getResponse();
        }

        if (!Hash::check($data['old_password'], $user->password)) {
            $this->setError($m = 'Invalid old password');
            return $this->getResponse();
        }

        if (Hash::check($data['password'], $user->password)) {
            $this->setError($m = 'The old password and the new password cannot be the same');
            return $this->getResponse();
        }

        $hashed_password = Hash::make($data['password']);
        $user->update(['password' => $hashed_password, 'force_password_reset' => false]);

        $this->setError('', 0);
        $this->setSuccess('success');
        return $this->getResponse($user);
    }

    public function UpdateProfile($request)
    {
        $this->request = $request;
        $this->setResponseType();

        $this->rules = [
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string|max:124',
            'department' => 'required|integer|exists:departments,id',
        ];

        $this->customValidate();
        if ($this->has_failed) {
            $this->setError($m = $this->getMessage());
            return  $this->getResponse();
        }
        $data = $this->validatedData();

        $user = User::find($request->user()->id);
        if (!$user) {
            $this->setError($m = 'User not found');
            return $this->getResponse();
        }

        if (!$user->is_active) {
            $this->setError($m = 'User account is not active');
            return $this->getResponse();
        }
        $user->update($data);

        return $this->getResponse($user);
    }
}
