<?php

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\CartView;
use Raketa\BackendTestTask\Service\AddProductToCartService;

readonly class AddToCartController
{
    public function __construct(
        private CartView $cartView,
        private AddProductToCartService $addProductToCartService
    ) {
    }

    public function addToCart(RequestInterface $request): ResponseInterface //? Invalid naming. get() is not appropriate.
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);

        // $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

        // $cart = $this->cartManager->getCart();
        // $cart->addItem(new CartItem(
        //     Uuid::uuid4()->toString(),
        //     $product->getUuid(),
        //     $product->getPrice(),
        //     $rawRequest['quantity'],
        // ));

        $cart = $this->addProductToCartService->addProductToCart($rawRequest['productUuid'], $rawRequest['quantity']);

        $response = new JsonResponse();
        $response->getBody()->write(
            json_encode(
                [
                    'status' => 'success',
                    'cart' => $this->cartView->toArray($cart)
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
