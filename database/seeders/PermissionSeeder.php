<?php

namespace Database\Seeders;

use Database\Seeders\Traits\{DisableForeignKeys, TruncateTable};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    private $permissionModel;
    private $roleModel;

    public function __construct()
    {
        $this->permissionModel = app(config("permission.models.permission"));
        $this->roleModel = app(config('permission.models.role'));
    }

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple([
            config("permission.table_names.permissions"),
            config("permission.table_names.role_has_permissions")
        ]);
        $this->enableForeignKeys();

        $permissions = config("permission_list.permissions");

        collect($permissions)->each(function ($permission) {
            Permission::create($permission);
        });

        //Admin Roles
        $adminRole = $this->roleModel::whereName(ADMIN_ROLE)->first();
        $adminPermissions = config("permission_list.admin_permissions");
        $adminRole->givePermissionTo($adminPermissions);

        //Advisor Roles
        $advisorRole = $this->roleModel::whereName(ADVISOR_ROLE)->first();
        $advisorPermissions = config("permission_list.advisor_permissions");
        $advisorRole->givePermissionTo($advisorPermissions);

        //Enagic Roles
        $enagicRole = $this->roleModel::whereName(ENAGIC_ROLE)->first();
        $enagicPermissions = config("permission_list.enagic_permissions");
        $enagicRole->givePermissionTo($enagicPermissions);

        //Common Roles
        $permissionsExceptAllMember = config("permission_list.except_all_member_role");
        $rolesExceptAllMember = $this->roleModel::excludeAllMemberRole()->get();
        $rolesExceptAllMember->each(function ($role) use ($permissionsExceptAllMember) {
            $role->givePermissionTo($permissionsExceptAllMember);
        });

        /*
         admin_permissions
         enagic_permissions
         advisor_permissions
         except_all_member_role
        */
    }
}
