// [file name]: service-worker.js
// Location: public/service-worker.js

self.addEventListener('push', function(event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }
    
    let data = {};
    
    if (event.data) {
        data = event.data.json();
    }
    
    const options = {
        body: data.body || data.message,
        icon: data.icon || '/favicon.ico',
        badge: '/badge.png',
        tag: data.type || 'general',
        data: data,
        actions: data.actions || [
            {
                action: 'view',
                title: 'View'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification(data.title || 'Notification', options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    if (event.action === 'view' || event.action === 'open') {
        const url = event.notification.data.url || '/dashboard';
        
        event.waitUntil(
            clients.matchAll({type: 'window'}).then(function(clientList) {
                for (let client of clientList) {
                    if (client.url === url && 'focus' in client) {
                        return client.focus();
                    }
                }
                return clients.openWindow(url);
            })
        );
    }
});