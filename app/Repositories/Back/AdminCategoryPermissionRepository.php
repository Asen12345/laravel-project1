<?php

namespace App\Repositories\Back;

use App\Eloquent\AdminCategoryPermission;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class PermissionRepository.
 */
class AdminCategoryPermissionRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return AdminCategoryPermission::class;
    }

    public function updateCategoryPermissions($user_id, $permissions) {
        $this->model->where('user_id', $user_id)->delete();
        if (!empty($permissions)) {
            foreach ($permissions as $key => $permission) {
                $this->model->create([
                    'user_id'     => $user_id,
                    'category_id' => $key,
                ]);
            }
        }
    }
}
