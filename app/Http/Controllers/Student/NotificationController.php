<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            
            $data = $notification->data;
            $url = $data['url'] ?? '/dashboard';
            
            session()->flash('from_notification', true);
            
            return redirect($url);
        }
        
        return redirect('/dashboard');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
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
