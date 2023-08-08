<?php

namespace App\Repositories;

use App\Contracts\Repositories\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CourseRepository implements CourseRepositoryInterface
{

    private Model $courseModel;

    public function __construct(Model $courseModel)
    {
        $this->courseModel = $courseModel;
        $this->roleModel = app(config('permission.models.role'));
    }

    public function getCourseCategories()
    {
        $currenUser = currentUser();

        $courseCategories = $this->roleModel::query()
            ->when($currenUser->hasRole(ADMIN_ROLE), fn($q) => $q->selectRaw("roles.*, (true) as has_access"))
            ->when(!$currenUser->hasRole(ADMIN_ROLE), function ($q) use ($currenUser) {
                $q->selectRaw("roles.*, (SELECT count(*) from model_has_roles where model_has_roles.role_id = roles.id and model_has_roles.model_type = 'User' and model_has_roles.model_id = " . $currenUser->id . " limit 1 ) as has_access");
            })
            ->excludeAdminRole()
            ->get();

        $courseCategories->map(function ($role) {
            $role->name = ($role->name == CORE_ROLE) ? $role->name . " Rank Course" : $role->name . " Course";
        });

        return $courseCategories;
    }

}
