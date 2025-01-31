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

use OrangeHRM\Performance\Service\KpiService;
use OrangeHRM\Performance\Service\PerformanceTrackerLogService;
use OrangeHRM\Performance\Service\PerformanceTrackerService;
use OrangeHRM\Performance\Service\PerformanceReviewService;
use OrangeHRM\Performance\Service\UnidadCalificacionService;
use OrangeHRM\Core\Traits\ServiceContainerTrait;
use OrangeHRM\Framework\PluginConfigurationInterface;
use OrangeHRM\Framework\Http\Request;
use OrangeHRM\Framework\Services;

class PerformancePluginConfiguration implements PluginConfigurationInterface
{
    use ServiceContainerTrait;

    /**
     * @inheritDoc
     */
    public function initialize(Request $request): void
    {
        // Registrar el servicio de KPI
        $this->getContainer()->register(
            Services::KPI_SERVICE,
            KpiService::class
        );

        // Registrar el servicio de Performance Tracker
        $this->getContainer()->register(
            Services::PERFORMANCE_TRACKER_SERVICE,
            PerformanceTrackerService::class
        );

        // Registrar el servicio de Performance Review
        $this->getContainer()->register(
            Services::PERFORMANCE_REVIEW_SERVICE,
            PerformanceReviewService::class
        );

        // Registrar el servicio de Performance Tracker Log
        $this->getContainer()->register(
            Services::PERFORMANCE_TRACKER_LOG_SERVICE,
            PerformanceTrackerLogService::class
        );

        // Registrar el nuevo servicio de UnidadCalificacion
        $this->getContainer()->register(
            Services::UNIDAD_CALIFICACION_SERVICE,
            UnidadCalificacionService::class
        );
    }
}
