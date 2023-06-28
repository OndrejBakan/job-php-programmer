<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(
            Customer::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $relations = explode(',', $request->input('include'));

        $customer = Customer::include($relations)->findOrFail($id);

        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $customer->update($validated);

        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): Response
    {
        if ($customer->delete())
            return response()->json([], 200);
        
        return response()->notFound();
    }

    public function attachCustomerGroups(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'customer_groups.*' => 'array:id',
            'customer_groups.*.id' => 'distinct',
        ]);

        $customer->customerGroups()->attach(
            Arr::pluck($validated['customer_groups'], 'id')
        );

        return response()->json($customer->load('customerGroups'));
    }

    public function detachCustomerGroups(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'customer_groups.*' => 'array:id',
            'customer_groups.*.id' => 'distinct',
        ]);

        $customer->customerGroups()->detach(
            Arr::pluck($validated['customer_groups'], 'id')
        );

        return response()->json($customer->load('customerGroups'));
    }

    public function syncCustomerGroups(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'customer_groups.*' => 'array:id',
            'customer_groups.*.id' => 'distinct',
        ]);

        $customer->customerGroups()->sync(
            Arr::pluck($validated['customer_groups'], 'id')
        );

        return response()->json($customer->load('customerGroups'));
    }
}
