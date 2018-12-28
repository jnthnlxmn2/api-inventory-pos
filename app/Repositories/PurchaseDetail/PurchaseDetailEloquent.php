<?php

namespace App\Repositories\PurchaseDetail;

use App\PurchaseDetail;
use App\Repositories\Common\Eloquent\EloquentRepository;
use Auth;
class PurchaseDetailEloquent extends EloquentRepository implements PurchaseDetailRepository{
    protected $model;

    public function __construct(PurchaseDetail $Purchase){
        $this->model = $Purchase;
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