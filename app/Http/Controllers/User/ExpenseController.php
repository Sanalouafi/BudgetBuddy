<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *    title="Expense API",
 *   version="1.0.0",
 *  description="API to manage expenses",
 * )
 */
class ExpenseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/expenses",
     *     tags={"Expenses"},
     *     summary="Get all expenses",
     *     description="Retrieve a list of all expenses",
     *     security={{"sanctum": {}}},
     *     @OA\Response(response="200", description="List of expenses"),
     * )
     */
    public function index(Request $request)
    {
        $expenses = Expense::where('user_id', $request->user()->id)->get();
        $response = [
            'status' => 'ok',
            'data' => ExpenseResource::collection($expenses)
        ];

        return response()->json($response, 200);

    }


    /**
     * @OA\Post(
     *     path="/api/expenses",
     *     tags={"Expenses"},
     *     summary="Create a new expense",
     *     description="Create a new expense record",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Expense data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="amount", type="number", format="float"),
     *             @OA\Property(property="user_id", type="integer"),
     *         ),
     *     ),
     *     @OA\Response(response="201", description="Expense created"),
     * )
     */
    public function store(Request $request)
    {
        $userId = $request->user()->id;

        $expense = Expense::create([
            'name' => $request->name,
            'description' => $request->description,
            'amount' => $request->amount,
            'user_id' => $userId,
        ]);
        return response()->json($expense, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/expenses/{expense}",
     *     tags={"Expenses"},
     *     summary="Get a specific expense",
     *     description="Retrieve a specific expense by ID",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="expense",
     *         in="path",
     *         required=true,
     *         description="ID of the expense",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Expense details"),
     * )
     */
    public function show(Expense $expense)
    {
        $response = [
            'status' => 'ok',
            'data' => new ExpenseResource($expense)
        ];

        return response()->json($response, 200);
    }


    /**
     * @OA\Put(
     *     path="/api/expenses/{expense}",
     *     tags={"Expenses"},
     *     summary="Update an existing expense",
     *     description="Update an existing expense record",
     *      security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="expense",
     *         in="path",
     *         required=true,
     *         description="ID of the expense",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Expense data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="amount", type="number", format="float"),
     *             @OA\Property(property="user_id", type="integer"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="Expense updated"),
     * )
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);
        $expense->update([
            'name' => $request->name,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);
        return response()->json($expense, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/expenses/{expense}",
     *     tags={"Expenses"},
     *     summary="Delete an existing expense",
     *     description="Delete an existing expense record",
     *      security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="expense",
     *         in="path",
     *         required=true,
     *         description="ID of the expense",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Expense deleted"),
     * )
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        $expense->delete();

        return response()->json(null, 204);
    }
}
