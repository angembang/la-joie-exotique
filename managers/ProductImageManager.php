<?php

/**
 * Manages the retrieval and persistence of product image in the platform
 */
class ProductImageManager extends AbstractManager 
{
  /**
   * Creates a new product image and persists it in the database
   * 
   * @param ProductImage $productImage The product image object to be created.
   * 
   * @return ProductImage The created product image with the assigned identifier.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function createProductImage(ProductImage $productImage): ProductImage 
  {
    try {
      // Prepare the SQL query to insert the product image into the database.
      $query = $this->db->prepare("INSERT INTO products_images 
      (product_id, image_id) 
      VALUES 
      (:product_id, :image_id)");

      // Bind parameters with their values.
      $parameters = [
        ":product_id" => $productImage->getProductId(),
        ":image_id" => $productImage->getImageId()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last insert identifier
      $productImageId = $this->db->lastInsertId();

      // Set the identifier for the created product image
      $productImage->setId($productImageId);

      // Return the created product image
      return $productImage;

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
  public function findImagesByProductId(int $productId): ?array 
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

      // Evecute the query with the parameter.
      $query->execute($parameter);

      // Fetch the product image data from the database.
      $imagesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if product images data are found.
      if($imagesData) {
        $images = [];

        // Loop through each product image in the array.
        foreach($imagesData as $imageData) {
          $image = new Image(
            $imageData["id"],
            $imageData["name"],
            $imageData["file_name"],
            $imageData["alt"]
          );
          $images[] = $image;
        }
        return $images;
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find the product images: " .$e->getMessage(), $e->getCode());
      throw new PDOException("to find the product images");
    }
  }

}