<?php

/**
 * Manages the retrieval and persistence of Tag object in the platform
 */
class TagManager extends AbstractManager
{
  /**
   * Creates a new tag and persits its in the database
   * 
   * @param Tag $tag The tag object to be created.
   * 
   * @return Tag The created tag object with the assigned identifier.
   * 
   * @throws PDOException If the tag is not created.
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

      // Set the identifier for the created category
      $tag->setId($tagId);

      // Return the created category
      return $tag;
    
    } catch(PDOException $e) {
        // Handle the exception appropriately
        throw new PDOException("Failed to create a new tag");
    }
  }


  /**
   * Retrieves a tag by its unique identifier
   * 
   * @param int $tagId The unique identifier of the tag.
   * 
   * @return Tag|null The retrieved tag. Null if not found.
   * 
   * @throws PDOException If the tag is not found.
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
        $tag = new Tag(
          $tagData["id"],
          $tagData["category_id"],
          $tagData["name"],
          $tagData["description"]
        );
        return $tag;
      } 

    } catch(PDOException $e) {
        throw new PDOException("Failed to found a tag");
    }
  }


  /**
   * Retrieves a tag by its category identifier
   * 
   * @param int $categoryId The category identifier of the tag.
   * 
   * @return Tag|null The retrieved tag. Null if not found.
   * 
   * @throws PDOException If the tag is not found.
   */
  public function findTagByCategoryId(int $categoryId): ?Tag
  {
    try {
      // Prepare the SQL query to retrieve the tag by its category identifier
      $query = $this->db->prepare("SELECT * FROM tags WHERE category_id = :category_id");

      // Bind the parameter with its value
      $parameter = [
        ":category_id" => $categoryId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the category data from the database
      $tagData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if category data is found
      if($tagData) {
        $tag = new Tag(
          $tagData["id"],
          $tagData["category_id"],
          $tagData["name"],
          $tagData["description"]
        );
        return $tag;
      } 

    } catch(PDOException $e) {
        throw new PDOException("Failed to found a tag");
    }
  }


  /**
   * Retrieves a tag by its name
   * 
   * @param string $tagName The name of the tag.
   * 
   * @return Tag|null The retrieved tag. Null if not found.
   * 
   * @throws PDOException If the tag is not found.
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
        $tag = new Tag(
          $tagData["id"],
          $tagData["category_id"],
          $tagData["name"],
          $tagData["description"]
        );
        return $tag;
      } 

    } catch(PDOException $e) {
        throw new PDOException("Failed to found a tag");
    }
  }


  /**
   * update a tag in the database
   * 
   * @param Tag $tag The tag to be updated.
   * 
   * @return Tag The tag updated.
   * 
   * @throws PDOException If the tag is not updated
   */
  public function updateTag(Tag $tag): Tag 
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
      $query->execute($parameters);
      return $tag;
    
    } catch(PDOException $e) {
        throw new PDOException("Failed to update the tag");
    }
  }


  /**
   * Deletes a tag from the database
   * 
   * @param int $tagId The unique identifier of the tag to be deleted.
   * 
   */
  public function deleteTagById(int $tagId): void 
  {
    try {
      // Prepare the SQL query to delete a tag by its unique identifier
      $query = $this->db->prepare("DELETE FROM tags WHERE id = :id");

      // Bind the parameter with its value
      $parameter = [
        ":id" => $tagId
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);
    
    } catch(PDOException $e) {
        throw new PDOException("Failed to delete the tag");
    }
  }

}