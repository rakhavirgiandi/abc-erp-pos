import { exec } from 'child_process';
import { join } from 'path';

const appUrl = process.env.APP_URL;
const appId = process.env.NATIVEPHP_APP_ID;
const appName = process.env.NATIVEPHP_APP_NAME;
const isBuilding = process.env.NATIVEPHP_BUILDING;
const appAuthor = process.env.NATIVEPHP_APP_AUTHOR;
const fileName = process.env.NATIVEPHP_APP_FILENAME;
const appVersion = process.env.NATIVEPHP_APP_VERSION;
const appCopyright = process.env.NATIVEPHP_APP_COPYRIGHT;
const deepLinkProtocol = process.env.NATIVEPHP_DEEPLINK_SCHEME;
const updaterEnabled = process.env.NATIVEPHP_UPDATER_ENABLED === 'true';
// const deleteAppDataOnUninstall = process.env.NATIVEPHP_NSIS_DELETE_APP_DATA === 'true';

// Azure signing configuration
// const azureEndpoint = process.env.NATIVEPHP_AZURE_ENDPOINT;
// const azureCertificateProfileName = process.env.NATIVEPHP_AZURE_CERTIFICATE_PROFILE_NAME;
// const azureCodeSigningAccountName = process.env.NATIVEPHP_AZURE_CODE_SIGNING_ACCOUNT_NAME;

// Since we do not copy the php executable here, we only need these for building
const isWindows = process.argv.includes('--win');
const isLinux = process.argv.includes('--linux');
const isDarwin = process.argv.includes('--mac');

let targetOs;

if (isWindows) {
    targetOs = 'win';
}

if (isLinux) {
    targetOs = 'linux';
}

if (isDarwin) {
    targetOs = 'mac';
}

let updaterConfig = {};

try {
    updaterConfig = process.env.NATIVEPHP_UPDATER_CONFIG;
    updaterConfig = JSON.parse(updaterConfig);
} catch {
    updaterConfig = {};
}

if (isBuilding) {
    console.log('  • updater config', updaterConfig);
}

export default {
    appId: appId,
    productName: appName,
    copyright: appCopyright,
    directories: {
        buildResources: 'build',
        output: isBuilding ? join(process.env.APP_PATH, 'nativephp', 'electron', 'dist') : undefined,
    },
    files: [
        '!**/.vscode/*',
        '!src/*',
        '!dist/*',
        '!electron.vite.config.{js,ts,mjs,cjs}',
        '!{.eslintignore,.eslintrc.cjs,.prettierignore,.prettierrc.yaml,dev-app-update.yml,CHANGELOG.md,README.md}',
        '!{.env,.env.*,.npmrc,pnpm-lock.yaml}',
        // 'pgsql/**/*',
    ],
    beforePack: async (context) => {
        let arch = {
            1: 'x64',
            3: 'arm64',
        }[context.arch];

        if (arch === undefined) {
            console.error('Cannot build PHP for unsupported architecture');
            process.exit(1);
        }

        console.log(`  • building php binary - exec php.js --${targetOs} --${arch}`);
        exec(`node php.js --${targetOs} --${arch}`);
    },
    afterSign: 'build/notarize.js',
    win: {
        target: [
            {
                target: 'nsis',
                arch: ['x64'],
            },
        ],
        icon: 'packages/nativephp/desktop/resources/build/icon.png',
    },
    nsis: {
        // true = satu installer untuk semua user (install ke Program Files)
        // false = per-user install (tidak butuh admin untuk install file,
        //         tapi pg_ctl register tetap butuh admin)
        oneClick: false,
        perMachine: true,
        allowElevation: true,
        allowToChangeInstallationDirectory: true,
        installerIcon: 'packages/nativephp/desktop/resources/build/icon.png',
        uninstallerIcon: 'packages/nativephp/desktop/resources/build/icon.png',
        installerHeaderIcon: 'packages/nativephp/desktop/resources/build/icon.png',
        createDesktopShortcut: true,
        createStartMenuShortcut: true,
        shortcutName: 'ABC POS',

        // Script NSIS custom kita
        // File ini akan di-include ke dalam NSIS script yang di-generate Electron Builder
        include: 'build/installer.nsh',

        // Atau pakai script penuh (menggantikan script default Electron Builder):
        // script: 'build/installer.nsi',
    },
    protocols: {
        name: deepLinkProtocol,
        schemes: [deepLinkProtocol],
    },
    mac: {
        entitlementsInherit: 'build/entitlements.mac.plist',
        artifactName: appName + '-${version}-${arch}.${ext}',
        extendInfo: {
            NSCameraUsageDescription: "Application requests access to the device's camera.",
            NSMicrophoneUsageDescription: "Application requests access to the device's microphone.",
            NSDocumentsFolderUsageDescription: "Application requests access to the user's Documents folder.",
            NSDownloadsFolderUsageDescription: "Application requests access to the user's Downloads folder.",
        },
    },
    dmg: {
        artifactName: appName + '-${version}-${arch}.${ext}',
    },
    linux: {
        target: ['AppImage', 'deb'],
        maintainer: appUrl,
        category: 'Utility',
    },
    appImage: {
        artifactName: appName + '-${version}.${ext}',
    },
    npmRebuild: false,
    extraMetadata: {
        name: fileName,
        homepage: appUrl,
        version: appVersion,
        author: appAuthor,
    },
    extraResources: [
        {
            from: process.env.NATIVEPHP_BUILD_PATH,
            to: 'build',
            filter: ['**/*', '!{.git}'],
        },
        {
            from: join(process.env.APP_PATH, 'pgsql'),
            to: 'pgsql',
            filter: ['**/*'],
        },
    ],
    ...(updaterEnabled ? { publish: updaterConfig } : {}),
};
