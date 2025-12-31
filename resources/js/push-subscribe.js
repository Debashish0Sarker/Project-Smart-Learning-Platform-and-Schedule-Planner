// Example client-side subscription and send-to-server logic
// Usage: include this in a page, replace VAPID_PUBLIC_KEY with your env value injected to the front-end.

async function urlBase64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4);
  const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
  const rawData = atob(base64);
  const outputArray = new Uint8Array(rawData.length);
  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}

export async function subscribeForPush(vapidPublicKey) {
  if (!('serviceWorker' in navigator) || !('PushManager' in window)) throw new Error('Push not supported');
  const reg = await navigator.serviceWorker.register('/sw.js');
  const sub = await reg.pushManager.subscribe({
    userVisibleOnly: true,
    applicationServerKey: urlBase64ToUint8Array(vapidPublicKey)
  });
  // Send subscription to your external API or keep it server-side
  return sub.toJSON();
}

export async function sendPushViaApi(subscription, title, body) {
  const resp = await fetch('/api/push/send', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ subscription, title, body })
  });
  return resp.json();
}
