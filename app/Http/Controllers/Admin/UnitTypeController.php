<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UnitType\UnitTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnitTypeController extends Controller
{

    public function __construct(UnitTypeRepository $unittypeRepository)
    {
        $this->unittypeRepository = $unittypeRepository;
    }

    public function index(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $unittype = $this->unittypeRepository->getAll($options);
        return response()->success($unittype);
    }

    public function store(Request $request)
    {
        $params = $request->only([
            'name',
            'description',
            'quantity',
            'created_by',
            'updated_by',
        ]);
        $unittype = $this->unittypeRepository->saveByUser($params);
        return response()->success($unittype);
    }

    public function show($id)
    {
        $unittype = $this->unittypeRepository->find($id);
        return response()->success($unittype);
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
        $unittype_request = $request->only(['name',
            'description',
            'quantity',
            'created_by',
            'updated_by']);
        $unittype = $this->unittypeRepository->update($id, $unittype_request);
        return response()->success($unittype);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unittype = $this->unittypeRepository->delete($id);
        return response()->success($unittype);
    }

}
