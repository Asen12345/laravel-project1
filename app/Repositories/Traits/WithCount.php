<?php
namespace App\Repositories\Traits;

trait WithCount
{
    protected $withCount = [];

    /**
     * @param $relations
     * @return $this
     */
    public function withCount($relations)
    {
        $this->unsetClauses();

        $this->model = $this->model->withCount($relations);

        return $this;
    }

}