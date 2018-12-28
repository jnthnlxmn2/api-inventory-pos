<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $Category = $this->categoryRepository->getAll($options);
        return response()->success($Category);
    }

    public function store(Request $request)
    {
        $params = $request->only([
            'name',
            'description',
            'created_by',
            'updated_by',
        ]);
        $Category = $this->categoryRepository->saveByUser($params);
        return response()->success($Category);
    }

    public function show($id)
    {
        $category = $this->categoryRepository->find($id);
        return response()->success($category);
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
        $category_request = $request->only(['name', 'description', 'created_by',
            'updated_by']);
        $category = $this->categoryRepository->update($id, $category_request);
        return response()->success($category);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = $this->categoryRepository->delete($id);
        return response()->success($delete);
    }

}
