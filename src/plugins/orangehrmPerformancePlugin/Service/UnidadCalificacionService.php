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

namespace OrangeHRM\Performance\Service;

// use Exception;
// use OrangeHRM\Core\Traits\ORM\EntityManagerHelperTrait;
// use OrangeHRM\Entity\UnidadCalificacion;
// use OrangeHRM\ORM\Exception\TransactionException;
// use OrangeHRM\Performance\Dao\UnidadCalificacionDao;

class UnidadCalificacionService
{
//     use EntityManagerHelperTrait;

//     private ?UnidadCalificacionDao $unidadCalificacionDao = null;

//     /**
//      * @return UnidadCalificacionDao
//      */
//     public function getUnidadCalificacionDao(): UnidadCalificacionDao
//     {
//         if (!($this->unidadCalificacionDao instanceof UnidadCalificacionDao)) {
//             $this->unidadCalificacionDao = new UnidadCalificacionDao();
//         }
//         return $this->unidadCalificacionDao;
//     }

//     /**
//      * @param UnidadCalificacion $unidadCalificacion
//      * @return UnidadCalificacion
//      * @throws TransactionException
//      */
//     public function saveUnidadCalificacion(UnidadCalificacion $unidadCalificacion): UnidadCalificacion
//     {
//         $this->beginTransaction();
//         try {
//             $unidadCalificacion = $this->getUnidadCalificacionDao()->saveUnidadCalificacion($unidadCalificacion);
//             $this->commitTransaction();
//             return $unidadCalificacion;
//         } catch (Exception $e) {
//             $this->rollBackTransaction();
//             throw new TransactionException($e);
//         }
//     }

//     /**
//      * @param int $id
//      * @return UnidadCalificacion|null
//      */
//     public function getUnidadCalificacionById(int $id): ?UnidadCalificacion
//     {
//         return $this->getUnidadCalificacionDao()->getUnidadCalificacionById($id);
//     }

//     /**
//      * @param int $id
//      * @return bool
//      * @throws TransactionException
//      */
//     public function deleteUnidadCalificacion(int $id): bool
//     {
//         $this->beginTransaction();
//         try {
//             $result = $this->getUnidadCalificacionDao()->deleteUnidadCalificacion($id);
//             $this->commitTransaction();
//             return $result;
//         } catch (Exception $e) {
//             $this->rollBackTransaction();
//             throw new TransactionException($e);
//         }
//     }

//     /**
//      * @param array $ids
//      * @return bool
//      * @throws TransactionException
//      */
//     public function deleteMultipleUnidadCalificacion(array $ids): bool
//     {
//         $this->beginTransaction();
//         try {
//             foreach ($ids as $id) {
//                 $this->getUnidadCalificacionDao()->deleteUnidadCalificacion($id);
//             }
//             $this->commitTransaction();
//             return true;
//         } catch (Exception $e) {
//             $this->rollBackTransaction();
//             throw new TransactionException($e);
//         }
//     }

//     /**
//      * @param int $id
//      * @return bool
//      */
//     public function isUnidadCalificacionEditable(int $id): bool
//     {
//         return $this->getUnidadCalificacionDao()->isUnidadCalificacionEditable($id);
//     }


public function retornarMensaje(){
    echo("hola mundo");
}
}
