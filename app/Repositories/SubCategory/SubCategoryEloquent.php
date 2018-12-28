<?php

namespace App\Repositories\SubCategory;

use App\Category;
use App\Repositories\Common\Eloquent\EloquentRepository;
use App\SubCategory;

class SubCategoryEloquent extends EloquentRepository implements SubCategoryRepository
{
    protected $model;

    public function __construct(subCategory $subCategory, Category $category)
    {
        $this->model = $subCategory;
        $this->category = $category;

        $this->options = ['paginate' => 15, 'limit' => 0, 'order' => 'desc'];
    }

    public function getAll($options = [])
    {
        $options = $this->getOptions($options);
        $sub_categories = $this->model->orderBy('created_at', 'DESC')
            ->orderBy('created_at', 'desc')
            ->limit($options['limit'])
            ->paginate($options['paginate']);
        foreach ($sub_categories as $sub_category) {
            $category = $this->category->where('id', $sub_category['category_id'])
                ->get()->first();
            $sub_category['category'] = $category;
        }

        return $sub_categories;

    }
    public function getByCategoryId($id, $options = [])
    {
        $options = $this->getOptions($options);
        return $this->model->where('category_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit($options['limit'])
            ->paginate($options['paginate']);
    }
}
