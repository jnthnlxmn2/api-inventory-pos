<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Discount\DiscountRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiscountController extends Controller
{

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    public function index(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $item = $this->discountRepository->getAll($options);
        return response()->success($item);
    }

    public function store(Request $request)
    {
        $params = $request->only([
            'code',
            'type',
            'date_used',
            'active',
            'value',
        ]);
        $item = $this->discountRepository->save($params);
        return response()->success($item);
    }

    public function show($id)
    {
        $item = $this->discountRepository->find($id);
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
        $customer_request = $request->only(['code',
            'type',
            'date_used',
            'active',
            'value']);
        $item = $this->discountRepository->update($id, $customer_request);
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
        $item = $this->discountRepository->delete($id);
        return response()->success($item);
    }

}
