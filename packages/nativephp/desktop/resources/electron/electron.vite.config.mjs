import { defineConfig, externalizeDepsPlugin } from 'electron-vite';
import { join, resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const appPath   = process.env.APP_PATH;
const buildPath = process.env.NATIVEPHP_BUILD_PATH || '';
const isBuilding = !! process.env.NATIVEPHP_BUILDING;

const isInNativephpFolder = __dirname.includes('nativephp\\electron') || __dirname.includes('nativephp/electron');
const outDir = isInNativephpFolder
    ? resolve(__dirname, 'out', 'main')
    : appPath
        ? join(appPath, 'nativephp', 'electron', 'out', 'main')
        : resolve(__dirname, 'out', 'main');

// Saat development (native:run) → pakai path absolut dari NATIVEPHP_BUILD_PATH
// Saat production build (native:build) → pakai path relatif yang valid di perangkat user
const nativephpBuildPath = isBuilding
    ? '../../../build'   // production: relatif dari out/main/ ke resources/build/
    : buildPath;         // development: path absolut dari env var

export default defineConfig({
    main: {
        build: {
            outDir,
            rollupOptions: {
                plugins: [
                    {
                        name: 'watch-external',
                        buildStart() {
                            if (! appPath) return;
                            this.addWatchFile(
                                join(appPath, 'app', 'Providers', 'NativeAppServiceProvider.php'),
                            );
                        },
                    },
                ],
            },
        },
        define: {
            'import.meta.env.MAIN_VITE_NATIVEPHP_BUILD_PATH': JSON.stringify(nativephpBuildPath),
        },
        plugins: [externalizeDepsPlugin()],
    },
});