<?php
class Nodo {
    public $dato;
    public $siguiente;
    public $anterior;
    public function __construct($dato) {
        $this->dato = $dato;
        $this->siguiente = null;
        $this->anterior = null;
    }
}

class ListaDoble {
    public $cabeza;
    public $cola;
    public function __construct() {
        $this->cabeza = null;
        $this->cola = null;
    }
    
    public function insertar($dato) {
        $nuevo = new Nodo($dato);
        if ($this->cabeza === null) {
            $this->cabeza = $nuevo;
            $this->cola = $nuevo;
        } else {
            $this->cola->siguiente = $nuevo;
            $nuevo->anterior = $this->cola;
            $this->cola = $nuevo;
        }
    }
    public function ordenarBubbleSort($campo) {
        if ($this->cabeza === null) return;
        $intercambiado = true;
        while ($intercambiado) {
            $intercambiado = false;
            $actual = $this->cabeza;
            while ($actual->siguiente !== null) {
                if ($actual->dato[$campo] > $actual->siguiente->dato[$campo]) {
                    $temp = $actual->dato;
                    $actual->dato = $actual->siguiente->dato;
                    $actual->siguiente->dato = $temp;
                    $intercambiado = true;
                }
                $actual = $actual->siguiente;
            }
        }
    }
    public function obtenerElementos() {
        $elementos = [];
        $actual = $this->cabeza;
        while ($actual !== null) {
            $elementos[] = $actual->dato;
            $actual = $actual->siguiente;
        }
        return $elementos;
    }
}
?>