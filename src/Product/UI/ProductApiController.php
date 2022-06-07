<?php

declare(strict_types=1);

namespace App\Product\UI;

use App\Product\Application\Dto\Product;
use App\Product\Application\Exception\ProductNotFoundException;
use App\Product\Application\Exception\ProductWithSameTitleExistsException;
use App\Product\Application\Query\ProductQuery;
use App\Product\Application\UseCase\CreateProduct;
use App\Product\Application\UseCase\CreateProductModel;
use App\Product\Application\UseCase\PatchProduct;
use App\Product\Application\UseCase\PatchProductModel;
use App\Product\Application\UseCase\RemoveProduct;
use App\Product\Application\UseCase\RemoveProductModel;
use App\Shared\Infrastructure\Exception\AppException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Tag(name: "Product")]
class ProductApiController extends AbstractController
{
    public function __construct(
        private ProductQuery $productQuery,
        private CreateProduct $createProduct,
        private PatchProduct $patchProduct,
        private RemoveProduct $removeProduct
    ) {
    }

    /**
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=true,
     *     description="Page",
     *     example=1
     * )
     * @OA\Response(
     *     response=200,
     *     description="Collection of Products",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class))
     *     )
     * )
     */
    #[Route("/api/products", name: "api_products", methods: ["GET"])]
    public function getList(Request $request): JsonResponse
    {
        return $this->json(
            $this->productQuery->getPaginatedList(
                $request->query->getInt('page', 1)
            )
        );
    }

    /**
     * @OA\RequestBody(@Model(type=CreateProductModel::class))
     * @OA\Response(
     *     response=200,
     *     description="Create new product",
     *     @OA\JsonContent(
     *        type="string",
     *        example="Product has been added"
     *     )
     * )
     */
    #[Route(path: "/api/product", name: "api_products_create", methods: ["POST"])]
    public function create(Request $request): JsonResponse
    {
        try {
            $this->createProduct->execute(
                CreateProductModel::fromJson($request->getContent())
            );
        } catch (ProductWithSameTitleExistsException $e) {
            throw new AppException($e->getMessage(), Response::HTTP_CONFLICT);
        }

        return $this->json('Product has been added');
    }

    /**
     * @OA\RequestBody(@Model(type=CreateProductModel::class))
     * @OA\Response(
     *     response=200,
     *     description="Patch one or few fields in product",
     *     @OA\JsonContent(
     *        type="string",
     *        example="Product has been updated"
     *     )
     * )
     */
    #[Route(path: "/api/product/{id}", name: "api_products_patch", methods: ["PATCH"])]
    public function patch(int $id, Request $request): JsonResponse
    {
        try {
            $this->patchProduct->execute(
                PatchProductModel::fromJson($id, $request->getContent())
            );
        } catch (ProductNotFoundException $e) {
            return $this->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (ProductWithSameTitleExistsException $e) {
            return $this->json($e->getMessage(), Response::HTTP_CONFLICT);
        }

        return $this->json("Product has been updated");
    }

    /**
     * @OA\RequestBody(@Model(type=CreateProductModel::class))
     * @OA\Response(
     *     response=200,
     *     description="Patch one or few fields in product",
     *     @OA\JsonContent(
     *        type="string",
     *        example="Product has been removed"
     *     )
     * )
     */
    #[Route(path: "/api/product/{id}", name: "api_products_delete", methods: ["DELETE"])]
    public function remove(int $id): JsonResponse
    {
        $this->removeProduct->execute(new RemoveProductModel($id));

        return $this->json("Product has been removed");
    }
}