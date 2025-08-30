<?php
require_once __DIR__ . '/../models/TikTok.php';

class TikTokController {
    private $tiktokModel;

    public function __construct() {
        $this->tiktokModel = new TikTok();
    }

    public function listarVideos($limite = 10) {
        return $this->tiktokModel->obtenerVideos($limite);
    }
}