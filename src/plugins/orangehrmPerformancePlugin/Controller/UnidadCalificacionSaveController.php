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

namespace OrangeHRM\Performance\Controller;

use OrangeHRM\Core\Controller\AbstractVueController;
use OrangeHRM\Core\Vue\Component;
use OrangeHRM\Core\Vue\Prop;
use OrangeHRM\Framework\Http\Request;
use OrangeHRM\Performance\Traits\Service\UnidadCalificacionServiceTrait;

class UnidadCalificacionSaveController extends AbstractVueController
{
    use UnidadCalificacionServiceTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        if ($request->attributes->has('id')) {
            // Vista para editar una Unidad de Calificación
            $component = new Component('unidad-calificacion-edit');
            $component->addProp(new Prop('unidad-calificacion-id', Prop::TYPE_NUMBER, $request->attributes->getInt('id')));
        } else {
            // Vista para crear una nueva Unidad de Calificación
            $component = new Component('unidad-calificacion-save');

            // Obtener valores por defecto si existen
            $defaultUnidad = $this->getUnidadCalificacionService()->getUnidadCalificacionDao()->getDefaultUnidadCalificacion();
            if ($defaultUnidad) {
                $component->addProp(new Prop('default-descripcion', Prop::TYPE_STRING, $defaultUnidad->getDescripcion()));
                $component->addProp(new Prop('default-valor', Prop::TYPE_NUMBER, $defaultUnidad->getValor()));
            }
        }

        $this->setComponent($component);
    }
}
