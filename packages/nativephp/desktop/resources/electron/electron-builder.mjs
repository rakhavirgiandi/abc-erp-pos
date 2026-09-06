import { exec } from 'child_process';
import { join } from 'path';

const appUrl          = process.env.APP_URL;
const appId           = process.env.NATIVEPHP_APP_ID;
const appName         = process.env.NATIVEPHP_APP_NAME;
const isBuilding      = process.env.NATIVEPHP_BUILDING;
const appAuthor       = process.env.NATIVEPHP_APP_AUTHOR;
const fileName        = process.env.NATIVEPHP_APP_FILENAME;
const appVersion      = process.env.NATIVEPHP_APP_VERSION;
const appCopyright    = process.env.NATIVEPHP_APP_COPYRIGHT;
const deepLinkProtocol = process.env.NATIVEPHP_DEEPLINK_SCHEME;
const updaterEnabled  = process.env.NATIVEPHP_UPDATER_ENABLED === 'true';

// APP_PATH hanya tersedia saat dipanggil via php artisan native:build/run.
// Saat app dijalankan langsung oleh user, APP_PATH tidak ada — semua
// yang bergantung pada APP_PATH harus di-guard dengan kondisi isBuilding.
const appPath         = process.env.APP_PATH || '';
const buildPath       = process.env.NATIVEPHP_BUILD_PATH || '';

const isWindows = process.argv.includes('--win');
const isLinux   = process.argv.includes('--linux');
const isDarwin  = process.argv.includes('--mac');

let targetOs;
if (isWindows) targetOs = 'win';
if (isLinux)   targetOs = 'linux';
if (isDarwin)  targetOs = 'mac';

let updaterConfig = {};
try {
    updaterConfig = JSON.parse(process.env.NATIVEPHP_UPDATER_CONFIG);
} catch {
    updaterConfig = {};
}

if (isBuilding) {
    console.log('  • updater config', updaterConfig);
}

// Hanya generate extraResources pgsql saat build (appPath tersedia)
const extraResources = [
    ...(buildPath ? [{
        from: buildPath,
        to: 'build',
        filter: ['**/*', '!{.git}'],
    }] : []),
    ...(appPath ? [{
        from: join(appPath, 'pgsql'),
        to: 'build/pgsql',
        filter: ['**/*'],
    }] : []),
];

// Icon path — hanya valid saat build
const iconIco = appPath ? join(appPath, 'build', 'icon.ico') : undefined;
const iconPng = appPath ? join(appPath, 'build', 'icon.png') : undefined;
const installerNsh = appPath ? join(appPath, 'build', 'installer.nsh') : undefined;

export default {
    appId,
    productName: appName,
    copyright:   appCopyright,

    directories: {
        buildResources: 'build',
        output: isBuilding && appPath
            ? join(appPath, 'nativephp', 'electron', 'dist')
            : undefined,
    },

    files: [
        '!**/.vscode/*',
        '!src/*',
        '!dist/*',
        '!electron.vite.config.{js,ts,mjs,cjs}',
        '!{.eslintignore,.eslintrc.cjs,.prettierignore,.prettierrc.yaml,dev-app-update.yml,CHANGELOG.md,README.md}',
        '!{.env,.env.*,.npmrc,pnpm-lock.yaml}',
    ],

    beforePack: async (context) => {
        const arch = { 1: 'x64', 3: 'arm64' }[context.arch];

        if (arch === undefined) {
            console.error('Cannot build PHP for unsupported architecture');
            process.exit(1);
        }

        console.log(`  • building php binary - exec php.js --${targetOs} --${arch}`);

        await new Promise((resolve, reject) => {
            const child = exec(`node php.js --${targetOs} --${arch}`);
            child.stdout?.on('data', (d) => process.stdout.write(d));
            child.stderr?.on('data', (d) => process.stderr.write(d));
            child.on('exit', (code) => code === 0 ? resolve() : reject(new Error(`php.js failed: ${code}`)));
            child.on('error', reject);
        });
    },

    afterSign: 'build/notarize.js',

    win: {
        target: [{ target: 'nsis', arch: ['x64'] }],
        ...(iconPng ? { icon: iconPng } : {}),
    },

    nsis: {
        oneClick: false,
        perMachine: true,
        allowElevation: true,
        allowToChangeInstallationDirectory: true,
        ...(iconIco ? {
            installerIcon:       iconIco,
            uninstallerIcon:     iconIco,
            installerHeaderIcon: iconIco,
        } : {}),
        createDesktopShortcut:  true,
        createStartMenuShortcut: true,
        shortcutName: 'ABC POS',
        include: installerNsh,
    },

    protocols: {
        name:    deepLinkProtocol,
        schemes: [deepLinkProtocol],
    },

    mac: {
        entitlementsInherit: 'build/entitlements.mac.plist',
        artifactName: appName + '-${version}-${arch}.${ext}',
        extendInfo: {
            NSCameraUsageDescription:      "Application requests access to the device's camera.",
            NSMicrophoneUsageDescription:  "Application requests access to the device's microphone.",
            NSDocumentsFolderUsageDescription: "Application requests access to the user's Documents folder.",
            NSDownloadsFolderUsageDescription: "Application requests access to the user's Downloads folder.",
        },
    },

    dmg: {
        artifactName: appName + '-${version}-${arch}.${ext}',
    },

    linux: {
        target:     ['AppImage', 'deb'],
        maintainer: appUrl,
        category:   'Utility',
    },

    appImage: {
        artifactName: appName + '-${version}.${ext}',
    },

    npmRebuild: false,

    extraMetadata: {
        name:     fileName,
        homepage: appUrl,
        version:  appVersion,
        author:   appAuthor,
    },

    extraResources,

    ...(updaterEnabled ? { publish: updaterConfig } : {}),
};
