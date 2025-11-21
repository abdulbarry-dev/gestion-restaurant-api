<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of tables.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Table::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tables = $query->orderBy('table_number')->paginate(15);

        return response()->json($tables);
    }

    /**
     * Store a newly created table.
     */
    public function store(StoreTableRequest $request): JsonResponse
    {
        $table = Table::create($request->validated());

        return response()->json($table, 201);
    }

    /**
     * Display the specified table.
     */
    public function show(Table $table): JsonResponse
    {
        return response()->json($table->load('orders'));
    }

    /**
     * Update the specified table.
     */
    public function update(UpdateTableRequest $request, Table $table): JsonResponse
    {
        $table->update($request->validated());

        return response()->json($table);
    }

    /**
     * Remove the specified table.
     */
    public function destroy(Table $table): JsonResponse
    {
        $table->delete();

        return response()->json(['message' => 'Table deleted successfully'], 200);
    }
}
