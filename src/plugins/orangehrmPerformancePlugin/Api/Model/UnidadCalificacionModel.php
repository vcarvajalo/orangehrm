<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software: you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with OrangeHRM.
 * If not, see <https://www.gnu.org/licenses/>.
 */

namespace OrangeHRM\Performance\Api\Model;

use OrangeHRM\Core\Api\V2\Serializer\ModelTrait;
use OrangeHRM\Core\Api\V2\Serializer\Normalizable;
use OrangeHRM\Entity\UnidadCalificacion;
use OrangeHRM\Performance\Traits\Service\UnidadCalificacionServiceTrait;

/**
 * @OA\Schema(
 *     schema="Performance-UnidadCalificacionModel",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="descripcion", type="string"),
 *     @OA\Property(property="valor", type="integer"),
 *     @OA\Property(property="estado", type="boolean"),
 *     @OA\Property(property="deletable", type="boolean")
 * )
 */
class UnidadCalificacionModel implements Normalizable
{
    use ModelTrait {
        ModelTrait::toArray as entityToArray;
    }
    use UnidadCalificacionServiceTrait;

    /**
     * Constructor del modelo
     * @param UnidadCalificacion $unidadCalificacion
     */
    public function __construct(UnidadCalificacion $unidadCalificacion)
    {
        $this->setEntity($unidadCalificacion);
        $this->setFilters(
            [
                'id',
                'descripcion',
                'valor',
                'estado',
            ]
        );
        $this->setAttributeNames(
            [
                'id',
                'descripcion',
                'valor',
                'estado',
            ]
        );
    }

    /**
     * Convierte el modelo a un array para la serialización
     * @return array
     */
    public function toArray(): array
    {
        $deletable = $this->getUnidadCalificacionService()->getUnidadCalificacionDao()->isUnidadCalificacionDeletable(
            $this->getEntity()->getId()
        );
        $result = $this->entityToArray();
        $result['deletable'] = $deletable;
        return $result;
    }
}
