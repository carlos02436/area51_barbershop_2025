<?php
require_once __DIR__ . '/../models/Servicio.php';

class ServicioController {
    private $servicioModel;

    public function __construct() {
        $this->servicioModel = new Servicio();
    }

    // 🔹 Listar todos los servicios
    public function listarServicios() {
        return $this->servicioModel->obtenerServicios();
    }

    // 🔹 Obtener un servicio por ID
    public function mostrarServicio($id) {
        return $this->servicioModel->obtenerServicioPorId($id);
    }

    // 🔹 Crear un nuevo servicio con imagen
    public function crearServicio($data) {
        // Procesar imagen
        $img_servicio = null;
        if (isset($_FILES['img_servicio']) && $_FILES['img_servicio']['error'] === UPLOAD_ERR_OK) {
            $directorio = __DIR__ . '/../uploads/servicios/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $extension = pathinfo($_FILES['img_servicio']['name'], PATHINFO_EXTENSION);
            $img_servicio = time() . "_" . uniqid() . "." . strtolower($extension);
            $rutaDestino = $directorio . $img_servicio;

            if (move_uploaded_file($_FILES['img_servicio']['tmp_name'], $rutaDestino)) {
                $data['img_servicio'] = $img_servicio;
            } else {
                $data['img_servicio'] = null;
            }
        }

        return $this->servicioModel->crearServicio($data);
    }

    // 🔹 Actualizar un servicio con opción de nueva imagen
    public function actualizarServicio($id, $data) {
        if (isset($_FILES['img_servicio']) && $_FILES['img_servicio']['error'] === UPLOAD_ERR_OK) {
            $directorio = __DIR__ . '/../uploads/servicios/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $extension = pathinfo($_FILES['img_servicio']['name'], PATHINFO_EXTENSION);
            $img_servicio = time() . "_" . uniqid() . "." . strtolower($extension);
            $rutaDestino = $directorio . $img_servicio;

            if (move_uploaded_file($_FILES['img_servicio']['tmp_name'], $rutaDestino)) {
                $data['img_servicio'] = $img_servicio;
            }
        }

        return $this->servicioModel->actualizarServicio($id, $data);
    }

    // 🔹 Eliminar un servicio
    public function eliminarServicio($id) {
        return $this->servicioModel->eliminarServicio($id);
    }
}