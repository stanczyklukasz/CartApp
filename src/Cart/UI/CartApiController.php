<?php

declare(strict_types=1);

namespace App\Cart\UI;

use App\Cart\Application\UseCase\CreateCart;
use OpenApi\Annotations as OA;
use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


#[Tag(name: "Cart")]
class CartApiController extends AbstractController
{
    public function __construct(
        private CreateCart $createCart
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
}