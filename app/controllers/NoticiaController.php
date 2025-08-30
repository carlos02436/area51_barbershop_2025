<?php
// app/controllers/NoticiaController.php
require_once __DIR__ . '/../models/Noticia.php';

class NoticiaController {
    private $noticiaModel;

    public function __construct() {
        $this->noticiaModel = new Noticia(); // ahora la clase Noticia está incluida correctamente
    }

    public function listarNoticias($limite = 3) {
        return $this->noticiaModel->obtenerNoticias($limite);
    }
}