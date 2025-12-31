{{-- [file name]: test-notifications.blade.php --}}
{{-- Location: resources/views/test/notifications.blade.php --}}

@extends(auth()->user()->role === 'teacher' ? 'layouts.teacher' : 'layouts.student')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Test Web Push Notifications</h1>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-4">Browser Notification Status</h2>
            <div class="mb-4">
                <p>Permission Status: <span id="permission-status" class="font-semibold"></span></p>
                <p>Browser Support: <span id="browser-support" class="font-semibold"></span></p>
            </div>
            
            <div class="space-y-3">
                <button id="request-permission" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Request Notification Permission
                </button>
                
                <button id="send-test" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Send Test Notification
                </button>
            </div>
        </div>
        
        <div class="border-t pt-6">
            <h2 class="text-lg font-semibold mb-4">Notification Log</h2>
            <div id="notification-log" class="h-64 overflow-y-auto bg-gray-50 p-4 rounded-lg">
                <!-- Notifications will appear here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check browser support
    const browserSupport = document.getElementById('browser-support');
    if ('Notification' in window) {
        browserSupport.textContent = 'Supported ✅';
        browserSupport.className = 'font-semibold text-green-600';
    } else {
        browserSupport.textContent = 'Not Supported ❌';
        browserSupport.className = 'font-semibold text-red-600';
    }
    
    // Update permission status
    const permissionStatus = document.getElementById('permission-status');
    permissionStatus.textContent = Notification.permission;
    
    // Request permission button
    document.getElementById('request-permission').addEventListener('click', async function() {
        const permission = await Notification.requestPermission();
        permissionStatus.textContent = permission;
        
        if (permission === 'granted') {
            permissionStatus.className = 'font-semibold text-green-600';
            logMessage('✅ Notification permission granted');
        } else {
            permissionStatus.className = 'font-semibold text-red-600';
            logMessage('❌ Notification permission denied');
        }
    });
    
    // Send test notification button
    document.getElementById('send-test').addEventListener('click', async function() {
        try {
                const response = await fetch('/notifications/test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                });
                console.log('notifications/test response status:', response.status);
            
            const data = await response.json();
            logMessage('📤 Test notification sent to server');
            
            // Also try to show immediate notification
            if (Notification.permission === 'granted') {
                new Notification('Test Notification', {
                    body: 'This is a direct test notification',
                    icon: '/favicon.ico'
                });
                logMessage('🔔 Browser notification shown');
            }
        } catch (error) {
            logMessage('❌ Error sending test: ' + error.message);
        }
    });
    
    function logMessage(message) {
        const log = document.getElementById('notification-log');
        const timestamp = new Date().toLocaleTimeString();
        const entry = document.createElement('div');
        entry.className = 'mb-2 text-sm';
        entry.innerHTML = `<span class="text-gray-500">[${timestamp}]</span> ${message}`;
        log.appendChild(entry);
        log.scrollTop = log.scrollHeight;
    }
    
    // Initial log
    logMessage('🚀 Notification test page loaded');
});
</script>
@endsection