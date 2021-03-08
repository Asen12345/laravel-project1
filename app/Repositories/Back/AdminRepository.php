<?php

namespace App\Repositories\Back;

use App\Eloquent\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Str;

/**
 * Class UserRepository.
 */
class AdminRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Admin::class;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function createUserAdmin (array $data) {

        $active = !empty($data['active']) ? 1 : 0;

        return $this->model->create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'permission'     => $data['role'],
            'active'         => $active,
        ]);

    }

    public function createUserRedactor (array $data) {

        $active = !empty($data['active']) ? 1 : 0;

        return $this->model->create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'role'         => $data['role'],
            'active'       => $active,
        ]);
    }

    public function multiSort ($value) {

        return $this->model->where(function ($query) use ($value) {
            foreach ($value as $key => $val) {
                if ($key === 'page') {
                    continue;
                }
                if (is_null($val)) {
                    $query->where($key, 'LIKE', '%' .''. '%');
                } elseif (Str::contains($key, '_id')) {
                    $query->where($key, $val);
                } else {

                    if ($key === 'name') {
                        $query->where('name', 'LIKE', '%' . $val . '%')
                            ->orWhere('email', 'LIKE', '%' . $val . '%');
                    } else {
                        $query->where($key, 'LIKE', '%' . $val . '%');
                    }
                }
            }
        });

    }
}
