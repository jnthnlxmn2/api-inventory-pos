<?php

namespace App\Repositories\Purchase;

use App\Discount;
use App\Item;
use App\ItemQuantity;
use App\Log;
use App\Purchase;
use App\PurchaseDetail;
use App\Repositories\Common\Eloquent\EloquentRepository;
use App\Tax;
use Auth;
use Carbon\Carbon;
use DateTime;

class PurchaseEloquent extends EloquentRepository implements PurchaseRepository
{
    protected $model;
    protected $model2;

    public function __construct(Purchase $Purchase, PurchaseDetail $Details, Item $Item, Log $Log, Tax $Tax, Discount $Discount, ItemQuantity $Item_Quantity)
    {
        $this->tax = $Tax;
        $this->discount = $Discount;
        $this->log = $Log;
        $this->item = $Item;
        $this->item_quantity = $Item_Quantity;
        $this->model2 = $Details;
        $this->model = $Purchase;
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

    public function generatePurchase($params = [])
    {
        if ($params['discount_code'] != null) {
            $discount = $this->discount->whereRaw('BINARY `code`= ?', $params['discount_code'])
                ->get()
                ->first();
            if ($discount) {
                if ($discount->status === 0) {
                    return ['error' => 'DISCOUNT CODE HAS BEEN USED'];
                }
                $products = $params['items'];
                $tracking_number = 'TTM' . $params['code'];
                $discount_code = $params['discount_code'];
                $amount_paid = $params['amount_paid'];
                $total_quantity = 0;
                $total_amount = 0;
                $total_sale = 0;
                $created_by = 1;
                $updated_by = 1;
                $discounted_amount = 0;
                $total_discounted_amount = 0;
                $vat = 0;
                $vatable = 0;
                foreach ($products as $product) {
                    $total_quantity = $total_quantity + $product['total_quantity'];
                    $total_amount = $total_amount + $product['total_amount'];
                    $total_sale = $total_sale + $product['total_sale'];

                    $item_quantity = $this->item_quantity->where('item_id', $product['product_id'])
                        ->get();

                    $count2 = count($item_quantity);
                    $total = 0;
                    $retail_price = 0;
                    for ($y = 0; $y < $count2; $y++) {
                        $total = $total + $item_quantity[$y]['quantity'];
                    }
                    if (($total) < ($product['total_quantity'])) {
                        return ['error' => 'Out of stock'];
                    }
                    $total = 0;

                    $retail_price = 0;

                    $remaining_quantity = ($product['total_quantity']);
                    $remaining_quantity2 = ($product['total_quantity']);
                    $i = 0;
                    do {
                        $product_stock = $this->item_quantity->where('item_id', $product['product_id'])->where('quantity', '!=', 0)
                            ->get()
                            ->first();

                        $product_stock1 = $this->item_quantity->where('id', $product_stock->id)
                            ->get()
                            ->first();

                        return $product_stock1;

                        $remaining_quantity = ($product_stock->quantity) - ($remaining_quantity);
                        if ($remaining_quantity < 0) {
                            $retail_price = $retail_price + $product_stock->quantity * $product_stock->price;
                        } else {
                            $retail_price = $retail_price + $remaining_quantity * $product_stock->price;
                        }
                        $product_stock->quantity = ($product_stock->quantity) - ($remaining_quantity2);
                        $remaining_quantity2 = $remaining_quantity;
                        if ($remaining_quantity < 0) {
                            $product_stock->quantity = 0;
                            $remaining_quantity = abs($remaining_quantity);
                            $remaining_quantity2 = abs($remaining_quantity2);

                        } else {
                            $i = 1;
                        }
                    } while ($i < 1);

                    $net = $product['total_amount'] - $retail_price;

                    $product['net '] = $net;
                    $product['retail_total_price'] = $retail_price;

                    /*   $prod = $this->item->where('id', $product['product_id'])
                ->get()
                ->first();
                if (($prod->total_stock) < ($product['total_quantity'])) {
                return ['error' => 'Out of stock'];
                } */
                }
                if ($discount->type == 'percentage') {
                    $discounted_amount = $total_sale * ($discount->value / 100);
                    $total_discounted_amount = $total_sale - $discounted_amount;
                }
                if ($discount->type == 'exact') {
                    $discounted_amount = $discount->value;
                    $total_discounted_amount = $total_sale - $discounted_amount;
                }
                $tax = $this->tax
                    ->get()
                    ->first();
                $total_tax = $total_sale * ($tax->tax / 100);
                $change = 0;
                if ($total_sale > $amount_paid) {
                    return ['error' => 'insufficient amount'];
                }
                if ($discount) {
                    $change = $amount_paid - $total_discounted_amount;
                    if ($change < 0) {
                        $change = 0;
                    }
                    $this->discount->where('id', $discount->id)
                        ->update(['status' => 0, 'date_used' => Carbon::now()->toDateTimeString()]);
                } else {
                    $change = $amount_paid - $total_sale;
                }
                $vat = ($total_sale * ($tax->tax / 100));
                $vatable = $total_sale - $vat;

                $purchase = [
                    'tracking_number' => $tracking_number,
                    'total_quantity' => $total_quantity,
                    'total_amount' => $total_amount,
                    'tax' => $total_tax,
                    'tax_id' => $tax->id,
                    'vat' => $vat,
                    'vatable' => $vatable,
                    'sale_total_amount' => $total_sale,
                    'amount_paid' => $amount_paid,
                    'change' => $change,
                    'discount_id' => $discount->id,
                    'discounted_amount' => $discounted_amount,
                    'total_discounted_amount' => $total_discounted_amount,
                    'created_by' => $created_by,
                    'updated_by' => $updated_by,
                ];

                $purchase1 = $this->savePurchase($purchase);

                $log2 = [
                    'table' => 'purchase',
                    'action' => 'insert',
                    'field' => '',
                    'old_value' => '',
                    'new_value' => $purchase1,
                    'user_id' => Auth::user()->id,
                    'created_at' => new DateTime(),
                ];

                $this->log->insert($log2);

                $count = count($products);
                for ($x = 0; $x < $count; $x++) {
                    $i = 0;
                    $remaining_quantity = ($products[$x]['total_quantity']);
                    $remaining_quantity2 = ($products[$x]['total_quantity']);
                    do {
                        $products[$x]['purchase_id'] = $purchase1->id;
                        $product_stock = $this->item_quantity->where('item_id', $products[$x]['product_id'])->where('quantity', '!=', 0)
                            ->get()
                            ->first();

                        $product_stock1 = $this->item_quantity->where('id', $product_stock->id)
                            ->get()
                            ->first();
                        $old_product_stock = $product_stock1;

                        $remaining_quantity = ($product_stock->quantity) - ($remaining_quantity);
                        $product_stock->quantity = ($product_stock->quantity) - ($remaining_quantity2);
                        $remaining_quantity2 = $remaining_quantity;
                        if ($remaining_quantity < 0) {
                            $product_stock->quantity = 0;
                            $remaining_quantity = abs($remaining_quantity);
                            $remaining_quantity2 = abs($remaining_quantity2);
                        } else {
                            $i = 1;
                        }
                        $product_update = $this->item_quantity->where('id', $product_stock->id)
                            ->update(['quantity' => $product_stock->quantity]);

                        $product_updated = $this->item_quantity->where('id', $product_stock->id)
                            ->get()
                            ->first();

                        $log = [
                            'table' => 'items_quantity',
                            'action' => 'update',
                            'field' => '',
                            'old_value' => $old_product_stock,
                            'new_value' => $product_updated,
                            'user_id' => Auth::user()->id,
                            'created_at' => new DateTime(),
                        ];

                        $this->log->insert($log);
                    } while ($i < 1);
                }

                $this->model2->insert($products);
                $purchase_details = $this->model2->where('purchase_id', $purchase1->id)
                    ->get();
                $count2 = count($purchase_details);
                for ($y = 0; $y < $count2; $y++) {
                    $product_item = $this->item->where('id', $purchase_details[$y]->product_id)
                        ->get()
                        ->first();

                    $purchase_details[$y]->product = $product_item;
                }
                $purchase1['purchase_details'] = $purchase_details;
                return $purchase1;
            } else {
                return ['error' => 'DISCOUNT CODE NOT EXISTING'];
            }
        } else {

            $products = $params['items'];
            $tracking_number = 'TTM' . $params['code'];
            $discount_code = 'NAN';
            $amount_paid = $params['amount_paid'];
            $total_quantity = 0;
            $total_amount = 0;
            $total_sale = 0;
            $created_by = 1;
            $updated_by = 1;
            $discounted_amount = 0;
            $total_discounted_amount = 0;
            $vat = 0;
            $vatable = 0;

            /* foreach ($products as $product) {
            $total_quantity = $total_quantity + $product['total_quantity'];
            $total_amount = $total_amount + $product['total_amount'];
            $total_sale = $total_sale + $product['total_sale'];
            $prod = $this->item->where('id', $product['product_id'])
            ->get()
            ->first();
            if (($prod->total_stock) < ($product['total_quantity'])) {
            return ['error' => 'Out of stock'];
            }

            }*/
            for ($z = 0; $z < count($products); $z++) {
                $total_quantity = $total_quantity + $products[$z]['total_quantity'];
                $total_amount = $total_amount + $products[$z]['total_amount'];
                $total_sale = $total_sale + $products[$z]['total_sale'];

                $item_quantity = $this->item_quantity->where('item_id', $products[$z]['product_id'])
                    ->get();

                $count2 = count($item_quantity);
                $total = 0;
                $retail_price = 0;
                for ($y = 0; $y < $count2; $y++) {
                    $total = $total + $item_quantity[$y]['quantity'];
                }
                if (($total) < ($products[$z]['total_quantity'])) {
                    return ['error' => 'Out of stock'];
                }
                $total = 0;

                $retail_price = 0;

                $remaining_quantity = ($products[$z]['total_quantity']);
                $remaining_quantity2 = ($products[$z]['total_quantity']);
                $total_quantity1 = ($products[$z]['total_quantity']);
                $i = 0;
                do {
                    $product_stock = $this->item_quantity->where('item_id', $products[$z]['product_id'])->where('quantity', '!=', 0)
                        ->get()
                        ->first();

                    $product_stock1 = $this->item_quantity->where('id', $product_stock->id)
                        ->get()
                        ->first();

                    $remaining_quantity = ($product_stock->quantity) - ($remaining_quantity);

                    if ($remaining_quantity < 0) {
                        $test = $products[$z]['total_sale'] - ($product_stock->price * $product_stock->quantity);
                        $retail_price = $retail_price + $product_stock->quantity * $test;
                        $total_quantity1 = $total_quantity1 - $remaining_quantity;
                    } else {
                        $test = $products[$z]['total_sale'] - ($product_stock->price * $total_quantity1);
                        return $test;
                        $retail_price = $retail_price + $total_quantity1 * $test;
                        $i = 1;
                    }

                    $product_stock->quantity = ($product_stock->quantity) - ($remaining_quantity2);
                    $remaining_quantity2 = $remaining_quantity;
                    if ($remaining_quantity < 0) {
                        $product_stock->quantity = 0;
                        // $remaining_quantity = abs($remaining_quantity);
                        //$remaining_quantity2 = abs($remaining_quantity2);

                    } else {
                        $i = 1;
                    }
                } while ($i < 1);

                $products[$z]['retail_total_price'] = $retail_price;
                $net = $products[$z]['total_amount'] - $retail_price;

                $product['net '] = $net;

                /*   $prod = $this->item->where('id', $product['product_id'])
            ->get()
            ->first();
            if (($prod->total_stock) < ($product['total_quantity'])) {
            return ['error' => 'Out of stock'];
            } */

            }
            return $products;

            $tax = $this->tax
                ->get()
                ->first();

            $total_tax = $total_sale * ($tax->tax / 100);

            $change = 0;
            if ($total_sale > $amount_paid) {
                return ['error' => 'insufficient amount'];
            }
            $change = $amount_paid - $total_sale;

            $vat = ($total_sale * ($tax->tax / 100));
            $vatable = $total_sale - $vat;

            $purchase = [
                'tracking_number' => $tracking_number,
                'total_quantity' => $total_quantity,
                'total_amount' => $total_amount,
                'tax' => $total_tax,
                'tax_id' => $tax->id,
                'vat' => $vat,
                'sale_total_amount' => $total_sale,
                'vatable' => $vatable,
                'amount_paid' => $amount_paid,
                'change' => $change,
                'discount_id' => 0,
                'discounted_amount' => $discounted_amount,
                'total_discounted_amount' => $total_discounted_amount,
                'created_by' => $created_by,
                'updated_by' => $updated_by,
            ];

            $purchase1 = $this->savePurchase($purchase);

            $log2 = [
                'table' => 'purchase',
                'action' => 'insert',
                'field' => '',
                'old_value' => '',
                'new_value' => $purchase1,
                'user_id' => Auth::user()->id,
                'created_at' => new DateTime(),
            ];

            $this->log->insert($log2);
            $count = count($products);
            for ($x = 0; $x < $count; $x++) {

                $i = 0;

                $remaining_quantity = ($products[$x]['total_quantity']);
                $remaining_quantity2 = ($products[$x]['total_quantity']);
                do {
                    $products[$x]['purchase_id'] = $purchase1->id;
                    $product_stock = $this->item_quantity->where('item_id', $products[$x]['product_id'])->where('quantity', '!=', 0)
                        ->get()
                        ->first();

                    $product_stock1 = $this->item_quantity->where('id', $product_stock->id)
                        ->get()
                        ->first();
                    $old_product_stock = $product_stock1;

                    $remaining_quantity = ($product_stock->quantity) - ($remaining_quantity);
                    $product_stock->quantity = ($product_stock->quantity) - ($remaining_quantity2);
                    $remaining_quantity2 = $remaining_quantity;
                    if ($remaining_quantity < 0) {
                        $product_stock->quantity = 0;
                        $remaining_quantity = abs($remaining_quantity);
                        $remaining_quantity2 = abs($remaining_quantity2);
                    } else {
                        $i = 1;
                    }
                    $product_update = $this->item_quantity->where('id', $product_stock->id)
                        ->update(['quantity' => $product_stock->quantity]);

                    $product_updated = $this->item_quantity->where('id', $product_stock->id)
                        ->get()
                        ->first();

                    $log = [
                        'table' => 'items_quantity',
                        'action' => 'update',
                        'field' => '',
                        'old_value' => $old_product_stock,
                        'new_value' => $product_updated,
                        'user_id' => Auth::user()->id,
                        'created_at' => new DateTime(),
                    ];

                    $this->log->insert($log);
                } while ($i < 1);
            }

            $this->model2->insert($products);

            $purchase_details = $this->model2->where('purchase_id', $purchase1->id)
                ->get();
            $count2 = count($purchase_details);
            for ($y = 0; $y < $count2; $y++) {
                $product_item = $this->item->where('id', $purchase_details[$y]->product_id)
                    ->get()
                    ->first();

                $purchase_details[$y]->product = $product_item;
            }
            $purchase1['purchase_details'] = $purchase_details;

            return $purchase1;
        }

    }

    public function savePurchase($attr = [])
    {
        $this->model->fill($attr);
        $this->model->save();
        return $purchase = $this->model->latest()->first();
    }

    public function saveLog($attr = [])
    {
        $this->log->fill($attr);
        $this->log->save();
        return $purchase = $this->model->latest()->first();
    }

    public function savePurchaseDetails($attr = [])
    {
        $this->model2->fill($attr);
        $this->model2->save();
        return $purchase = $this->model2->latest()->first();
    }

    public function getAllWithDetails($options = [])
    {
        $options = $this->getOptions($options);

        $purchases = $this->model->orderBy('created_at', 'DESC')
            ->orderBy('created_at', 'desc')
            ->limit($options['limit'])
            ->paginate($options['paginate']);
        $count = count($purchases);
        for ($x = 0; $x < $count; $x++) {
            $purchase_details = $this->model2->where('purchase_id', $purchases[$x]['id'])
                ->get();

            $count2 = count($purchase_details);
            for ($y = 0; $y < $count2; $y++) {
                $product_item = $this->item->where('id', $purchase_details[$y]->product_id)
                    ->get()
                    ->first();

                $purchase_details[$y]->product = $product_item;
            }
            $purchases[$x]['purchase_details'] = $purchase_details;

        }

        return $purchases;
    }

}
