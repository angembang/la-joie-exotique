<?php

/**
 * Manages the retrieval and persistence of Category object in the platform
 */
class CategoryManager extends AbstractManager 
{
  /**
   * Creates a new Category and persits its in the database
   * 
   * @param Category $category The category object to be created.
   * 
   * @return Category The created category object with the assigned identifier.
   * 
   * @throws PDOException If the category is not create.
   */
  public function createCategory(Category $category): ?Category 
  {
    try {
      // Prepare the SQL query to insert a new category into the database
      $query = $this->db->prepare("INSERT INTO categories (id, name, description) VALUES 
      (:id, :name, :description)");

      // Bind parameters with their values
      $parameters = [
        ":id" => $category->getId(),
        ":name" => $category->getName(),
        ":description" => $category->getDescription()
      ];

      // Execute the query with parameters
      $query->execute($parameters);

      // Retrieves the last inserted identifier
      $categoryId = $this->db->lastInsertId();

      // Set the identifier for the created category
      $category->setId($categoryId);

      // Return the created category
      return $category;
    
    } catch(PDOException $e) {
        // Handle the exception appropriately
        throw new PDOException("Failed to create a new category");
    }
  }


  /**
   * Retrieves a category by its unique identifier
   * 
   * @param int $categoryId The unique identifier of the category.
   * 
   * @return Category|null The retrieved category. Null if not found.
   * 
   * @throws PDOException If the category is not found.
   */
  public function findCategoryById(int $categoryId): ?Category
  {
    try {
      // Prepare the SQL query to retrieve the category by its unique identifier
      $query = $this->db->prepare("SELECT * FROM categories WHERE id = :id");

      // Bind the parameter with its value
      $parameter = [
        ":id" => $categoryId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the category data from the database
      $categoryData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if category data is found
      if($categoryData) {
        $category = new Category(
          $categoryData["id"],
          $categoryData["name"],
          $categoryData["description"]
        );
        return $category;
      } 

    } catch(PDOException $e) {
        throw new PDOException("Failed to found a category");
    }
  }


  /**
   * Retrieves a category by its name
   * 
   * @param string $categoryName The name of the category.
   * 
   * @return Category|null The retrieved category. Null if not found.
   * 
   * @throws PDOException If the category is not found.
   */
  public function findCategoryByName(string $categoryName): ?Category 
  {
    try {
      // Prepare the SQL query to retrieve the category by its name.
      $query = $this->db->prepare("SELECT * FROM categories WHERE name = :name");

      // Bind the parameter with its value
      $parameter = [
        ":name" => $categoryName
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the category data from the database
      $categoryData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if category data is found
      if($categoryData) {
        $category = new Category(
        $categoryData["id"],
        $categoryData["name"],
        $categoryData["description"]
      );
      return $category;
      } 
    } catch(PDOException $e) {
        throw new PDOException("Failed to found a category");
    }
  }
}