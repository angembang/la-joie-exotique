<?php

class ShopController extends AbstractController
{
    public function shop(): void
    {
        $pm = new ProductManager;
        $products = $pm->findAll();

        $this->render("shop/shop.html.twig", [
            "products" => $products
        ]);
    }

    public function addToCart(): void
    {
        $id = intval($_POST['product_id']);
        $pm = new ProductManager;
        $product = $pm->findOne($id);

        $_SESSION["cart"] = [];
        $_SESSION["cart"][]= $product->toArray();

        $this->renderJson($_SESSION["cart"]);
    }
    public function cart(): void
    {
        $this->render("shop/cart.html.twig", [
            "title" => "Panier"
        ]);
    }
}