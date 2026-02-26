<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Models\Colocation;
use App\Models\Settlement;
use Illuminate\Http\Request;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;

class ExpenseController extends Controller
{
    public function index(Request $request, Colocation $colocation)
    {
        $query = $colocation->expenses()
            ->with(['payer', 'category'])
            ->orderBy('date', 'desc');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('month')) {
            $date = \Carbon\Carbon::parse($request->month);
            $query->whereYear('date', $date->year)
                ->whereMonth('date', $date->month);
        }

        $expenses = $query->paginate(20)->withQueryString();

        $categories = Category::whereNull('colocation_id')
            ->orWhere('colocation_id', $colocation->id)
            ->get()
            ->unique('name');

        return view('expenses.index', compact('colocation', 'expenses', 'categories'));
    }

    public function create(Colocation $colocation)
    {
        $members = $colocation->users;
        $categories = Category::whereNull('colocation_id')
            ->orWhere('colocation_id', $colocation->id)
            ->get()
            ->unique('name');

        return view('expenses.create', compact('colocation', 'members', 'categories'));
    }
    public function show(Colocation $colocation, Expense $expense)
{
    $expense->load('settlements.debtor');

    return view('expenses.show', compact('colocation', 'expense'));
}

    public function store(StoreExpenseRequest $request, Colocation $colocation)
    {
        $category = Category::firstOrCreate([
            'name' => $request->category_name,
            'colocation_id' => $colocation->id
        ]);

        $expense = Expense::create([
            'colocation_id' => $colocation->id,
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        $this->syncSettlements($expense, $colocation);

        return redirect()->route('expenses.index', $colocation->id)
            ->with('success', 'Expense and splits saved!');
    }



    public function edit(Colocation $colocation, Expense $expense)
    {
        $categories = Category::whereNull('colocation_id')
            ->orWhere('colocation_id', $colocation->id)
            ->get()
            ->unique('name');

        return view('expenses.edit', compact('colocation', 'expense', 'categories'));
    }

    public function update(UpdateExpenseRequest $request, Colocation $colocation, Expense $expense)
    {
        $category = Category::firstOrCreate([
            'name' => $request->category_name,
            'colocation_id' => $colocation->id,
        ]);

        $expense->update([
            'category_id' => $category->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);


        $this->syncSettlements($expense, $colocation);

        return redirect()->route('expenses.index', $colocation)
            ->with('success', 'Expense updated!');
    }

    private function syncSettlements(Expense $expense, Colocation $colocation)
    {
        $expense->settlements()->delete();

        $members = $colocation->users;
        $count = $members->count();

        if ($count > 1) {
            $splitAmount = $expense->amount / $count;

            foreach ($members as $member) {
                if ($member->id != $expense->user_id) {
                    Settlement::create([
                        'expense_id'   => $expense->id,
                        'colocation_id' => $colocation->id,
                        'debtor_id'    => $member->id,
                        'creditor_id'  => $expense->user_id,
                        'amount'       => $splitAmount,
                        'is_paid'      => false,
                    ]);
                }
            }
        }
    }
}
