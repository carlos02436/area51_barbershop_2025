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
const OPEN_HOUR = 9;
const CLOSE_HOUR = 19;
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

  // Puedes obtener citas desde PHP si quieres bloquear horas ocupadas
  // Por ahora solo slots libres según horario
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
  else if (id >= 2) barberoNotice.textContent = "Horario: Diario 8:00–20:00 (continuo).";
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

/* Build WhatsApp URL */
async function buildWhatsAppURL({ id_barbero, cliente, servicio, fecha_cita, hora_cita }) {
  // Obtén los datos del barbero desde tu PHP si quieres
  const bNombre = selBarbero.selectedOptions[0]?.textContent || "";
  const bTelefono = selBarbero.selectedOptions[0]?.dataset.telefono || "";

  const fechaTxt = dayjs(fecha_cita).format("DD/MM/YYYY");
  const [H, M] = hora_cita.split(":");
  const horaTxt = to12h(Number(H), Number(M));

  const msg = 
    `💈 Hola ${bNombre}, se ha agendado una cita.\n` +
    `👤 Cliente: ${cliente}\n` +
    `💇‍♂️ Servicio: ${servicio}\n` +
    `📅 Fecha: ${fechaTxt}\n` +
    `🕒 Hora: ${horaTxt}\n` +
    `✅ ¡Gracias por usar nuestra barbería!`;

  return `https://wa.me/${bTelefono || ""}?text=${encodeURIComponent(msg)}`;
}

/* Submit handler (solo envía al PHP, PHP guarda la cita) */
form.addEventListener("submit", async (e) => {
  e.preventDefault();

  if (!selCliente.value || !selBarbero.value || !selServicio.value || !inpFecha.value || !selHora.value) {
    fechaErrors.textContent = "Completa todos los campos.";
    return;
  }

  // Enviar formulario al PHP
  const formData = new FormData(form);

  try {
    const res = await fetch("app/views/citas/crear_cita.php", {
      method: "POST",
      body: formData
    });

    const data = await res.json(); // PHP debe devolver JSON {ok:true} o {ok:false,error:""}

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
});

/* Init */
bootstrap();