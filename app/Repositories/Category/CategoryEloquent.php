<?php

namespace App\Repositories\Category;

use App\Category;
use App\Repositories\Common\Eloquent\EloquentRepository;
use Auth;
class CategoryEloquent extends EloquentRepository implements CategoryRepository{
    protected $model;

    public function __construct(Category $Category){
        $this->model = $Category;
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