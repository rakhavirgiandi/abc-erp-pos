import fs from "fs";
import fs_extra from 'fs-extra';
const { copySync, removeSync, ensureDirSync } = fs_extra;
import { join, dirname } from "path";
import unzip from "yauzl";

const isBuilding = Boolean(process.env.NATIVEPHP_BUILDING);
const phpBinaryPath = process.env.NATIVEPHP_PHP_BINARY_PATH;
const phpVersion = process.env.NATIVEPHP_PHP_BINARY_VERSION;

// Differentiates for Serving and Building
const isArm64 = isBuilding ? process.argv.includes('--arm64') : process.arch.includes('arm64');
const isWindows = isBuilding ?  process.argv.includes('--win') : process.platform.includes('win32');
const isLinux = isBuilding ?  process.argv.includes('--linux') : process.platform.includes('linux');
const isDarwin = isBuilding ?  process.argv.includes('--mac') : process.platform.includes('darwin');

const platform = {
    os: false,
    arch: false,
    phpBinary: 'php'
};

if (isWindows) {
    platform.os = 'win';
    platform.arch = 'x64';
    platform.phpBinary += '.exe';
}

if (isLinux) {
    platform.os = 'linux';
    platform.arch = 'x64';
}

if (isDarwin) {
    platform.os = 'mac';
    platform.arch = 'x64';
}

if (isArm64) {
    platform.arch = 'arm64';
}

if (isBuilding) {
    platform.arch = process.argv.includes('--x64') ? 'x64' : platform.arch;
    platform.arch = process.argv.includes('--arm64') ? 'arm64' : platform.arch;
}

const phpVersionZip = 'php-' + phpVersion + '.zip';
const binarySrcDir = join(phpBinaryPath, platform.os, platform.arch, phpVersionZip);
const binaryDestDir = join(process.env.NATIVEPHP_BUILD_PATH, 'php');

console.log('Binary Source: ', binarySrcDir);
console.log('Binary Filename: ', platform.phpBinary);
console.log('PHP version: ' + phpVersion);

if (platform.phpBinary) {
    try {
        console.log('Unzipping all files from ' + binarySrcDir + ' to ' + binaryDestDir);
        removeSync(binaryDestDir);
        ensureDirSync(binaryDestDir);

        unzip.open(binarySrcDir, { lazyEntries: true }, function (err, zipfile) {
            if (err) throw err;

            zipfile.readEntry();

            zipfile.on("entry", function (entry) {
                const entryPath = join(binaryDestDir, entry.fileName);

                // Jika entry adalah folder (diakhiri "/")
                if (/\/$/.test(entry.fileName)) {
                    ensureDirSync(entryPath);
                    zipfile.readEntry();
                    return;
                }

                // Pastikan folder tujuan ada sebelum menulis file
                ensureDirSync(dirname(entryPath));

                zipfile.openReadStream(entry, function (err, readStream) {
                    if (err) throw err;

                    const writeStream = fs.createWriteStream(entryPath);
                    readStream.pipe(writeStream);

                    writeStream.on("close", function () {
                        zipfile.readEntry();
                    });
                });
            });

            zipfile.on("end", function () {
                console.log('Selesai mengekstrak semua file ke ', binaryDestDir);

                // Beri permission execute khusus untuk binary PHP
                const binaryPath = join(binaryDestDir, platform.phpBinary);
                fs.chmod(binaryPath, 0o755, (err) => {
                    if (err) {
                        console.log(`Error setting permissions: ${err}`);
                    } else {
                        console.log('Permission set for', binaryPath);
                    }
                });
            });
        });
    } catch (e) {
        console.error('Error extracting PHP binary archive', e);
    }
}