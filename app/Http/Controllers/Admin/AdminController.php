<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // 1. Statistiques
    public function statistiques()
    {
        // Basic real-time counts
        $stats = [
            'total_users' => User::count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'total_colocations' => Colocation::count(),
        ];

        $bannedGrowth = \App\Models\User::where('is_banned', true)
            ->get()
            ->groupBy(function ($user) {
                return $user->created_at->format('M');
            });

        $bannedChartData = [
            'labels' => $bannedGrowth->keys(),
            'data' => $bannedGrowth->map->count()->values(),
        ];

        // --- REAL USER GROWTH ---
        // Get users from the last 6 months, grouped by month name
        $userGrowth = User::where('created_at', '>=', now()->subMonths(6))
            ->get()
            ->groupBy(function ($user) {
                return $user->created_at->format('M'); // Groups by 'Jan', 'Feb', etc.
            });

        $chartData = [
            'labels' => $userGrowth->keys(), // The Month names
            'data' => $userGrowth->map->count()->values(), // The count per month
        ];

        // --- REAL EXPENSE ACTIVITY ---
        // Get real expenses grouped by month to show platform usage
        $expenseActivity = Expense::where('created_at', '>=', now()->subMonths(6))
            ->get()
            ->groupBy(function ($expense) {
                return $expense->created_at->format('M');
            });

        $extraChartData = [
            'activity_labels' => $expenseActivity->keys(),
            'activity_data' => $expenseActivity->map->count()->values(),
        ];

        // --- REAL COLOCATION STATUS ---
        // Grouping colocations by their 'status' column (e.g., active, archived)
        $colocationStatus = Colocation::get()->groupBy('status');

        $statusChartData = [
            'labels' => $colocationStatus->keys(),
            'data' => $colocationStatus->map->count()->values(),
        ];

        return view('admin.statistiques', compact('stats', 'chartData', 'extraChartData', 'statusChartData','bannedChartData'));
    }

    // 2. Manage Users (List & Ban)
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function banUser(User $user)
    {
        $user->update(['is_banned' => true]); // Requires an 'is_banned' column in your migration
        return back()->with('status', 'User banned successfully.');
    }

    // 3. Colocations & Members
    public function colocations()
    {
        // Fetch colocations with their members (assuming a 'users' relationship exists)
        $colocations = Colocation::with('users')->get();
        return view('admin.colocations', compact('colocations'));
    }
}
