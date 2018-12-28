<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Manufacturer\ManufacturerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManufacturerController extends Controller
{

    public function __construct(ManufacturerRepository $manufacturerRepository)
    {
        $this->manufacturerRepository = $manufacturerRepository;
    }

    public function index(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $manufacturer = $this->manufacturerRepository->getAll($options);
        return response()->success($manufacturer);
    }

    public function store(Request $request)
    {
        $params = $request->only([
            'name',
            'description',
            'address',
            'phone',
            'created_by',
            'updated_by',
        ]);
        $manufacturer = $this->manufacturerRepository->saveByUser($params);
        return response()->success($manufacturer);
    }

    public function show($id)
    {
        $manufacturer = $this->manufacturerRepository->find($id);
        return response()->success($manufacturer);
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
        $manufacturer_request = $request->only(['name',
            'description',
            'address',
            'phone',
            'created_by',
            'updated_by']);
        $manufacturer = $this->manufacturerRepository->update($id, $manufacturer_request);
        return response()->success($manufacturer);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = $this->manufacturerRepository->delete($id);
        return response()->success($delete);
    }

}
