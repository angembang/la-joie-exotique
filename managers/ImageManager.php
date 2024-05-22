<?php

/**
 * Manages the retrieval and persistence of Image object in the platform
 */
class ImageManager extends AbstractManager
{
  /**
   * Creates a new Image and persists it in the database
   * 
   * @param Image $image The image object to be created.
   * 
   * @return Image The created image with the assigned identifier.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function createImage(Image $image): Image 
  {
    try {
      // Prepare the SQL query to insert a new image into the database.
      $query = $this->db->prepare("INSERT INTO images 
      (name, file_name, alt) 
      VALUES 
      (:name, :file_name, :alt)");

      // Bind parameters with their values.
      $parameters = [
        ":name" => $image->getName(),
        ":file_name" => $image->getFileName(),
        ":alt" => $image->getAlt()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last insert identifier.
      $imageId = $this->db->lastInsertId();

      // Set the identifer for the created image.
      $image->setId($imageId);

      // Return the created image.
      return $image;

    } catch(PDOException $e) {
      error_log("Failed to create a new image: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to create a new image");
    }
  }


  /**
   * Retrieves an image by its unique identifier.
   * 
   * @param int $imageId The unique identifier of the image.
   * 
   * @return Image|null The retrieved image or null if not found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findImageById(int $imageId): ?Image 
  {
    try {
      // Prepare the query to retrieve an image by its unique identifier.
      $query = $this->db->prepare("SELECT * FROM images WHERE id = :id");

      // Bind the parameter with its value.
      $parameter = [
        ":id" => $imageId
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the image data from the database.
      $imageData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if the image is found.
      if($imageData) {
        // Instantiante a new image with retrieved data
        $image = new Image(
          $imageData["id"],
          $imageData["name"],
          $imageData["file_name"],
          $imageData["alt"]
        );
        return $image;
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find the image: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find the image");
    }
  }


  /**
   * Updates an image
   * 
   * @param Image $image The image to be updated.
   * 
   * @return Image|null The updated imege or nul if not updated.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function updateImage(Image $image): ?Image 
  {
    try {
      // Prepare the SQL query to update an image in the database.
      $query = $this->db->prepare("UPDATE images SET 
      name = :name,
      file_name = :file_name,
      alt = :alt 
      WHERE id = :id");

      // Bind parameters with their values.
      $parameters = [
        ":id" => $image->getId(),
        ":name" => $image->getName(),
        ":file_name" => $image->getFileName(),
        ":alt" => $image->getAlt()
      ];

      // Execute the query with parameters.
      $success = $query->execute($parameters);

      // check if success
      if($success) {
        return $image;
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to update the image: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to update the image");
    }
  }


  /**
   * Deletes an image in the database
   * 
   * @param int $imageId The unique identifier of the image to be deleted.
   * 
   * @return bool True if successful, false if not.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function deleteImage(int $imageId): bool 
  {
    try {
      // Prepare the SQL query to delete an image in the database.
      $query = $this->db->prepare("DELETE FROM images WHERE id = :id");

      // Bind the parameter with its value.
      $parameter = [
        ":id" => $imageId
      ];

      // Execute the query with the parameter.
      $success = $query->execute($parameter);

      // Check if success
      if($success) {
        return true;
      }
      return false;

    } catch(PDOException $e) {
      error_log("Failed to delete the image: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to delete the image");
    }
  }
}