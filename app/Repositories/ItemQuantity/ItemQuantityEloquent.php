<?php

namespace App\Repositories\ItemQuantity;

use App\ItemQuantity;
use App\Log;
use App\Repositories\Common\Eloquent\EloquentRepository;
use Auth;
use DateTime;

class ItemQuantityEloquent extends EloquentRepository implements ItemQuantityRepository
{
    protected $model;

    public function __construct(ItemQuantity $ItemQuantity, Log $Log)
    {
        $this->log = $Log;
        $this->model = $ItemQuantity;
        $this->options = ['paginate' => 15, 'limit' => 0, 'order' => 'desc'];
    }

    public function save($attr = [])
    {
        
        $this->model->fill($attr);
        $this->model->save();
        $item_quantity = $this->model->latest()->first();

        $log = [
            'table' => 'refill',
            'action' => 'insert',
            'field' => '',
            'old_value' => '',
            'new_value' => $item_quantity,
            'user_id' => Auth::user()->id,
            'created_at' => new DateTime(),
        ];

        $this->log->insert($log);

        return $item_quantity;

    }
    public function update($id, $attr = [])
    {
        $item_old = $this->model->where('id', $id)
            ->get()
            ->first();

        $item_update = $this->model->where('id', $id)
            ->update($attr);

        $item_updated = $this->model->where('id', $id)
            ->get()
            ->first();

        $log = [
            'table' => 'items',
            'action' => 'update',
            'field' => '',
            'old_value' => $item_old,
            'new_value' => $item_updated,
            'user_id' => Auth::user()->id,
            'created_at' => new DateTime(),
        ];

        $this->log->insert($log);

        return $item_update;

    }

    public function delete($id)
    {
        $item_old = $this->model->where('id', $id)
            ->get()
            ->first();

        $log = [
            'table' => 'items',
            'action' => 'delete',
            'field' => '',
            'old_value' => $item_old,
            'new_value' => 'deleted',
            'user_id' => Auth::user()->id,
            'created_at' => new DateTime(),
        ];

        $this->log->insert($log);

        return $deleted = $this->model->where('id', $id)
            ->delete();
    }

    public function getAll($options = [])
    {
        $options = $this->getOptions($options);
        return $this->model->orderBy('created_at', 'DESC')
            ->orderBy('created_at', 'desc')
            ->limit($options['limit'])
            ->paginate($options['paginate']);
    }

    public function refill($id, $attr = [])
    {
        $item_old = $this->model->where('id', $id)
            ->get()
            ->first();

        $total_refill = $attr['stock'] + $item_old->total_stock;

        $data = ['total_stock' => $total_refill];

        $item_update = $this->model->where('id', $id)
            ->update($data);

        $item_updated = $this->model->where('id', $id)
            ->get()
            ->first();

        $log = [
            'table' => 'items',
            'action' => 'refill',
            'field' => '',
            'old_value' => $item_old,
            'new_value' => $item_updated,
            'user_id' => Auth::user()->id,
            'created_at' => new DateTime(),
        ];

        $this->log->insert($log);

        return $item_update;
    }

}
