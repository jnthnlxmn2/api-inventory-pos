<?php

namespace App\Repositories\Manufacturer;

use App\Manufacturer;
use App\Repositories\Common\Eloquent\EloquentRepository;
use Auth;
class ManufacturerEloquent extends EloquentRepository implements ManufacturerRepository{
    protected $model;

    public function __construct(Manufacturer $Manufacturer){
        $this->model = $Manufacturer;
        $this->options = ['paginate' => 15,'limit' => 0,'order' => 'desc'];
    }


    public function getAll($options = []){
        $options = $this->getOptions($options);
        return $this->model->orderBy('created_at','DESC')
                    ->orderBy('created_at','desc')
                    ->limit($options['limit'])
                    ->paginate($options['paginate']);
        }

    
    
}
?>