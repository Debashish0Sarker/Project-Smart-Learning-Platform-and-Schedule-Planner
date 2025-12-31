import './bootstrap';
import Echo from '@ably/laravel-echo';
import { Reverb } from '@ably/laravel-echo';

// Initialize Echo first so other listeners can attach to window.Echo
window.Echo = new Echo({
    broadcaster: Reverb,
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Then load notification helpers that use window.Echo
import './echo-notifications';
