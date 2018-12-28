<?php

namespace App\Repositories\Discount;

use App\Discount;
use App\Repositories\Common\Eloquent\EloquentRepository;

class DiscountEloquent extends EloquentRepository implements DiscountRepository
{
    protected $model;

    public function __construct(Discount $discount)
    {
        $this->model = $discount;
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
