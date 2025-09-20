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

// Función para formatear número WhatsApp
const formatoNumero = (num) => num.replace(/\D/g, '') + '@c.us';

function iniciarWhatsApp() {
  venom
    .create(
      'session-barberia', // Carpeta donde se guarda la sesión
      (statusSession) => {
        console.log('Estado de sesión:', statusSession);
      },
      (base64Qr) => {
        qrCodeData = base64Qr;
        console.log('QR actualizado');
      },
      {
        headless: "new", // nueva implementación headless
        multidevice: true,
        useChrome: true // Puppeteer usará Chromium interno
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

// Endpoint para enviar mensajes
app.post('/send-message', async (req, res) => {
  console.log('🚀 /send-message recibido', req.body); // LOG para verificar

  const { cliente, barbero } = req.body;

  if (!cliente || !cliente.telefono || !cliente.mensaje ||
      !barbero || !barbero.telefono || !barbero.mensaje) {
    return res.status(400).send('Datos incompletos para enviar mensajes');
  }

  // Espera hasta que el client esté listo
  const esperarCliente = () => new Promise((resolve) => {
    const intervalo = setInterval(() => {
      if (client) {
        clearInterval(intervalo);
        resolve();
      }
    }, 500);
  });

  await esperarCliente();

  try {
    const numeroCliente = formatoNumero(cliente.telefono);
    const numeroBarbero = formatoNumero(barbero.telefono);

    console.log('Enviando a cliente:', numeroCliente, cliente.mensaje);
    console.log('Enviando a barbero:', numeroBarbero, barbero.mensaje);

    await client.sendText(numeroCliente, cliente.mensaje);
    await client.sendText(numeroBarbero, barbero.mensaje);

    res.send('✅ Mensajes enviados correctamente');
  } catch (error) {
    console.error('❌ Error enviando mensajes:', error);
    res.status(500).send('Error enviando mensajes');
  }
});

// Endpoint para mostrar QR
app.get('/qr', (req, res) => {
  if (!qrCodeData) return res.send('QR aún no disponible, espera unos segundos...');
  res.send(`<h3>Escanea este QR con WhatsApp Web para iniciar sesión</h3>
            <img src="data:image/png;base64,${qrCodeData}" alt="QR Venom-Bot">`);
});

app.listen(PORT, () => {
  console.log(`Servidor Node.js corriendo en http://localhost:${PORT}`);
  console.log(`Para ver el QR (si es primera vez): http://localhost:${PORT}/qr`);
});