<?php

namespace App\Repositories\Customer;

use App\Customer;
use App\Repositories\Common\Eloquent\EloquentRepository;
use Auth;
class CustomerEloquent extends EloquentRepository implements CustomerRepository{
    protected $model;

    public function __construct(Customer $Categor){
        $this->model = $Categor;
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