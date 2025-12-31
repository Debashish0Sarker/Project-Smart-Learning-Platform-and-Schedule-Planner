// Request permission for OS notifications
if ('Notification' in window) {
  if (Notification.permission === 'default') {
    Notification.requestPermission();
  }
}

// Helper: open notification URL on click
function bindNotificationClick(n) {
  if (!n) return;
  n.onclick = function (ev) {
    const url = (ev && ev.target && ev.target.data && ev.target.data.url) || (ev && ev.currentTarget && ev.currentTarget.data && ev.currentTarget.data.url);
    if (url) {
      window.open(url, '_blank');
    }
  };
}

// Listen on private channel for authenticated user
const userMeta = document.head.querySelector('meta[name="user-id"]');
const userId = userMeta ? userMeta.content : null;

if (window.Echo && userId) {
  window.Echo.private(`App.Models.User.${userId}`).notification((notification) => {
    try {
      if (Notification.permission === 'granted') {
        const n = new Notification(notification.title || 'Notification', {
          body: notification.message || '',
          icon: notification.icon ? null : '/favicon.ico',
          data: notification
        });
        bindNotificationClick(n);
      }

      // Optionally update in-page notification list here (emit an event or update DOM)
      window.dispatchEvent(new CustomEvent('app:notification', { detail: notification }));
    } catch (err) {
      console.error('Notification display error', err);
    }
  });
} else {
  // If Echo not available or no user id, listen for fallback events if any
}
