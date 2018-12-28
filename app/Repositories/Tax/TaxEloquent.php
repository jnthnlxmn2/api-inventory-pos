<?php

namespace App\Repositories\Tax;

use App\Repositories\Common\Eloquent\EloquentRepository;
use App\Tax;

class TaxEloquent extends EloquentRepository implements TaxRepository
{
    protected $model;

    public function __construct(Tax $tax)
    {
        $this->model = $tax;
        $this->options = ['paginate' => 15, 'limit' => 0, 'order' => 'desc'];
    }

    public function getAll($options = [])
    {
        $options = $this->getOptions($options);
        return $this->model->orderBy('created_at', 'DESC')
            ->orderBy('created_at', 'desc')
            ->limit($options['limit'])
            ->paginate($options['paginate']);

    }

}
