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

        $this->adminPermissions();
        $this->advisorPermissios();
        $this->enagicPermissions();
        $this->allMemberPermissions();
    }

    private function adminPermissions()
    {
        $adminRole = $this->roleModel::whereName(ADMIN_ROLE)->first();
        $adminPermissions = config("permission_list.admin_permissions");
        $adminRole->givePermissionTo($adminPermissions);
    }

    private function advisorPermissios()
    {
        $advisorRole = $this->roleModel::whereName(ADVISOR_ROLE)->first();
        $advisorPermissions = config("permission_list.advisor_permissions");
        $advisorRole->givePermissionTo($advisorPermissions);
    }

    private function enagicPermissions()
    {
        $enagicRole = $this->roleModel::whereName(ENAGIC_ROLE)->first();
        $enagicPermissions = config("permission_list.enagic_permissions");
        $enagicRole->givePermissionTo($enagicPermissions);
    }

    private function allMemberPermissions() //edit it
    {
        $permissionsExceptAllMember = config("permission_list.all_members");
        $allMember = $this->roleModel::whereName(ALL_MEMBER_ROLE)->first();
        $allMember->givePermissionTo($permissionsExceptAllMember);
    }
}
