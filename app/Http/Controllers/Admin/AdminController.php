<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // show the statistics page with charts and numbers
    public function statistiques()
    {
        
        $stats = [
            'total_users' => User::count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'total_colocations' => Colocation::count(),
        ];

        // group banned users by the month they were created
        $bannedGrowth = \App\Models\User::where('is_banned', true)
            ->get()
            ->groupBy(function ($user) {
                return $user->created_at->format('M');
            });

        $bannedChartData = [
            'labels' => $bannedGrowth->keys(),
            'data' => $bannedGrowth->map->count()->values(),
        ];

        // get users created in the last 6 months and group them by month
        $userGrowth = User::where('created_at', '>=', now()->subMonths(6))
            ->get()
            ->groupBy(function ($user) {
                return $user->created_at->format('M');
            });

        $chartData = [
            'labels' => $userGrowth->keys(),
            'data' => $userGrowth->map->count()->values(),
        ];

        // get expenses created in the last 6 months and group them by month
        $expenseActivity = Expense::where('created_at', '>=', now()->subMonths(6))
            ->get()
            ->groupBy(function ($expense) {
                return $expense->created_at->format('M');
            });

        $extraChartData = [
            'activity_labels' => $expenseActivity->keys(),
            'activity_data' => $expenseActivity->map->count()->values(),
        ];

        // group colocations by their status : active or cancelled
        $colocationStatus = Colocation::get()->groupBy('status');

        $statusChartData = [
            'labels' => $colocationStatus->keys(),
            'data' => $colocationStatus->map->count()->values(),
        ];

        return view('admin.statistiques', compact('stats', 'chartData', 'extraChartData', 'statusChartData', 'bannedChartData'));
    }

    // show the list of all users
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // ban a user by setting is_banned to true
    public function banUser(User $user)
    {
        $user->update(['is_banned' => true]);
        return back()->with('status', 'User banned successfully.');
    }

    // show the list of all colocations with their members
    public function colocations()
    {
        $colocations = Colocation::with('users')->get();
        return view('admin.colocations', compact('colocations'));
    }
}