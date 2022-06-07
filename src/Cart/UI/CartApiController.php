<?php

declare(strict_types=1);

namespace App\Cart\UI;

use App\Cart\Application\Exception\CartNotFoundException;
use App\Cart\Application\Exception\ProductNotAddedException;
use App\Cart\Application\Exception\ProductNotFoundException;
use App\Cart\Application\UseCase\AddProductToCart;
use App\Cart\Application\UseCase\AddProductToCartModel;
use App\Cart\Application\UseCase\CreateCart;
use App\Shared\Infrastructure\Exception\AppException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Tag(name: "Cart")]
class CartApiController extends AbstractController
{
    public function __construct(
        private CreateCart $createCart,
        private AddProductToCart $addProductToCart
    ) {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create a cart and return its uuid",
     *     @OA\JsonContent(
     *        type="string",
     *        example="1ee9aa1b-6510-4105-92b9-7171bb2f3089"
     *     )
     * )
     */
    #[Route("/api/cart", name: "api_cart_create", methods: ["POST"])]
    public function getList(): JsonResponse
    {
        return $this->json(
            $this->createCart->execute()
        );
    }

    /**
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="productId", type="number", example=1)
     *     ),
     * )
     * @OA\Response(
     *     response=200,
     *     description="Add product to the cart",
     *     @OA\JsonContent(
     *        type="string",
     *        example="Product has been added to the cart"
     *     )
     * )
     */
    #[Route("/api/cart/{uuid}/add-product", name: "api_cart_add_product", methods: ["POST"])]
    public function addProduct(string $uuid, Request $request): JsonResponse
    {
        try {
            $this->addProductToCart->execute(
                AddProductToCartModel::fromJson(
                    $uuid,
                    $request->getContent()
                )
            );
        } catch (CartNotFoundException | ProductNotFoundException $e) {
            throw new AppException($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (ProductNotAddedException $e) {
            throw new AppException($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json("Product has been added");
    }
}