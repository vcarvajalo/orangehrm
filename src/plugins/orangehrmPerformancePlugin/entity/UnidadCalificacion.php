<?php

namespace OrangeHRM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="inf_unidad_Calificacion")
 */
class UnidadCalificacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private ?string $descripcion = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $valor = null;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private bool $estado = true;

    /**
     * Obtener el ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtener la descripción.
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * Establecer la descripción.
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * Obtener el valor.
     */
    public function getValor(): ?int
    {
        return $this->valor;
    }

    /**
     * Establecer el valor.
     */
    public function setValor(int $valor): self
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Obtener el estado.
     */
    public function getEstado(): bool
    {
        return $this->estado;
    }

    /**
     * Establecer el estado.
     */
    public function setEstado(bool $estado): self
    {
        $this->estado = $estado;
        return $this;
    }
}