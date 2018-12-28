<?php

namespace App\Repositories\UnitType;

use App\UnitType;
use App\Repositories\Common\Eloquent\EloquentRepository;
use Auth;
class UnitTypeEloquent extends EloquentRepository implements UnitTypeRepository{
    protected $model;

    public function __construct(UnitType $unit_type){
        $this->model = $unit_type;
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