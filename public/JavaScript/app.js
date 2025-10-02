import dayjs from "dayjs";
import customParse from "dayjs/plugin/customParseFormat";
dayjs.extend(customParse);

/* UI elements */
const $ = (s) => document.querySelector(s);
const selCliente = $("#cliente");
const selBarbero = $("#barbero");
const selServicio = $("#servicio");
const inpFecha = $("#fecha");
const selHora = $("#hora");
const barberoNotice = $("#barberoNotice");
const fechaErrors = $("#fechaErrors");
const preview = $("#servicePreview");
const previewImg = $("#serviceImg");
const previewName = $("#serviceName");
const form = $("#citaForm");
const resetBtn = $("#resetBtn");

/* Constants */
const OPEN_HOUR = 8;
const CLOSE_HOUR = 20;
const SLOT_MINUTES = 60;

/* Helpers */
const to12h = (h, m = 0) => dayjs().hour(h).minute(m).format("h:mm A");
const pad = (n) => String(n).padStart(2, "0");
const timeStr = (h, m = 0) => `${pad(h)}:${pad(m)}:00`;
const isToday = (d) => dayjs(d).isSame(dayjs(), "day");

/* Populate selects from PHP (asume que tu HTML ya trae opciones) */
async function bootstrap() {
  selBarbero.value = selBarbero.options[0]?.value || "";
  selServicio.value = selServicio.options[0]?.value || "";
  inpFecha.min = dayjs().format("YYYY-MM-DD");
  inpFecha.value = dayjs().format("YYYY-MM-DD");
  updateServicePreview();
  updateBarberoNotice();
  await refreshHoras();
}

/* Generate available slots */
function generateSlotsFor(barberoId, fechaISO) {
  let start = OPEN_HOUR;
  let end = CLOSE_HOUR;
  const slots = [];
  const d = dayjs(fechaISO);
  const isSunday = d.day() === 0;

  if (Number(barberoId) === 1) {
    if (isSunday) return [];
    for (let h = start; h < end; h += SLOT_MINUTES / 60) {
      if (h >= 12 && h < 14) continue;
      slots.push(timeStr(h, 0));
    }
  } else {
    for (let h = start; h < end; h += SLOT_MINUTES / 60) {
      slots.push(timeStr(h, 0));
    }
  }

  if (isToday(fechaISO)) {
    const now = dayjs();
    return slots.filter(t => {
      const [H, M] = t.split(":").map(Number);
      const slot = now.hour(H).minute(M).second(0);
      return slot.isAfter(now);
    });
  }

  return slots;
}

/* Refresh hour options */
async function refreshHoras() {
  const barberoId = selBarbero.value;
  const fechaISO = inpFecha.value;
  selHora.innerHTML = `<option value="">Selecciona una hora</option>`;
  fechaErrors.textContent = "";

  if (!barberoId || !fechaISO) return;

  const baseSlots = generateSlotsFor(barberoId, fechaISO);
  if (baseSlots.length === 0) {
    fechaErrors.textContent = "No hay disponibilidad para el barbero y fecha seleccionados.";
    return;
  }

  baseSlots.forEach(s => {
    const [H, M] = s.split(":").map(Number);
    const opt = document.createElement("option");
    opt.value = s;
    opt.textContent = to12h(H, M);
    selHora.appendChild(opt);
  });
}

/* UI updates */
function updateBarberoNotice() {
  const id = Number(selBarbero.value);
  if (id === 1) barberoNotice.textContent = "Horario: Lun-Sáb 8:00–12:00 y 14:00–20:00. Domingo no trabaja.";
  else if (id >= 2) barberoNotice.textContent = "Horario: Diario 8:00–20:00";
  else barberoNotice.textContent = "";
}

function updateServicePreview() {
  const opt = selServicio.selectedOptions[0];
  if (!opt || !opt.value) {
    preview.hidden = true;
    return;
  }

  const name = opt.textContent || "";
  const imgFile = opt.dataset.img || "";
  const imgPath = imgFile ? `app/uploads/servicios/${imgFile}` : "";
  previewName.textContent = name;
  previewImg.src = imgPath;
  preview.hidden = false;
}

/* Construir WhatsApp */
async function buildWhatsAppURL({ id_barbero, cliente, servicio, fecha_cita, hora_cita }) {
  const selBarbero = document.getElementById("barbero");
  const selectedOption = selBarbero.selectedOptions[0];
  
  // Validaciones
  if (!selectedOption) {
    alert("Error: No hay barbero seleccionado");
    return "#";
  }

  const bNombre = selectedOption.textContent.trim() || "";
  let bTelefono = selectedOption.dataset.telefono || "";

  // Limpiar número
  bTelefono = bTelefono.replace(/\D/g, "");

  // Validar teléfono
  if (!bTelefono) {
    alert("Error: No se encontró número de teléfono para el barbero seleccionado");
    return "#";
  }

  // Limpiar valores de cliente y servicio
  const clienteLimpio = cliente.trim();
  const servicioLimpio = servicio.trim();

  // Formatear fecha
  const fechaTxt = dayjs(fecha_cita).format("DD/MM/YYYY");

  // Formatear hora a 12h
  const [H, M] = hora_cita.split(":");
  const horaTxt = to12h(Number(H), Number(M));

  // Función para convertir a formato de 12 horas
  function to12h(hours, minutes) {
    const suffix = hours >= 12 ? "PM" : "AM";
    let hour = hours % 12;
    if (hour === 0) hour = 12;
    return `${hour}:${String(minutes).padStart(2, '0')} ${suffix}`;
  }

  // Construir mensaje
  const msg =
  `📌 *CITA CONFIRMADA - ÁREA 51 - LA SUPER BARBER*\n\n` +
  `💈 *Nueva cita agendada para ${bNombre}*\n\n` +
  `👤 *Cliente:* ${clienteLimpio}\n` +
  `💇‍♂️ *Servicio:* ${servicioLimpio}\n` +
  `📅 *Fecha:* ${fechaTxt}\n` +
  `🕒 *Hora:* ${horaTxt}\n\n` +
  `📍 *Ubicación:* https://maps.google.com/?q=Transversal+2+%23+04-01+Barrio+17+de+Febrero\n\n` +
  `🔁 ¿Necesitas cambiar tu cita? Responde con:\n*REPROGRAMAR*\n\n` +
  `❌ ¿Cancelar? Responde con:\n*CANCELAR*\n\n` +
  `✅ ¡Prepárate para un excelente servicio!`;

  // Retornar URL de WhatsApp
  return `https://api.whatsapp.com/send?phone=${bTelefono}&text=${encodeURIComponent(msg)}`;
}

// Función para enviar el mensaje (debe ser llamada desde tu evento de submit)
async function enviarMensajeWhatsApp(citaData) {
  try {
    const whatsappURL = await buildWhatsAppURL(citaData);
    
    // Evitar que se abra si hay error
    if (whatsappURL === "#") {
      return false;
    }
    
    // Abrir en nueva pestaña
    window.open(whatsappURL, '_blank');
    return true;
    
  } catch (error) {
    console.error("Error al generar URL de WhatsApp:", error);
    alert("Error al preparar el mensaje de WhatsApp");
    return false;
  }
}

/* Submit handler */
form.addEventListener("submit", async (e) => {
  e.preventDefault();

  if (!selCliente.value || !selBarbero.value || !selServicio.value || !inpFecha.value || !selHora.value) {
    fechaErrors.textContent = "Completa todos los campos.";
    return;
  }

  const formData = new FormData(form);

  try {
    const res = await fetch("app/views/citas/crear_cita.php", {
      method: "POST",
      body: formData
    });

    const data = await res.json();

    if (data.ok) {
      const clienteTxt = selCliente.selectedOptions[0]?.textContent || "";
      const servicioTxt = selServicio.selectedOptions[0]?.textContent || "";

      const wa = await buildWhatsAppURL({
        id_barbero: Number(selBarbero.value),
        cliente: clienteTxt,
        servicio: servicioTxt,
        fecha_cita: inpFecha.value,
        hora_cita: selHora.value
      });

      window.open(wa, "_blank", "noopener,noreferrer");

      // Limpiar formulario y refrescar UI
      form.reset();
      preview.hidden = true;
      updateBarberoNotice();
      await refreshHoras();
      fechaErrors.textContent = "";

      // Redirigir a la sección de Home
      window.location.href = "index.php?page=home";
    } else {
      fechaErrors.textContent = data.error || "Error desconocido al guardar la cita.";
    }

  } catch (err) {
    fechaErrors.textContent = "Error de conexión con el servidor.";
    console.error(err);
  }
});

/* Listeners */
selBarbero.addEventListener("change", async () => {
  updateBarberoNotice();
  await refreshHoras();
});
inpFecha.addEventListener("change", refreshHoras);
selServicio.addEventListener("change", updateServicePreview);
resetBtn.addEventListener("click", () => {
  form.reset();
  preview.hidden = true;
  updateBarberoNotice();
  refreshHoras();

  // Redirigir a la sección Contáctanos
  window.location.href = "index.php?page=home#contactanos";
});

/* Init */
bootstrap();