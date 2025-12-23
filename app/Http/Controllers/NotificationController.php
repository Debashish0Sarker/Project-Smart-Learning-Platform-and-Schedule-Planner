<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->role === 'student') {
            return redirect()->route('student.notifications.index');
        }

        if ($user && $user->role === 'teacher') {
            return redirect()->route('teacher.notifications.index');
        }

        $notifications = $user ? $user->notifications()->paginate(20) : collect();
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();

            $data = $notification->data;
            $url = $data['url'] ?? ($user && $user->role === 'teacher' ? '/teacher/dashboard' : '/student/dashboard');

            session()->flash('from_notification', true);

            return redirect($url);
        }

        return redirect($user && $user->role === 'teacher' ? '/teacher/dashboard' : '/student/dashboard');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function delete($id)
    {
        Auth::user()->notifications()->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Notification deleted.');
    }

    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        return redirect()->back()->with('success', 'All notifications cleared.');
    }
}