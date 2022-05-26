<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\TransformerAbstract;

/**
 * @OA\Info(
 *      version="2.0.0",
 *      title="Drop Delivery API",
 *      description="Drop Technologies"
 * )
 *
 * @OA\Schema(schema="SuccessResponse",
 *     @OA\Property(property="data", type="array", @OA\Items())
 * )
 *
 * @OA\Schema(schema="SuccessMessage",
 *     @OA\Property(property="data", type="object", @OA\Property(property="message", type="string"))
 * )
 *
 * @OA\Schema(schema="SuccessResponseObject",
 *     @OA\Property(property="data", type="object")
 * )
 *
 * @OA\Schema(schema="ErrorResponse",
 *      @OA\Property(property="errorMessage", type="string"),
 * )
 *
 * @OA\Schema(schema="DeleteResponse",
 *      @OA\Property(property="message", type="string"),
 * )
 *
 * @OA\Schema(schema="JWTToken", type="object",
 *      @OA\Property(property="token", type="string", description="Unique jwt Token"),
 *      @OA\Property(property="token_type", type="string", description="Header Key Token Type"),
 *      @OA\Property(property="time", type="integer", description="Expires Timestamp")
 * )
 *
 * @OA\Schema(schema="loginInputData",
 *      @OA\Property(property="email", type="string", description="Email Address", example="test@gmail.com"),
 *      @OA\Property(property="password", type="string", description="password", example="password"),
 * )
 * /**
 * @OA\Schema(
 *     schema="StandardPaginationMeta",
 *     @OA\Property(property="pagination", type="object", description="Standard Meta Description",
 *      @OA\Property(property="count",type="integer",format="int32",description="Number of items in count"),
 *      @OA\Property(property="current_page",type="integer",format="int32",description="Current page number"),
 *      @OA\Property(property="per_page",type="integer",format="int32",description="Number of items per page"),
 *      @OA\Property(property="total",type="integer",format="int32",description="Number if items in data"),
 *      @OA\Property(property="total_pages",type="integer",format="int32",description="Number of Pages"),
 *      @OA\Property(property="links", type="object", description="Automatic links for listing",
 *           @OA\Property(property="current",type="string",format="string",description="Current page links"),
 *           @OA\Property(property="first",type="string",format="string",description="Current page links"),
 *           @OA\Property(property="last",type="string",format="string",description="Current page links"),
 *           @OA\Property(property="next",type="string",format="string",description="Current page links")
 *      )
 *     )
 * )
 * @OA\Schema(
 *     schema="StandardTimestamp", description="standard Timestamp object",
 *       allOf={
 *          @OA\Schema(ref="#/components/schemas/StandardTimestampWithoutDeleted"),
 *          @OA\Schema(
 *              @OA\Property(property="deleted_at", type="object", description="deleted at", ref="#/components/schemas/dateFormate")
 *          )
 *      }
 * )
 *
 * @OA\Schema(
 *     schema="StandardTimestampWithoutDeleted", description="standard Timestamp object",
 *      @OA\Property(property="created_at", type="object", description="created at", ref="#/components/schemas/dateFormate"),
 *      @OA\Property(property="updated_at", type="object", description="updated at", ref="#/components/schemas/dateFormate")
 *     )
 * )
 * 
 * @OA\Schema(
 *  schema="dateFormate",
 *  @OA\Property(property="date", type="string", description="date", example="2021-10-22 02:12:09"),
 *  @OA\Property(property="timezone", type="string", description="timezone", example="America/Los_Angeles")
 * )
 * 
 * @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"asc","desc"}),  description="Sort Order")
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function returnJsonResponse($data, $statusCode = 200)
    {
        if (gettype($data) === 'array' && !array_key_exists('data', $data)) {
            $data = ['data' => $data];
        }

        return new JsonResponse($data, $statusCode);
    }

    public function abortJsonResponse($errors, $statusCode = 422)
    {
        $formattedErrors = [];
        if (!is_array($errors)) {
            $errors = [$errors];
        }
        foreach ($errors as $error) {
            if ($error instanceof \Exception) {
                $e = [
                    'message' => $error->getMessage(),
                    'code' => $error->getCode(),
                ];
                if (config('app.env') !== 'production') {
                    $e['line'] = $error->getLine();
                    $e['trace'] = $error->getTrace();
                }
                $formattedErrors[] = $e;
            } elseif (is_string($error)) {
                $e = [
                    'message' => $error,
                ];
                $formattedErrors[] = $e;
            } elseif (is_array($error)) {
                $formattedErrors[] = $error;
            }
        }

        return $this->returnJsonResponse(
            [
                'errors' => $formattedErrors,
                'statusCode' => $statusCode,
            ],
            $statusCode,
        );
    }

    public function item($item, TransformerAbstract $transformer, Closure $callback = null)
    {
        $resource = new Item($item, $transformer);
        if ($callback !== null) {
            call_user_func($callback, $resource);
        }

        return $this->buildResponse($resource);
    }

    private function buildResponse(ResourceInterface $resource)
    {
        $data = app('fractalManager')->createData($resource);

        return $this->returnJsonResponse($data->toArray());
    }

    public function collection($items, TransformerAbstract $transformer, Closure $callback = null)
    {
        $resources = new Collection($items, $transformer);
        if ($callback !== null) {
            call_user_func($callback, $resources);
        }

        return $this->buildResponse($resources);
    }

    public function paginateCollection($items, TransformerAbstract $transformer, Closure $callback = null)
    {
        $perPage = app('request')->query('perPage', 10);
        $currentPage = app('request')->query('page', 1) ?? 1;
        $paginator = $items;

        if (method_exists($items, 'paginate') === true) {
            $paginator = $items->paginate($perPage);
            $paginator->appends(app('request')->query());
        } elseif ($items instanceof \Illuminate\Support\Collection) {
            $paginator = new LengthAwarePaginator($items->forPage($currentPage, $perPage), $items->count(), app('request')->query('perPage', 10));
        }

        $resource = new Collection($paginator, $transformer);
        if ($callback !== null) {
            call_user_func($callback, $resource);
        }
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->buildResponse($resource);
    }
}
