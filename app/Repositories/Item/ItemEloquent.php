<?php

namespace App\Repositories\Item;

use App\Item;
use App\ItemQuantity;
use App\Log;
use App\Repositories\Common\Eloquent\EloquentRepository;
use Auth;
use DateTime;

class ItemEloquent extends EloquentRepository implements ItemRepository
{
    protected $model;

    public function __construct(Item $Item, Log $Log, ItemQuantity $Item_Quantity)
    {
        $this->log = $Log;
        $this->model = $Item;
        $this->item_quantity = $Item_Quantity;
        $this->options = ['paginate' => 15, 'limit' => 0, 'order' => 'desc'];
    }

    public function save($attr = [])
    {
        $this->model->fill($attr);
        $this->model->save();
        $purchase = $this->model->latest()->first();

        $log = [
            'table' => 'items',
            'action' => 'insert',
            'field' => '',
            'old_value' => '',
            'new_value' => $purchase,
            'user_id' => Auth::user()->id,
            'created_at' => new DateTime(),
        ];

        $this->log->insert($log);

        return $purchase;

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
        $items = $this->model->orderBy('created_at', 'DESC')
            ->orderBy('created_at', 'desc')
            ->limit($options['limit'])
            ->paginate($options['paginate']);

            $count = count($items);

            for ($x = 0; $x < $count; $x++) {
                $item_quantity = $this->item_quantity->where('item_id', $items[$x]['id'])
                ->get();

                $items[$x]['item_stocks'] = $item_quantity;

                $count2 = count($item_quantity);
                $total = 0;
                for ($y = 0; $y < $count2; $y++) {
                    $total = $total + $item_quantity[$y]['quantity'];
                }
                
                $items[$x]['total_item_stock_quantity'] = $total;
                $total = 0 ;
            }

            return $items;

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
