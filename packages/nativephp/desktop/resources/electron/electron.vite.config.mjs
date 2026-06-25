import { defineConfig, externalizeDepsPlugin } from 'electron-vite';
import { join } from 'path';

const appPath = process.env.APP_PATH;

export default defineConfig({
    main: {
        build: {
            rollupOptions: {
                plugins: [
                    {
                        name: 'watch-external',
                        buildStart() {
                            if (!appPath) {
                                console.warn('[watch-external] APP_PATH is not set, skipping watch file.');
                                return;
                            }
                            this.addWatchFile(
                                join(appPath, 'app', 'Providers', 'NativeAppServiceProvider.php'),
                            );
                        },
                    },
                ],
            },
        },
        plugins: [externalizeDepsPlugin()],
    },
});