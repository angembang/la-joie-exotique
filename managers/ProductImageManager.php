<?php

/**
 * Manages the retrieval and persistence of product image in the platform
 */
class ProductImageManager extends AbstractManager 
{
  /**
   * Creates a new product image and persists it in the database
   * 
   * @param int $productId The product identifier of the product image object to be created.
   * @param int $imageId The image identifier of the product image object to be created.
   * 
   * @return bool True if successfull, false if not.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function createProductImage(int $productId, int $imageId): bool 
  {
    try {
      // Prepare the SQL query to insert the product image into the database.
      $query = $this->db->prepare("INSERT INTO products_images 
      (product_id, image_id) 
      VALUES 
      (:product_id, :image_id)");

      // Bind parameters with their values.
      $parameters = [
        ":product_id" => $productId,
        ":image_id" => $imageId
      ];

      // Execute the query with parameters.
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return true;
      }
      return false;

    } catch(PDOException $e) {
      error_log("Failed to create a new product image: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to create a new product image");
    }
  }





  /**
   * Retrieves images by the product identifier
   * 
   * @param int $productId The unique identifier of the product
   * 
   * @return array|null The array of retreived images or null if no image is found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findProductImagesByProductId(int $productId): ?array 
  {
    try {
      // Prepare the SQL query to retrieve images by the product identifier.
      $query = $this->db->prepare("SELECT pi.*, i.* FROM products_images pi 
      JOIN images i 
      ON pi.image_id = i.id
      WHERE pi.product_id = :product_id");

      // Bind the parameter with its value.
      $parameter = [
        ":product_id" => $productId
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the product image data from the database.
      $productImagesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if product images data are found.
      if($productImagesData) {
        $productImages = [];

        // Loop through each product image in the array.
        foreach($productImagesData as $productImageData) {
          $productImage = new ProductImage(
            $productImageData["product_id"],
            $productImageData["product_image"]
          );
          $productImages[] = $productImage;
        }
        return $productImages;
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find the product images: " .$e->getMessage(), $e->getCode());
      throw new PDOException("to find the product images");
    }
  }

}