<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Service;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Domain\CartItem;

use Ramsey\Uuid\Uuid;

class AddProductToCartService
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartManager $cartManager,
    ) {
    }

    public function addProductToCart(string $productUuid, int $productQuantity): Cart
    {
        // Я не старался что-то выдумывать, просто перенёс логику, которой не должно было быть в контроллере, в отдельный класс
        $product = $this->productRepository->getByUuid($productUuid);

        $cart = $this->cartManager->getCart();
        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $productQuantity,
        ));

        return $cart;
    }
}