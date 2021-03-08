<?php
namespace App\Repositories\Traits;

trait Join
{
    protected $join;

    public function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false)
    {
        $this->unsetClauses();

        //$this->newQuery()->eagerLoad();

        $this->model = $this->model->join($table, $first, $operator, $second, $type, $where);

        return $this;
    }

}