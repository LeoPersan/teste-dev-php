<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @response array{status: string, message: string, data: Supplier[]}
     */
    public function index(Request $request): JsonResponse
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
            'data' => $this->getSuppliers($validated),
        ]);
    }

    /**
     * @param array{page: int|null, per_page: int|null, document_number: string|null} $filters
     * @return Supplier[]
     */
    protected function getSuppliers(array $filters): array
    {
        $filters['page'] = $filters['page'] ?? 1;
        $filters['per_page'] = $filters['per_page'] ?? 10;
        $filters['document_number'] = $filters['document_number'] ?? false;

        if (config('cache_suppliers_filtered')) {
            return cache()->tags('suppliers')->rememberForever(
                "suppliers::" . serialize($filters),
                fn () => Supplier::search($filters)->simplePaginate($filters['per_page'])->items()
            );
        }

        return cache()->tags('suppliers')->rememberForever("suppliers::all", fn () => Supplier::all())
            ->when(
                $filters['document_number'],
                fn ($suppliers) => $suppliers->where('document_number', $filters['document_number'])
            )
            ->slice(($filters['page'] - 1) * $filters['per_page'], $filters['per_page'])->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @response array{status: string, message: string, data: Supplier}
     */
    public function store(StoreSupplierRequest $request): JsonResponse
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
    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
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
    public function destroy(Supplier $supplier): JsonResponse
    {
        $supplier->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data successfully deleted.',
        ]);
    }
}
