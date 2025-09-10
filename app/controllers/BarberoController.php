<?php
require_once __DIR__ . '/../models/Barbero.php';

class BarberoController {
    private $barberoModel;

    public function __construct() {
        $this->barberoModel = new Barbero();
    }

    public function listar() {
        return $this->barberoModel->obtenerTodos();
    }

    // Para mostrar pocos en home/cards
    public function listarBarberos() {
        if (method_exists($this->barberoModel, 'obtenerBarberos')) {
            return $this->barberoModel->obtenerTodos();
        }
        return $this->barberoModel->obtenerTodos();
    }

    public function mostrar($id) {
        return $this->barberoModel->obtenerPorId($id);
    }

    public function crear($datos) {
        $datos = $this->procesarImagen($datos, 'img_barberos');
        return $this->barberoModel->crear($datos);
    }

    // Aquí va la actualización (antes tenías "editar" que no existe en el modelo)
    public function actualizar($id, $datos) {
        // Si el form incluye 'img_actual' se conservará cuando no se suba una nueva
        $datos = $this->procesarImagen($datos, 'img_barberos', $id);
        return $this->barberoModel->actualizar($id, $datos);
    }

    public function eliminar($id) {
        // borrar imagen física si existe
        $barbero = $this->barberoModel->obtenerPorId($id);
        if ($barbero && !empty($barbero['img_barberos'])) {
            $ruta = __DIR__ . '/../../uploads/barberos/' . $barbero['img_barberos'];
            if (file_exists($ruta)) @unlink($ruta);
        }
        return $this->barberoModel->eliminar($id);
    }

    // Manejo de archivos centralizado
    private function procesarImagen($datos, $inputName = 'img_barberos', $id = null) {
        // Si se subió archivo nuevo
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $directorio = __DIR__ . '/../../uploads/barberos/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }
            $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
            $nombreArchivo = time() . '_' . bin2hex(random_bytes(5)) . '.' . strtolower($ext);
            $rutaDestino = $directorio . $nombreArchivo;

            if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $rutaDestino)) {
                // eliminar la antigua si estamos actualizando
                if ($id) {
                    $ant = $this->barberoModel->obtenerPorId($id);
                    if ($ant && !empty($ant['img_barberos'])) {
                        $vieja = $directorio . $ant['img_barberos'];
                        if (file_exists($vieja)) @unlink($vieja);
                    }
                }
                $datos[$inputName] = $nombreArchivo;
            }
        } else {
            // No subió nuevo archivo: conservar el actual si el formulario lo envía como img_actual
            if (isset($datos['img_actual']) && !empty($datos['img_actual'])) {
                $datos[$inputName] = $datos['img_actual'];
                unset($datos['img_actual']);
            } else {
                // si el modelo espera la clave, asegúrate que exista (o déjalo null)
                if (!isset($datos[$inputName])) $datos[$inputName] = null;
            }
        }

        return $datos;
    }
}