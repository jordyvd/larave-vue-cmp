import { readFile, writeFile } from 'fs/promises';
import { resolve } from 'path';

const filePath = 'storage/version.txt';

if (!filePath) {
    console.error('Por favor, proporciona la ruta del archivo de texto.');
    process.exit(1);
}

async function setVersionToTxt() {
    try {
        let data = await readFile(resolve(filePath), 'utf-8');
        const version = parseInt(data) + 1;
        await writeFile(resolve(filePath), version.toString());
        return version;
    } catch (err) {
        console.error('Error al leer el archivo:', err);
        return 0;
    }
}

setVersionToTxt();
