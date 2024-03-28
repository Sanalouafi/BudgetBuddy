<?php

namespace Tests\Unit\Controllers\User;

use App\Http\Controllers\User\ExpenseController;
use App\Models\Expense;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseControllerTest extends TestCase
{
    use RefreshDatabase; // Use this trait to ensure database transactions for each test

    public function testIndex()
    {
        // Create a user and some expenses
        $user = \App\Models\User::factory()->create();
        $expenses = Expense::factory()->count(3)->create(['user_id' => $user->id]);

        // Mock request with authenticated user
        $response = $this->actingAs($user)->get('/api/expenses');

        // Assert response status is 200 and contains expense data
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function testStore()
    {
        // Create a user
        $user = \App\Models\User::factory()->create();

        // Create expense data
        $expenseData = [
            'name' => 'Test Expense',
            'description' => 'Test Description',
            'amount' => 100.00,
            'user_id' => $user->id,
        ];

        // Call store method
        $response = $this->actingAs($user)->post('/api/expenses', $expenseData);

        // Assert response status is 201 and contains the created expense
        $response->assertStatus(201);
        $this->assertDatabaseHas('expenses', ['name' => 'Test Expense']);
    }

    public function testShow()
    {
        // Create a user
        $user = \App\Models\User::factory()->create();

        // Create an expense
        $expense = Expense::factory()->create(['user_id' => $user->id]);

        // Mock request with authenticated user
        $response = $this->actingAs($user)->get('/api/expenses/' . $expense->id);

        // Assert response status is 200 and contains the expense data
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $expense->id,
                'name' => $expense->name,
                // Include other attributes you expect to be returned
            ]
        ]);
    }


}
