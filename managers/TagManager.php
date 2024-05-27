<?php

/**
 * Manages the retrieval and persistence of Tag object in the platform
 */
class TagManager extends AbstractManager
{
  /**
   * Creates a new tag and persists it in the database
   * 
   * @param Tag $tag The tag object to be created.
   * 
   * @return Tag The created tag object with the assigned identifier.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createTag(Tag $tag): Tag 
  {
    try {
      // Prepare the SQL query to insert a new tag into the database
      $query = $this->db->prepare("INSERT INTO tags (id, category_id, name, description) VALUES 
      (:id, :category_id, :name, :description)");

      // Bind parameters with their values
      $parameters = [
        ":id" => $tag->getId(),
        ":category_id" => $tag->getCategoryId(),
        ":name" => $tag->getName(),
        ":description" => $tag->getDescription()
      ];

      // Execute the query with parameters
      $query->execute($parameters);

      // Retrieves the last inserted identifier
      $tagId = $this->db->lastInsertId();

      // Set the identifier for the created tag
      $tag->setId($tagId);

      // Return the created tag
      return $tag;
    
    } catch(PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create a new tag: " .$e->getMessage(), $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create a new tag");
    }
  }


  /**
   * Retrieves a tag by its unique identifier
   * 
   * @param int $tagId The unique identifier of the tag.
   * 
   * @return Tag|null The retrieved tag, or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findTagById(int $tagId): ?Tag
  {
    try {
      // Prepare the SQL query to retrieve the tag by its unique identifier
      $query = $this->db->prepare("SELECT * FROM tags WHERE id = :id");

      // Bind the parameter with its value
      $parameter = [
        ":id" => $tagId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the category data from the database
      $tagData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if category data is found
      if($tagData) {
       return $this->hydrateTag($tagData);
      } 
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find the tag: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find the tag");
    }
  }


  /**
   * Retrieves a tag by its category identifier
   * 
   * @param int $categoryId The category identifier of the tag.
   * 
   * @return array|null An array The retrieved tag, or null if no tag is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findTagsByCategoryId(int $categoryId): ?array
  {
    try {
      // Prepare the SQL query to retrieve tags by their category identifier
      $query = $this->db->prepare("SELECT tags.*, categories.* 
      FROM tags 
      JOIN Categories 
      ON category_id = categories.id 
      WHERE category_id = :category_id");

      // Bind the parameter with its value
      $parameter = [
        ":category_id" => $categoryId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch tags data from the database
      $tagsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if tags data are found
      if($tagsData) {
        return $this->hydrateTags($tagsData);
      }
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find the tag: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find the tag");
    }
  }


  /**
   * Retrieves a tag by its name
   * 
   * @param string $tagName The name of the tag.
   * 
   * @return Tag|null The retrieved tag, or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findTagByName(string $tagName): ?Tag
  {
    try {
      // Prepare the SQL query to retrieve the tag by its name.
      $query = $this->db->prepare("SELECT * FROM tags WHERE name = :name");

      // Bind the parameter with its value
      $parameter = [
        ":name" => $tagName
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the category data from the database
      $tagData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if category data is found
      if($tagData) {
        return $this->hydrateTag($tagData);
      }
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find the tag: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find the tag");
    }
  }


  /**
   * Retrieves all tags
   *
   * @return array tag|null The array of tag, or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all tags into the database
      $query = $this->db->prepare("SELECT * FROM tags");

      // Execute the query
      $query->execute();

      // Fetch tags data from the database
      $tagsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if tags data is not empty
      if($tagsData) {
        $tags = [];
        return $this->hydrateTags($tagsData);
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find tags: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find tags");
    }  
  }


  /**
   * update a tag in the database
   * 
   * @param Tag $tag The tag to be updated.
   * 
   * @return Tag|null The tag updated, or null if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updateTag(Tag $tag): ?Tag 
  {
    try {
      // Prepare the SQL query to update the Tag
      $query = $this->db->prepare("UPDATE tags SET
      category_id = :category_id,
      name = :name,
      description = :description 
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $tag->getId(),
        ":category_id" => $tag->getCategoryId(),
        ":name" => $tag->getName(),
        ":description" =>$tag->getDescription()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $tag;
      }
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the tag: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to update the tag");
    }
  }


  /**
   * Deletes a tag from the database
   * 
   * @param int $tagId The unique identifier of the tag to be deleted.
   * 
   * @return bool True if successful, false if not.
   */
  public function deleteTagById(int $tagId): bool 
  {
    try {
      // Prepare the SQL query to delete a tag by its unique identifier
      $query = $this->db->prepare("DELETE FROM tags WHERE id = :id");

      // Bind the parameter with its value
      $parameter = [
        ":id" => $tagId
      ];

      // Execute the query with the parameter.
      $success = $query->execute($parameter);

      // Check if success
      if($success) {
        return true;
      }
      return false;
    
    } catch(PDOException $e) {
      error_log("Failed to delete the tag: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to delete the tag");
    }
  }


  /**
     * Helper method to hydrate Tag object from data.
     * 
     * @param $tagData The data of the tag retrieve from the database.
     * 
     * @return Tag The retrieved tag.
     */
    private function hydrateTag($tagData): Tag
    {
      // Instantiate a new user with retrieved data
      $tag = new Tag(
        $tagData["id"],
        $tagData["category_id"],
        $tagData["name"],
        $tagData["description"]
      );
      return $tag;
    }


  /**
   * Helper method to hydrate Tag objects from data array.
   *
   * @param array $tagsData The array of tag data retrieved from the database.
   * 
   * @return array An array of tag objects hydrated from the provided data.
   */
  private function hydrateTags(array $tagsData): array {
    // Initialize an empty array to store the hydrated Tag objects.
    $tags = [];
  
    // Loop through each tag data in the array.
    foreach($tagsData as $tagData) {
      // Create a new Tag object using the data from the current iteration.
      $tag = new Tag(
        $tagData["id"],
        $tagData["category_id"],
        $tagData["name"],
        $tagData["description"]        
    );  
    // Add the newly created Tag object to the array.
    $tags[] = $tag;
  }
  // Return the array of hydrated Tag objects.
  return $tags;
  }

}