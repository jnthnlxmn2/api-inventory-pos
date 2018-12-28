<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Tax\TaxRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaxController extends Controller
{

    public function __construct(TaxRepository $TaxRepository)
    {
        $this->TaxRepository = $TaxRepository;
    }

    public function index(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $item = $this->TaxRepository->getAll($options);
        return response()->success($item);
    }

    public function store(Request $request)
    {
        $params = $request->only([
            'tax',
        ]);
        $item = $this->TaxRepository->save($params);
        return response()->success($item);
    }

    public function show($id)
    {
        $item = $this->TaxRepository->find($id);
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
            'tax',
        ]);
        $item = $this->TaxRepository->update($id, $customer_request);
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
        $item = $this->TaxRepository->delete($id);
        return response()->success($item);
    }

}
