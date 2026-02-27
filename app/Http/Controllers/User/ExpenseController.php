<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Expense;
use App\Models\Category;
use App\Models\Colocation;
use App\Models\Settlement;
use Illuminate\Http\Request;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;

class ExpenseController extends Controller
{
    // show all expenses for a colocation with debts and credits
    public function index(Request $request, Colocation $colocation)
    {
        $userId = auth()->id();

        // get all unpaid amounts that others owe to the current user
        $credits = Settlement::where('creditor_id', $userId)
            ->where('colocation_id', $colocation->id)
            ->where('is_paid', false)
            ->with('debtor') 
            ->get();

        // get all unpaid amounts that the current user owes to others
        $debts = Settlement::where('debtor_id', $userId)
            ->where('colocation_id', $colocation->id)
            ->where('is_paid', false)
            ->with('creditor')
            ->get();

        // build the expense query with optional filters
        $query = $colocation->expenses()
            ->with(['payer', 'category'])
            ->orderBy('date', 'desc');

        // filter by category if given
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // filter by month if given
        if ($request->filled('month')) {
            $date = \Carbon\Carbon::parse($request->month);
            $query->whereYear('date', $date->year)
                ->whereMonth('date', $date->month);
        }

        $expenses = $query->paginate(20)->withQueryString();

        // get all categories available for this colocation
        $categories = Category::whereNull('colocation_id')
            ->orWhere('colocation_id', $colocation->id)
            ->get()
            ->unique('name');

        return view('user.expenses.index', compact(
            'colocation',
            'expenses',
            'categories',
            'credits',
            'debts'
        ));
    }

    // show the form to create a new expense
    public function create(Colocation $colocation)
    {
        $members = $colocation->users;
        $categories = Category::whereNull('colocation_id')
            ->orWhere('colocation_id', $colocation->id)
            ->get()
            ->unique('name');

        return view('user.expenses.create', compact('colocation', 'members', 'categories'));
    }

    // show the details of one expense
    public function show(Colocation $colocation, Expense $expense)
    {
        $expense->load('settlements.debtor');

        return view('user.expenses.show', compact('colocation', 'expense'));
    }

    // save a new expense and create the splits
    public function store(StoreExpenseRequest $request, Colocation $colocation)
    {
        // find or create the category for this expense
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

        // create the settlement rows for each member
        $this->syncSettlements($expense, $colocation);

        return redirect()->route('user.expenses.index', $colocation)
            ->with('success', 'Expense and splits saved!');
    }

    // show the form to edit an expense
    public function edit(Colocation $colocation, Expense $expense)
    {
        $this->authorize('update', $expense);
        $categories = Category::whereNull('colocation_id')
            ->orWhere('colocation_id', $colocation->id)
            ->get()
            ->unique('name');

        return view('user.expenses.edit', compact('colocation', 'expense', 'categories'));
    }

    // save the changes to an expense and update the splits
    public function update(UpdateExpenseRequest $request, Colocation $colocation, Expense $expense)
    {
        $this->authorize('update', $expense);

        // find or create the category for the updated expense
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

        // rebuild the settlement rows after the update
        $this->syncSettlements($expense, $colocation);

        return redirect()->route('user.expenses.index', $colocation)
            ->with('success', 'Expense updated!');
    }

    // delete old splits and create new ones for each member
    private function syncSettlements(Expense $expense, Colocation $colocation)
    {
        // remove all existing splits for this expense
        $expense->settlements()->delete();
        $members = $colocation->users;
        $count = $members->count();

        // only split if there is more than one member
        if ($count > 1) {
            $splitAmount = $expense->amount / $count;

            // create one settlement row for each member who did not pay
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