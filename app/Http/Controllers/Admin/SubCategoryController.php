<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\SubCategory\SubCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{

    public function __construct(SubCategoryRepository $subcategoryRepository)
    {
        $this->subcategoryRepository = $subcategoryRepository;
    }

    public function index(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $SubCategory = $this->subcategoryRepository->getAll($options);
        return response()->success($SubCategory);
    }

    public function store(Request $request)
    {
        $params = $request->only([
            'name',
            'category_id',
            'description',
            'created_by',
            'updated_by',
        ]);
        $SubCategory = $this->subcategoryRepository->saveByUser($params);
        return response()->success($SubCategory);
    }

    public function show($id)
    {
        $SubCategory = $this->subcategoryRepository->find($id);
        return response()->success($SubCategory);
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
        $subcategory_request = $request->only(['name', 'category_id', 'description', 'created_by',
            'updated_by']);
        $SubCategory = $this->subcategoryRepository->update($id, $subcategory_request);
        return response()->success($SubCategory);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = $this->subcategoryRepository->delete($id);
        return response()->success($delete);
    }

    public function getByCategoryId($id, Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $sub_category = $this->subcategoryRepository->getByCategoryId($id, $options);
        return response()->success($sub_category);
    }

}
