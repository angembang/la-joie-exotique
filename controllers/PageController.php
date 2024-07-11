<?php

/**
 * 
 */
class PageController extends AbstractController
{
  /** 
   *
   */
  public function home(): void 
  {
    try {
      $categoryManager = new CategoryManager();
        $productManager = new ProductManager();
        $imageManager = new ImageManager();

        $categories = $categoryManager->findAll();
        $productsCategoryArray = [];

        foreach ($categories as $category) {
          $categoryId = $category->getId();
          $productsCat = $productManager->findProductsByCategoryId($categoryId);

          // Vérifier que $productsCat est bien un tableau
          if (!is_array($productsCat)) {
            $productsCat = [];
          }

          // Limiter le nombre de produits à 2
          $productsCat = array_slice($productsCat, 0, 2);

          foreach ($productsCat as $productItem) {
            $imageIds = [
              $productItem->getImage1Id(),
              $productItem->getImage2Id(),
              $productItem->getImage3Id(),
              $productItem->getImage4Id()
            ];

              $images = array_filter(array_map(function($imageId) use ($imageManager) {
              return $imageId ? $imageManager->findImageById($imageId) : null;
            }, $imageIds));

            $productItem->images = $images;
          }
        
        $productsCategoryArray[] = [
          "category" => $category,
          "products" => $productsCat
        ];
      }

      $this->render("home", [
        "productsCategoryArray" => $productsCategoryArray
      ]);
  } catch(PDOException $e) {
      error_log("Failed to find products: ".$e->getMessage() .$e->getCode());
      throw new PDOException("Failed to find products");
   }
  }


  /*
   *
   */
  public function cleanUrl() {
    header("Location: /index.php?route=clear-cart");
    exit();
  }
  

  /*
   *
   */
  public function adminHome() {
    $this->render("adminHome", []);
  }


  /**
   * 
   */
  public function about(): void 
  {
    $this->render("about", []);
  }


  /**
   * 
   */
  public function legalnotice(): void 
  {
    $this->render("legalNotice", []);
  }


  /**
   * 
   */
  public function privacypolicy(): void 
  {
    $this->render("privacyPolicy", []);
  }


  /**
   * 
   */
  public function contact() {
    $this->render("contact", []);
  }


  /**
   * 
   */
  public function error(): void 
  {
    require "templates/error.phtml";
  }
}