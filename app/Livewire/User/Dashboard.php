<?php

namespace App\Livewire\User;

use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Traits\LivewireToast;
use Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('user.layouts.main')]
class Dashboard extends Component
{
    use LivewireToast;

    public string $randomQuotes;

    // meta
    public string $metaTitle;

    public string $totalOrders;

    public string $userBalance;

    public string $totalSpent;

    public string $totalTickets;

    public function mount()
    {
        $user = Auth::user();
        // set meta
        $this->metaTitle = 'Dashboard';
        $this->totalOrders = Order::where('user_id', $user->id)->count();
        $this->userBalance = $user->balance;
        $this->totalSpent = Transaction::where('user_id', $user->id)->where('type', 'debit')->sum('amount');
        $this->totalTickets = SupportTicket::where('user_id', $user->id)->count();
        $this->randomQuotes = $this->getRandomQuotes();
    }

    public function getRandomQuotes()
    {
        $quotes = [
            "Here's what's happening with your SMM campaigns today.",
            'Welcome back! Ready to grow your audience?',
            'Keep pushing! consistency is key to success!',
            "Success is built on strategy and timing. Let's optimize.",
            'Another day, another opportunity to level up.',
            'Your digital presence just got stronger.',
            "Good to see you! Let's make some impact today.",
            "Marketing magic starts with smart moves you're on track.",
            "Take control of your brand you're doing great.",
            "Insights await. Let's make data-driven decisions.",
            "You're not just managing campaigns you're building momentum.",
            "Let's boost your reach, one post at a time.",
            "Every click counts and you're making them count.",
        ];

        return $quotes[array_rand($quotes)];
    }

    public function render()
    {
        $recentActivities = Transaction::where('user_id', Auth::user()->id)->latest()->take(5)->get();

        return view('livewire.user.dashboard', compact('recentActivities'));
    }
}
