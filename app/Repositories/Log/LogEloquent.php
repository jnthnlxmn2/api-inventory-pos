<?php

namespace App\Repositories\Log;

use App\Log;
use App\Repositories\Common\Eloquent\EloquentRepository;

class LogEloquent extends EloquentRepository implements LogRepository
{
    protected $model;

    public function __construct(Log $Log)
    {
        $this->model = $Log;
        $this->options = ['paginate' => 15, 'limit' => 0, 'order' => 'desc'];
    }

    public function getAll($options = [])
    {
        $options = $this->getOptions($options);
        return $this->model->orderBy('created_at', 'desc')
            ->limit($options['limit'])
            ->paginate($options['paginate']);
    }

}
