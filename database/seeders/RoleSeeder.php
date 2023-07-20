<?php

namespace Database\Seeders;

use App\Traits\CommonServices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use CommonServices;

    public function run()
    {
        $roles = array(
            ['name' => ADMIN_ROLE],
            ['name' => ENAGIC_ROLE],
            ['name' => TRIFECTA_ROLE],
            ['name' => ADVISOR_ROLE],
            ['name' => CORE_ROLE],
            ['name' => ACTIVE_RECRUITER_ROLE],
        );

        $RoleModel = app(config('permission.models.role'));

        foreach ($roles as &$role) {
            $role['uuid'] = $this->generateUniqueUUID(config('permission.models.role'));
            $role['guard_name'] = 'web';
            $role['created_at'] = $role['updated_at'] = now();
        }

        $RoleModel::insert($roles);
    }
}
