<?php

namespace App\Repositories\Back;

use App\Eloquent\AdminHasPermission;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class PermissionRepository.
 */
class AdminHasPermissionRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return AdminHasPermission::class;
    }

    public function updatePermissions($user_id, $permissions) {
        $this->model->where('user_id', $user_id)->delete();
        if (!empty($permissions)){
            foreach ($permissions as $key => $permission) {
                $this->model->create([
                    'user_id'        => $user_id,
                    'permission_id'  => $key,
                ]);
            }
        }
    }
}
