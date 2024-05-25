<?php

/**
 * Manages the retrieval and persistence of Category object in the platform
 */
class CategoryManager extends AbstractManager 
{
  /**
   * Creates a new Category and persists it in the database
   * 
   * @param Category $category The category object to be created.
   * 
   * @return Category The created category object with the assigned identifier.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createCategory(Category $category): Category 
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

      // Retrieve the last inserted identifier
      $categoryId = $this->db->lastInsertId();

      // Set the identifier for the created category
      $category->setId($categoryId);

      // Return the created category
      return $category;
    
    } catch(PDOException $e) {
      // Log the error message and code to the error log file
      error_log("Failed to create a new category: " .$e->getMessage(), $e->getCode());
      // Handle the exception appropriately
      throw new PDOException("Failed to create a new category");
    }
  }


  /**
   * Retrieves a category by its unique identifier
   * 
   * @param int $categoryId The unique identifier of the category.
   * 
   * @return Category|null The retrieved category, or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
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
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find the category: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find the category");
    }
  }


  /**
   * Retrieves a category by its name
   * 
   * @param string $categoryName The name of the category.
   * 
   * @return Category|null The retrieved category, or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
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
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find the category: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find a category");
    }
  }


  /**
   * Retrieves all categories
   *
   * @return array category|null The array of category, or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve allcategories into the database
      $query = $this->db->prepare("SELECT * FROM categories");

      // Execute the query
      $query->execute();

      // Fetch categories data from the database
      $categoriesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if categories data is not empty
      if($categoriesData) {
        $categories = [];
        // Loop through each category data
        foreach($categoriesData as $categoryData) {
          // Instantiate an user for each user data
          $category = new Category(
            $categoryData["id"],
            $categoryData["name"],
            $categoryData["description"]
          );
          // Add the instantiated category object to the categories array
          $categories[] = $category;
        }
        // Return the array of the category objects
        return $categories;
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find categories: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find categories");
    }  
  }



  /**
   * update a category in the database
   * 
   * @param Category $category The category to be updated.
   * 
   * @return Category|null The category updated, or null if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updateCategory(Category $category): ?Category 
  {
    try {
      // Prepare the SQL query to update the category
      $query = $this->db->prepare("UPDATE categories SET
      name = :name,
      description = :description 
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $category->getId(),
        ":name" => $category->getName(),
        ":description" =>$category->getDescription()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $category;
      }
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the category: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to update the category");
    }
  }


  /**
   * Deletes a category from the database
   * 
   * @param int $categoryId The unique identifier of the category to be deleted.
   * 
   * @return bool True if successful, false if not.
   * 
   */
  public function deleteCategoryById(int $categoryId): bool
  {
    try {
      // Prepare the SQL query to delete a category by its unique identifier
      $query = $this->db->prepare("DELETE FROM categories WHERE id = :id");

      // Bind the parameter with its value
      $parameter = [
        ":id" => $categoryId
      ];

      // Execute the query with the parameter.
      $success = $query->execute($parameter);
      if($success) {
        return true;
      }
      return false;
    
    } catch(PDOException $e) {
      error_log("Failed to delete the category: " .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to delete the category");
    }
  }
}