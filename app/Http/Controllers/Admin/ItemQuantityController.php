<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ItemQuantity\ItemQuantityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemQuantityController extends Controller
{

    public function __construct(ItemQuantityRepository $ItemController)
    {
        $this->ItemController = $ItemController;
    }

    public function index(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $item = $this->ItemController->getAll($options);
        return response()->success($item);
    }

    public function store(Request $request)
    {
        $params = $request->only([
            'item_id',
            'quantity',
            'price',
        ]);
        $item = $this->ItemController->save($params);
        return response()->success($item);
    }

    public function show($id)
    {
        $item = $this->ItemController->find($id);
        return response()->success($item);
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
        $customer_request = $request->only([
            'item_id',
            'quantity',
            'price',
            'created_by',
        ]);
        $item = $this->ItemController->update($id, $customer_request);
        return response()->success($item);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = $this->ItemController->delete($id);
        return response()->success($item);
    }

    public function refill(Request $request, $id)
    {
        $refill_request = $request->only(['stock']);
        $item = $this->ItemController->refill($id, $refill_request);
        return response()->success($item);
    }

}
