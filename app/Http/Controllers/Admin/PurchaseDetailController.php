<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\PurchaseDetail\PurchaseDetailRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchaseDetailController extends Controller
{

    public function __construct(PurchaseDetailRepository $purchaseDetailRepository)
    {
        $this->purchaseDetailRepository = $purchaseDetailRepository;
    }

    public function index(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $purchase = $this->purchaseDetailRepository->getAll($options);
        return response()->success($purchase);
    }

    public function store(Request $request)
    {
        $params = $request->only([
            'purchase_id',
            'product_id',
            'total_amount',
            'total_tax',
            'total_quantity',
            'customer_id',
            'per_unit_id',
            'amount',
            'tax',
        ]);
        $purchase = $this->purchaseDetailRepository->saveByUser($params);
        return response()->success($purchase);
    }

    public function show($id)
    {
        $purchase = $this->purchaseDetailRepository->find($id);
        return response()->success($purchase);
    }

    /**
     * Update the specified resource in storage.
     * TODO: create request for
     *
     * @param  \Illuminate\Http\Requests\AnnouncementRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $purchase_request = $request->only(['purchase_id',
            'purchase_id',
            'product_id',
            'total_amount',
            'total_tax',
            'total_quantity',
            'customer_id',
            'per_unit_id',
            'amount',
            'tax']);
        $purchase = $this->purchaseDetailRepository->update($id, $purchase_request);
        return response()->success($purchase);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = $this->purchaseDetailRepository->delete($id);
        return response()->success($purchase);
    }

}
