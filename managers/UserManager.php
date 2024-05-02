<?php

/**
 * Manages the retrieval and persistence of User object in the platform
 */
class UserManager extends AbstractManager
{
  /**
   * creates a new user and persists its in the database.
   * 
   * @param User $user The user object to be created.
   * 
   * @return User The created user object with the assigned identifier.
   */
  public function createUser(User $user): ?User
  {
    try {
      // Prepare the SQL query to insert a new user into the database
      $query = $this->db->prepare("INSERT INTO users (email_or_phone, password, role, address_id) 
      VALUES (:email_or_phone, :password, :role, :address_id)");

      // Bind the parameters with their values.
      $parameters = [
        ":email_or_phone" => $user->getEmailOrPhone(),
        ":password" => $user->getPassword(),
        ":role" => $user->getRole(),
        ":address_id" => $user->getAddressId()
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $userId = $this->db->lastInsertId();

      // Set the identifier for the created user
      $user->setId($userId);

      // Return the created user object
      return $user;
    
    } catch (Exception $e) {
        // Handle the exception appropriately
        throw new Exception("Failed to create user: " . $e->getMessage());
    }
  }
  


  /**
   * Retrieves an user by his unique identifier
   * 
   * @param int $userId The unique identifier of the user
   * 
   * @return User|null The retrieved user or null if not found.
   */
  public function findUserById(int $userId): ?User
  {
    try {
      // Prepare the SQL query to retrieve the user by its unique identifier
      $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
    
      // Bind parameters with their values.
      $parameters = [
        ":id" => $userId
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Call the fetchUser method to retrieve the user
      return $this->fetchUser($query, $parameters);
    
    } catch (Exception $e) {
        // Handle the exception appropriately
        throw new Exception("Failed to find user: " . $e->getMessage());
    }     
  }


  /**
   * Retrieves an user by its email or phone
   * 
   * @param string $emailOrPhone The emil or phone of the user.
   * 
   * @return User|null The retrieved user or null if not found. 
   */
  public function findUserByEmailOrPhone(string $emailOrPhone): ?User
  {
    try {
      // Prepare the SQL query to retrieve the user by its email or phone.
      $query = $this->db->prepare("SELECT * FROM users WHERE email_or_phone = :email_or_phone");

      // Bind parameters with their values.
      $parameters = [
        ":email_or_phone" => $emailOrPhone
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Call the fetchUser method to retrieve the user
      return $this->fetchUser($query, $parameters);
    
    } catch(Exception $e) {
        throw new Exception("Failed to find user:" . $e->getMessage());
    }  
  }


  /**
   * Retrieves an user by its address identifier
   * 
   * @param int $addressId The address identifier of the user.
   * 
   * @return User|null The retrieved user or null if not found.
   */
  public function findUserByAddressId(int $addressId): ?User
  {
    try {
      // Prepare the SQL query to retrieve the user by its address identifier.
      $query = $this->db->prepare("SELECT * FROM users WHERE address_id = :address_id");

      // Bind parameters with their values.
      $parameters = [
        ":address_id" => $addressId
      ];

      // Execute The query with parameters.
      $query->execute($parameters);

      // Call the fetchUser method to retrieve the user
      return $this->fetchUser($query, $parameters);
    
    } catch(Exception $e) {
        throw new Exception("Failed to find user:" .$e->getMessage());
    }    
  }


  /**
   * Retrieves an user by its role
   * 
   * @param string $role The role of the user.
   * 
   * @return User|null The user retrieved by its role or null if not found.
   */
  public function findUserByRole(string $role): ?User
  {
    try {
      // Prepare the SQL query to retrieve the user by its role.
      $query = $this->db->prepare("SELECT * users WHERE role = :role");

      // Bind parameters with their values.
      $parameters = [
        ":role" => $role
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Call the fetchUser method to retrieve the user
      return $this->fetchUser($query, $parameters);
    
    } catch(Exception $e) {
      throw new Exception("Failed to find user:" .$e->getMessage());
    }   
  }


  /**
   * Retrieves all users
   *
   * @return array User|null The array of user or null if not found.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all users into the database
      $query = $this->db->prepare("SELECT * FROM users");

      // Execute the query
      $query->execute();

      // Fetch users data from the database
      $usersData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if users data is not empty
      if($usersData) {
        $users = [];
        
        // Loop through each user data
        foreach($usersData as $userData) {
          // Create a user for each user data
          $user = new User(
            $userData["id"],
            $userData["email_or_phone"],
            $userData["password"],
            $userData["role"],
            $userData["address_id"]
          );

          // Add the created user object to the users array
          $users[] = $user;
        }
        // Return the array of the user objects
        return $users;
      }

    } catch(Exception $e) {
      throw new Exception("Failed to find user:" .$e->getMessage());
    }  
  }


  /**
   * Updates an user in the database
   * 
   * @param User $user The user to be updated.
   * 
   * @return User The user updated.
   */
  public function updateUser(User $user): ?User
  {
    try {
      // Prepare the SQL query to update an user.
      $query = $this->db->prepare("UPDATE users SET 
      email_or_phone = :email_or_phone,
      password = :password,
      role = :role,
      address_id = :address_id");

      // Bind parameters with their values
      $parameters = [
        ":email_or_phone" => $user->getEmailOrPhone(),
        ":password" => $user->getPassword(),
        ":role" => $user->getRole(),
        ":address_id" => $user->getAddressId()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $user;
      }  
    
    } catch(Exception $e) {
      throw new Exception("Failed to update user:" .$e->getMessage());
    }  
  }
  
  
  /**
   * Deletes an user from the database
   * 
   * @param int $userId The unique identifier of the user to be deleted
   * 
   * @return bool True if the operation is successful, false if not
   */
  public function deleteUserById(int $userId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve user.
      $query = $this->db->prepare("DELETE FROM users WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $userId
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } else {
        return false;
      }
  
    } catch(Exception $e) {
        throw new Exception("Failed to delete user:" .$e->getMessage());
    }  
  }


  /**
     * Executes a query to fetch user data from the database and returns a User object if found.
     * 
     * @param PDOStatement $query The prepared statement object.
     * 
     * @param $parameters The parameters of the query.
     * 
     * @return User The retrieved user.
     */
    private function fetchUser(PDOStatement $query, array $parameters): User
    {
      // Execute the query with parameters
      $query->execute($parameters);

      // Fetch the user data from the database
      $userData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if user is found
      if ($userData) {
          // Create a new user with retrieved data
          $user = new User(
            $userData["id"],
            $userData["email_or_phone"],
            $userData["password"],
            $userData["role"],
            $userData["address_id"]
          );
          return $user;
      
        } 
      // Throw an exception if user is not found
      throw new Exception("User not found");
    }
}