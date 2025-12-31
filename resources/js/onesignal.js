// OneSignal web integration helper (for web push)
// Include OneSignal SDK in your blade: <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>

export function initOneSignal(appId) {
  if (!window.OneSignal) {
    console.warn('OneSignal SDK not loaded');
    return;
  }

  window.OneSignal = window.OneSignal || [];

  OneSignal.push(function() {
    OneSignal.init({
      appId: appId,
      allowLocalhostAsSecureOrigin: true,
    });

    OneSignal.on('subscriptionChange', async function(isSubscribed) {
      if (isSubscribed) {
        const playerId = await OneSignal.getUserId();
        // send playerId to server
        fetch('/onesignal/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ player_id: playerId, platform: 'web' })
        });
      }
    });
  });
}
