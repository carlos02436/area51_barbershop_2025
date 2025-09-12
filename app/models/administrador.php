<?php
class Administrador {
    public $id;
    public $nombre;
    public $email;

    public function __construct($id, $nombre, $email) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
    }
}