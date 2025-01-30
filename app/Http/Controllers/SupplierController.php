<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @response array{status: string, message: string, data: Supplier[]}
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            /**
             * @query
             *
             * @default 1
             */
            'page' => ['integer', 'min:1'],
            /**
             * @query
             *
             * @default 10
             */
            'per_page' => ['integer', 'min:1'],
            /**
             * Filter Supplier by CPF or CNPJ.
             *
             * @query
             *
             * @example 99.999.999/0001-99
             */
            'document_number' => ['string', 'max:18'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data successfully retrieved.',
            'data' => Supplier::search($validated)->simplePaginate($validated['per_page'] ?? 10)->items(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @response array{status: string, message: string, data: Supplier}
     */
    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Data successfully created.',
            'data' => $supplier,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @response array{status: string, message: string, data: Supplier}
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Data successfully updated.',
            'data' => $supplier,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data successfully deleted.',
        ]);
    }
}
