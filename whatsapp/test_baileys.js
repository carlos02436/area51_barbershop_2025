const { default: makeWASocket, useMultiFileAuthState } = require("@adiwajshing/baileys");

async function start() {
    const { state, saveCreds } = await useMultiFileAuthState('auth_info');

    const sock = makeWASocket({
        auth: state,
        printQRInTerminal: true
    });

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect } = update;
        if(connection === 'close') {
            console.log('Conexión cerrada, reintentando...', lastDisconnect?.error);
            start();
        } else if(connection === 'open') {
            console.log('Conectado exitosamente!');
            
            // ENVIAR MENSAJE SOLO AQUÍ, DESPUÉS DE CONECTARSE
            const number = "573175625131@s.whatsapp.net";
            await sock.sendMessage(number, { text: "Hola desde Baileys!" });
        }
    });
}

start();