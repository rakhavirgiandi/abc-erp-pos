import axios from 'axios';
import { session } from 'electron';
import state from './state.js';

export async function appendCookie() {
    const cookie = {
        url: `http://localhost:${state.phpPort}`,
        name: '_php_native',
        value: state.randomSecret,
    };

    await session.defaultSession.cookies.set(cookie);
}

export async function notifyLaravel(endpoint: string, payload = {}) {
    if (endpoint === 'events') {
        broadcastToWindows('native-event', payload);
    }

    if (!state.phpPort) {
        console.log(`[notifyLaravel] phpPort not ready yet for '${endpoint}', waiting...`);
        let waited = 0;
        while (!state.phpPort && waited < 15000) {
            await new Promise((r) => setTimeout(r, 200));
            waited += 200;
        }
        console.log(`[notifyLaravel] after waiting ${waited}ms, phpPort=${state.phpPort}`);
    }

    try {
        await axios.post(`http://127.0.0.1:${state.phpPort}/_native/api/${endpoint}`, payload, {
            headers: {
                'X-NativePHP-Secret': state.randomSecret,
            },
        });
        console.log(`[notifyLaravel] SUCCESS: ${endpoint}`);
    } catch (e) {
        console.error(`[notifyLaravel] FAILED (${endpoint}):`, e instanceof Error ? e.message : e);
    }
}

export function broadcastToWindows(event, payload) {
    Object.values(state.windows).forEach((window) => {
        window.webContents.send(event, payload);
    });

    if (state.activeMenuBar?.window) {
        state.activeMenuBar.window.webContents.send(event, payload);
    }
}

/**
 * Remove null and undefined values from an object
 */
export function trimOptions<T extends Record<string, unknown>>(options: T): T {
    Object.keys(options).forEach((key) => options[key] == null && delete options[key]);

    return options;
}

export function appendWindowIdToUrl(url, id) {
    return url + (url.indexOf('?') === -1 ? '?' : '&') + '_windowId=' + id;
}

export function goToUrl(url, windowId) {
    state.windows[windowId]?.loadURL(appendWindowIdToUrl(url, windowId));
}
