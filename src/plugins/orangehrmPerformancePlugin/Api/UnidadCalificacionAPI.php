<?php

namespace OrangeHRM\Performance\Api;

use OrangeHRM\Core\Api\CommonParams;
use OrangeHRM\Core\Api\V2\CrudEndpoint;
use OrangeHRM\Core\Api\V2\Endpoint;
use OrangeHRM\Core\Api\V2\EndpointCollectionResult;
use OrangeHRM\Core\Api\V2\EndpointResourceResult;
use OrangeHRM\Core\Api\V2\EndpointResult;
use OrangeHRM\Performance\Dto\UnidadCalificacionSearchFilterParams;
use OrangeHRM\Core\Api\V2\Model\ArrayModel;
use OrangeHRM\Core\Api\V2\ParameterBag;
use OrangeHRM\Core\Api\V2\RequestParams;
use OrangeHRM\Core\Api\V2\Validator\ParamRule;
use OrangeHRM\Core\Api\V2\Validator\ParamRuleCollection;
use OrangeHRM\Core\Api\V2\Validator\Rule;
use OrangeHRM\Core\Api\V2\Validator\Rules;
use OrangeHRM\Entity\UnidadCalificacion;
use OrangeHRM\Performance\Api\Model\UnidadCalificacionModel;
use OrangeHRM\Performance\Traits\Service\UnidadCalificacionServiceTrait;

class UnidadCalificacionAPI extends Endpoint implements CrudEndpoint
{
    use UnidadCalificacionServiceTrait;

    public const PARAMETER_DESCRIPCION = 'descripcion';
    public const PARAMETER_VALOR = 'valor';
    public const PARAMETER_ESTADO = 'estado';

    /**
     * @OA\Get(
     *     path="/api/v2/performance/unidad-calificacion/{id}",
     *     tags={"Performance/Unidad Calificacion"},
     *     summary="Get a Unidad Calificacion",
     *     @OA\PathParameter(name="id", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", ref="#/components/responses/RecordNotFound")
     * )
     */
    public function getOne(): EndpointResult
    {
        $id = $this->getRequestParams()->getInt(RequestParams::PARAM_TYPE_ATTRIBUTE, CommonParams::PARAMETER_ID);
        $unidadCalificacion = $this->getUnidadCalificacionService()->getUnidadCalificacionDao()->getUnidadCalificacionById($id);
        $this->throwRecordNotFoundExceptionIfNotExist($unidadCalificacion, UnidadCalificacion::class);
        return new EndpointResourceResult(UnidadCalificacionModel::class, $unidadCalificacion);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(CommonParams::PARAMETER_ID, new Rule(Rules::POSITIVE))
        );
    }

 /**
 * @OA\Get(
 *     path="/api/v2/performance/unidad-calificacion",
 *     tags={"Performance/Unidad Calificación"},
 *     summary="List All Unidad Calificación",
 *     operationId="list-all-unidad-calificacion",
 *     @OA\Parameter(
 *         name="descripcion",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="sortField",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", enum=UnidadCalificacionSearchFilterParams::ALLOWED_SORT_FIELDS)
 *     ),
 *     @OA\Parameter(ref="#/components/parameters/sortOrder"),
 *     @OA\Parameter(ref="#/components/parameters/limit"),
 *     @OA\Parameter(ref="#/components/parameters/offset"),
 *     @OA\Response(
 *         response="200",
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Performance-UnidadCalificacionModel")
 *             ),
 *             @OA\Property(property="meta",
 *                 type="object",
 *                 @OA\Property(property="total", type="integer")
 *             )
 *         )
 *     )
 * )
 * @inheritDoc
 */
public function getAll(): EndpointResult
{
    // Crear una instancia de los parámetros de filtro
    $unidadCalificacionSearchFilterParams = new UnidadCalificacionSearchFilterParams();
    
    // Configurar los parámetros de paginación y ordenamiento
    $this->setSortingAndPaginationParams($unidadCalificacionSearchFilterParams);

    // Filtrar por descripción si se proporciona
    $unidadCalificacionSearchFilterParams->setDescripcion(
        $this->getRequestParams()->getStringOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_DESCRIPCION
        )
    );

    // Obtener la lista de unidades de calificación y el conteo total
    $unidades = $this->getUnidadCalificacionService()->getUnidadCalificacionDao()->getUnidadCalificacionList($unidadCalificacionSearchFilterParams);
    $count = $this->getUnidadCalificacionService()->getUnidadCalificacionDao()->getUnidadCalificacionCount($unidadCalificacionSearchFilterParams);

    // Retornar el resultado en el formato esperado
    return new EndpointCollectionResult(
        UnidadCalificacionModel::class,
        $unidades,
        new ParameterBag([CommonParams::PARAMETER_TOTAL => $count])
    );
}


    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection();
    }

    /**
     * @OA\Post(
     *     path="/api/v2/performance/unidad-calificacion",
     *     tags={"Performance/Unidad Calificacion"},
     *     summary="Create a Unidad Calificacion",
     *     @OA\RequestBody(@OA\JsonContent(
     *         required={"descripcion", "valor"},
     *         @OA\Property(property="descripcion", type="string"),
     *         @OA\Property(property="valor", type="integer"),
     *         @OA\Property(property="estado", type="boolean")
     *     )),
     *     @OA\Response(response="200", description="Success")
     * )
     */
    public function create(): EndpointResult
    {
        $unidadCalificacion = new UnidadCalificacion();
        $this->setUnidadCalificacion($unidadCalificacion);
        $this->getUnidadCalificacionService()->saveUnidadCalificacion($unidadCalificacion);
        return new EndpointResourceResult(UnidadCalificacionModel::class, $unidadCalificacion);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(self::PARAMETER_DESCRIPCION, new Rule(Rules::STRING_TYPE)),
            new ParamRule(self::PARAMETER_VALOR, new Rule(Rules::INT_TYPE)),
            new ParamRule(self::PARAMETER_ESTADO, new Rule(Rules::BOOL_TYPE))
        );
    }

    /**
     * @OA\Put(
     *     path="/api/v2/performance/unidad-calificacion/{id}",
     *     tags={"Performance/Unidad Calificacion"},
     *     summary="Update a Unidad Calificacion",
     *     @OA\Response(response="200", description="Success")
     * )
     */
    public function update(): EndpointResult
    {
        $id = $this->getRequestParams()->getInt(RequestParams::PARAM_TYPE_ATTRIBUTE, CommonParams::PARAMETER_ID);
        $unidadCalificacion = $this->getUnidadCalificacionService()->getUnidadCalificacionDao()->getUnidadCalificacionById($id);
        $this->throwRecordNotFoundExceptionIfNotExist($unidadCalificacion, UnidadCalificacion::class);
        $this->setUnidadCalificacion($unidadCalificacion);
        $this->getUnidadCalificacionService()->saveUnidadCalificacion($unidadCalificacion);
        return new EndpointResourceResult(UnidadCalificacionModel::class, $unidadCalificacion);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        return $this->getValidationRuleForCreate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/performance/unidad-calificacion",
     *     tags={"Performance/Unidad Calificacion"},
     *     summary="Delete Unidad Calificaciones",
     *     @OA\Response(response="200", description="Success")
     * )
     */
    /**
     * @inheritDoc
     */
    public function delete(): EndpointResult
    {
        // Obtener los IDs de las unidades de calificación desde el request
        $ids = $this->getUnidadCalificacionService()->getUnidadCalificacionDao()->getExistingUnidadCalificacionIds(
            $this->getRequestParams()->getArray(RequestParams::PARAM_TYPE_BODY, CommonParams::PARAMETER_IDS)
        );

        // Lanza una excepción si no se encuentran registros con los IDs proporcionados
        $this->throwRecordNotFoundExceptionIfEmptyIds($ids);

        // Llama al DAO para eliminar las unidades de calificación
        $this->getUnidadCalificacionService()->getUnidadCalificacionDao()->deleteUnidadCalificacion($ids);

        // Retorna los IDs eliminados como resultado
        return new EndpointResourceResult(ArrayModel::class, $ids);
    }


    /**
     * @inheritDoc
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(CommonParams::PARAMETER_IDS, new Rule(Rules::INT_ARRAY))
        );
    }

    /**
     * Método para mapear los datos del request a la entidad UnidadCalificacion.
     *
     * @param UnidadCalificacion $unidadCalificacion
     */
    private function setUnidadCalificacion(UnidadCalificacion $unidadCalificacion): void
    {
        $unidadCalificacion->setDescripcion(
            $this->getRequestParams()->getString(RequestParams::PARAM_TYPE_BODY, self::PARAMETER_DESCRIPCION)
        );

        $unidadCalificacion->setValor(
            $this->getRequestParams()->getInt(RequestParams::PARAM_TYPE_BODY, self::PARAMETER_VALOR)
        );

        $unidadCalificacion->setEstado(
            $this->getRequestParams()->getBooleanOrNull(RequestParams::PARAM_TYPE_BODY, self::PARAMETER_ESTADO)
        );
    }
}
