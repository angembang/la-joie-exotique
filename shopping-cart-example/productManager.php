<?php

class ProductManager extends AbstractManager
{
    public function findAll() : array
    {
        $query = $this->db->prepare('SELECT * FROM products');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $products = [];

        foreach($result as $item)
        {
            $product = new Product($item["name"], $item["description"], $item["picture_url"], $item["picture_alt"], $item["price"]);
            $product->setId($item["id"]);
            $products[] = $product;
        }
        return $products;
    }

    public function findOne(int $id) : ? Product
    {
        $query = $this->db->prepare('SELECT * FROM products WHERE id=:id');

        $parameters = [
            "id" => $id
        ];

        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result)
        {
            $product = new Product($result["name"], $result["description"], $result["picture_url"], $result["picture_alt"], $result["price"]);
            $product->setId($result["id"]);
            return $product;


        }
        return null;
    }
}