// [file name]: webpush-handler.js
// Location: public/js/webpush-handler.js

class WebPushHandler {
    constructor() {
        this.permission = Notification.permission;
        this.initialize();
    }

    initialize() {
        this.setupEventListeners();
        this.setupServiceWorker();
        this.checkExistingPermission();
    }

    setupEventListeners() {
        // Listen for broadcast events from Echo
        if (window.Echo) {
            window.Echo.private(`App.Models.User.${window.userId}`)
                .notification((notification) => {
                    this.showBrowserNotification(notification);
                });
        }

        // Also poll for new notifications as backup
        this.startPolling();
    }

    async setupServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/service-worker.js');
                console.log('ServiceWorker registered');
            } catch (error) {
                console.error('ServiceWorker registration failed:', error);
            }
        }
    }

    async requestPermission() {
        if (this.permission === 'granted') return true;
        
        try {
            const result = await Notification.requestPermission();
            this.permission = result;
            
            if (result === 'granted') {
                this.showWelcomeNotification();
                return true;
            }
            return false;
        } catch (error) {
            console.error('Error requesting notification permission:', error);
            return false;
        }
    }

    showBrowserNotification(notificationData) {
        if (!('Notification' in window)) return;
        if (Notification.permission !== 'granted') return;

        const notification = new Notification(notificationData.title, {
            body: notificationData.message || notificationData.body,
            icon: notificationData.icon || '/favicon.ico',
            tag: notificationData.type || 'general',
            data: notificationData
        });

        notification.onclick = function(event) {
            event.preventDefault();
            window.focus();
            this.close();
            
            if (notificationData.url) {
                window.location.href = notificationData.url;
            }
        };

        setTimeout(() => notification.close(), 10000);
    }

    showWelcomeNotification() {
        this.showBrowserNotification({
            title: 'Notifications Enabled',
            message: 'You will now receive notifications for important updates.',
            icon: '✅'
        });
    }

    startPolling(interval = 30000) {
        setInterval(() => this.checkNewNotifications(), interval);
    }

    async checkNewNotifications() {
        try {
            const response = await fetch('/notifications/unread-count');
            const data = await response.json();
            
            if (data.unread > 0) {
                // Optional: Show badge or update UI
                this.updateUnreadBadge(data.unread);
            }
        } catch (error) {
            console.error('Error checking notifications:', error);
        }
    }

    updateUnreadBadge(count) {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        }
    }

    checkExistingPermission() {
        if (this.permission === 'default') {
            // Show permission button after delay
            setTimeout(() => this.showPermissionButton(), 5000);
        }
    }

    showPermissionButton() {
        if (document.getElementById('enable-notifications-btn')) return;
        
        const button = document.createElement('button');
        button.id = 'enable-notifications-btn';
        button.innerHTML = '🔔 Enable Notifications';
        button.className = 'enable-notifications-btn';
        
        button.addEventListener('click', async () => {
            await this.requestPermission();
            button.remove();
        });
        
        document.body.appendChild(button);
    }
}