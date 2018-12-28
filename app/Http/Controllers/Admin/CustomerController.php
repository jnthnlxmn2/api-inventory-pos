<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Customer\CustomerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $options = $request->only('paginate', 'limit', 'order');
        $Customer = $this->customerRepository->getAll($options);
        return response()->success($Customer);
    }

    public function store(Request $request)
    {
        $params = $request->only([
            'name',
            'address',
            'phone',
            'created_by',
            'updated_by',
        ]);
        $Customer = $this->customerRepository->saveByUser($params);
        return response()->success($Customer);
    }

    public function show($id)
    {
        $Customer = $this->customerRepository->find($id);
        return response()->success($Customer);
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
        $customer_request = $request->only(['name',
            'address',
            'phone',
            'created_by',
            'updated_by']);
        $customer = $this->customerRepository->update($id, $customer_request);
        return response()->success($customer);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = $this->customerRepository->delete($id);
        return response()->success($delete);
    }

}
