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

namespace OrangeHRM\Performance\Dao;

use OrangeHRM\Core\Dao\BaseDao;
use OrangeHRM\Entity\UnidadCalificacion;
use OrangeHRM\Performance\Dto\UnidadCalificacionSearchFilterParams;
use OrangeHRM\ORM\QueryBuilderWrapper;

class UnidadCalificacionDao extends BaseDao
{
    /**
     * @param UnidadCalificacion $unidadCalificacion
     * @return UnidadCalificacion
     */
    public function saveUnidadCalificacion(UnidadCalificacion $unidadCalificacion): UnidadCalificacion
    {
        $this->persist($unidadCalificacion);
        return $unidadCalificacion;
    }

    /**
     * @param int $id
     * @return UnidadCalificacion|null
     */
    public function getUnidadCalificacionById(int $id): ?UnidadCalificacion
    {
        return $this->getRepository(UnidadCalificacion::class)->findOneBy(['id' => $id, 'estado' => true]);
    }

    /**
     * @param UnidadCalificacionSearchFilterParams $filterParams
     * @return UnidadCalificacion[]
     */
    public function getUnidadCalificacionList(UnidadCalificacionSearchFilterParams $filterParams): array
    {
        $qb = $this->getUnidadCalificacionQueryBuilderWrapper($filterParams)->getQueryBuilder();
        return $qb->getQuery()->execute();
    }

    /**
     * @param UnidadCalificacionSearchFilterParams $filterParams
     * @return int
     */
    public function getUnidadCalificacionCount(UnidadCalificacionSearchFilterParams $filterParams): int
    {
        $qb = $this->getUnidadCalificacionQueryBuilderWrapper($filterParams)->getQueryBuilder();
        return $this->getPaginator($qb)->count();
    }

    /**
     * @param UnidadCalificacionSearchFilterParams $filterParams
     * @return QueryBuilderWrapper
     */
    private function getUnidadCalificacionQueryBuilderWrapper(UnidadCalificacionSearchFilterParams $filterParams): QueryBuilderWrapper
    {
        $q = $this->createQueryBuilder(UnidadCalificacion::class, 'unidadCalificacion');
        $q->andWhere($q->expr()->eq('unidadCalificacion.estado', ':estado'))
            ->setParameter('estado', $filterParams->getEstado() ?? true);

        if (!is_null($filterParams->getDescripcion())) {
            $q->andWhere($q->expr()->like('unidadCalificacion.descripcion', ':descripcion'))
                ->setParameter('descripcion', '%' . $filterParams->getDescripcion() . '%');
        }

        $this->setSortingAndPaginationParams($q, $filterParams);
        $q->addOrderBy('unidadCalificacion.descripcion');

        return $this->getQueryBuilderWrapper($q);
    }

    /**
     * Elimina (desactiva) las unidades de calificación por sus IDs.
     *
     * @param int[] $ids
     * @return bool
     */
    public function deleteUnidadCalificacion(array $ids): bool
    {
        $q = $this->createQueryBuilder(UnidadCalificacion::class, 'unidadCalificacion');
        $q->update()
            ->set('unidadCalificacion.estado', ':estado')
            ->setParameter('estado', false)
            ->andWhere($q->expr()->in('unidadCalificacion.id', ':ids'))
            ->setParameter('ids', $ids);

        return $q->getQuery()->execute() > 0;
    }


    /**
     * @param int $id
     * @return bool
     */
    public function isUnidadCalificacionEditable(int $id): bool
    {
        $q = $this->createQueryBuilder(UnidadCalificacion::class, 'unidadCalificacion');
        $q->andWhere($q->expr()->eq('unidadCalificacion.id', ':id'))
            ->setParameter('id', $id)
            ->andWhere($q->expr()->eq('unidadCalificacion.estado', ':estado'))
            ->setParameter('estado', true);

        return $this->getPaginator($q)->count() > 0;
    }


    /**
     * @return UnidadCalificacion|null
     */
    public function getDefaultUnidadCalificacion(): ?UnidadCalificacion
    {
        // Buscar la unidad de calificación con el valor más alto como predeterminada
        return $this->getRepository(UnidadCalificacion::class)->findOneBy(
            ['estado' => true],
            ['valor' => 'DESC']
        );
    }

    /**
     * @param int[] $ids
     * @return int[]
     */
    public function getExistingUnidadCalificacionIds(array $ids): array
    {
        $qb = $this->createQueryBuilder(UnidadCalificacion::class, 'unidadCalificacion');
        $qb->select('unidadCalificacion.id')
            ->andWhere($qb->expr()->in('unidadCalificacion.id', ':ids'))
            ->andWhere('unidadCalificacion.estado = true')
            ->setParameter('ids', $ids);

        return $qb->getQuery()->getSingleColumnResult();
    }

    /**
     * Verifica si una Unidad de Calificación es eliminable
     *
     * @param int $unidadCalificacionId
     * @return bool
     */
    public function isUnidadCalificacionDeletable(int $unidadCalificacionId): bool
    {
        $qb = $this->createQueryBuilder(UnidadCalificacion::class, 'unidadCalificacion');

        // Verificamos si la unidad de calificación está asociada a evaluaciones activas
        $qb->select('COUNT(evaluacion.id)')
            ->leftJoin('unidadCalificacion.evaluaciones', 'evaluacion')
            ->andWhere($qb->expr()->eq('unidadCalificacion.id', ':unidadCalificacionId'))
            ->andWhere($qb->expr()->isNull('evaluacion.deletedAt'))
            ->setParameter('unidadCalificacionId', $unidadCalificacionId);

        $count = $qb->getQuery()->getSingleScalarResult();

        // Si no hay evaluaciones activas, es eliminable
        return $count == 0;
    }
}
