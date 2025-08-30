<?php
require_once __DIR__ . '/../models/Video.php';

class VideoController {
    private $videoModel;

    public function __construct() {
        $this->videoModel = new Video();
    }

    public function listarVideos($limite = 3) {
        return $this->videoModel->obtenerVideos($limite);
    }
}