const venom = require('venom-bot');
const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();
const PORT = 3000;

app.use(bodyParser.json());
app.use(cors());

let client;
let qrCodeData = '';

function iniciarWhatsApp() {
  venom
    .create(
      'session-barberia',
      (statusSession) => {
        console.log('Estado de sesión:', statusSession);
      },
      (base64Qr) => {
        qrCodeData = base64Qr;
        console.log('QR actualizado');
      },
      {
        headless: true,
        multidevice: true
      }
    )
    .then((c) => {
      client = c;
      console.log('✅ WhatsApp listo para enviar mensajes');

      client.onStateChange((state) => {
        console.log('Estado WhatsApp:', state);
        if (state === 'CONFLICT' || state === 'DISCONNECTED') {
          console.log('⚠️ Reconectando WhatsApp...');
          iniciarWhatsApp();
        }
      });
    })
    .catch((err) => {
      console.error('❌ Error iniciando WhatsApp:', err);
      setTimeout(iniciarWhatsApp, 10000);
    });
}

iniciarWhatsApp();

// Endpoint seguro para enviar mensajes
app.post('/send-message', async (req, res) => {
  if (!client) return res.status(500).send('WhatsApp no está listo');

  const { cliente, barbero } = req.body;

  // Validación de datos
  if (!cliente || !cliente.telefono || !cliente.mensaje ||
      !barbero || !barbero.telefono || !barbero.mensaje) {
    return res.status(400).send('Datos incompletos para enviar mensajes');
  }

  try {
    // Agregar @c.us a los números si no lo tienen
    const numeroCliente = cliente.telefono.includes('@c.us') ? cliente.telefono : cliente.telefono + '@c.us';
    const numeroBarbero = barbero.telefono.includes('@c.us') ? barbero.telefono : barbero.telefono + '@c.us';

    await client.sendText(numeroCliente, cliente.mensaje);
    await client.sendText(numeroBarbero, barbero.mensaje);

    console.log(`Mensajes enviados a ${numeroCliente} y ${numeroBarbero}`);
    res.send('✅ Mensajes enviados correctamente');
  } catch (error) {
    console.error('Error enviando mensajes:', error);
    res.status(500).send('❌ Error enviando los mensajes');
  }
});

app.get('/qr', (req, res) => {
  if (!qrCodeData) return res.send('QR aún no disponible, espera unos segundos...');
  res.send(`<h3>Escanea este QR con WhatsApp Web para iniciar sesión</h3>
            <img src="data:image/png;base64,${qrCodeData}" alt="QR Venom-Bot">`);
});

app.listen(PORT, () => {
  console.log(`Servidor Node.js corriendo en http://localhost:${PORT}`);
  console.log(`Para ver el QR (si es primera vez): http://localhost:${PORT}/qr`);
});