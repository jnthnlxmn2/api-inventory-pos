<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Purchase\PurchaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{

    public function __construct(PurchaseRepository $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    public function index(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $purchase = $this->purchaseRepository->getAll($options);
        return response()->success($purchase);
    }

    public function store(Request $request)
    {
        $params = $request->only([
            'tracking_number',
            'total_quantity',
            'total_amount',
            'tax',
            'created_by',
            'updated_by',
        ]);
        $purchase = $this->purchaseRepository->saveByUser($params);
        return response()->success($purchase);
    }

    public function show($id)
    {
        $purchase = $this->purchaseRepository->find($id);
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
        $purchase_request = $request->only([
            'tracking_number',
            'total_quantity',
            'total_amount',
            'tax',
            'created_by',
            'updated_by',
        ]);
        $purchase = $this->purchaseRepository->update($id, $purchase_request);
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
        $delete = $this->purchaseRepository->delete($id);
        return response()->success($delete);
    }

    public function generatePurchase(Request $request)
    {
        $params = $request->only([
            'items', 'code', 'discount_code', 'amount_paid',
        ]);
        $purchase = $this->purchaseRepository->generatePurchase($params);
        return response()->success($purchase);
    }

    public function getAllWithDetails(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $purchase = $this->purchaseRepository->getAllWithDetails($options);
        return response()->success($purchase);
    }

}
