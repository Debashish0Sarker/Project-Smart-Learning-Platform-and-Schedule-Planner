import './bootstrap';
import Echo from '@ably/laravel-echo';
import * as Ably from 'ably';

// Initialize Echo with Ably
window.Echo = new Echo({
    broadcaster: 'ably',
    client: new Ably.Realtime({ 
        key: 'your-ably-key-here', // Make sure to use your actual Ably key
        // Or use environment variable:
        // key: import.meta.env.VITE_ABLY_KEY 
    })
});

// Optional: If you're using Reverb (Laravel's WebSocket server), use this instead:
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// 
// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST,
//     wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
//     wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });