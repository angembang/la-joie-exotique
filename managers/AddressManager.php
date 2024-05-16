<?php

/**
 * Manages the retrieval and persistence of Address objects in the platform
 */
class AddressManager extends AbstractManager
{
  /**
   * Creates a new Address and persists its in the database
   * 
   * @param Address $address The address object to be created.
   * 
   * @return Address The created address with the assigned identifier.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function createAddress(Address $address): Address
  {
    try {
      // Prepare the SQL query to insert a new address into the database
      $query = $this->db->prepare("INSERT INTO addresses 
      (city, postal_code, street, number, digicode_or_appt_name)
      VALUES
      (:city, :postal_code, :street, :number, :digicode_or_appt_name)");

      // Bind parameters with theirs values
      $parameters = [
        ":city" => $address->getCity(),
        ":postal_code" => $address->getPostalCode(),
        ":street" => $address->getStreet(),
        ":number" => $address->getNumber(),
        ":digicode_or_appt_name" => $address->getDigicodeOrApptName()
      ];

      // Execute the query with parameters
      $query->execute($parameters);

      // Retrieve the last inserted identifier
      $addressId = $this->db->lastInsertId();

      // Set the identifier for the created address
      $address->setId($addressId);

      // Return the created address object
      return $address;
    
    } catch(PDOException $e) {
      error_log("failed to create address" .$e->getMessage(),  $e->getCode());
      throw new PDOException("Failed to create address");
    }
  }


  /**
   * Retrieves an address by its unique identifier
   * 
   * @param int $addressId the unique identifier of the address. 
   * 
   * @return Address|null The retrieved address, or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAddressById(int $addressId): ?Address
  {
    try {
      // Prepare the SQL query to retrieve the address by its unique identifier.
      $query = $this->db->prepare("SELECT * FROM addresses WHERE id = :id");

      // Bind the parameter with its value
      $parameter = [
        ":id" => $addressId
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the address data from the database
      $addressData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if address is found
      if($addressData) {
        // Instantiate a new address with retrieved data
        $address = new Address(
          $addressData["id"],
          $addressData["city"],
          $addressData["postal_code"],
          $addressData["street"],
          $addressData["number"],
          $addressData["digicode_or_appt_name"]
        );
        // Return the address object.
        return $address;
      }
      // Address is not found, return null.
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find address" .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find address");
    }
  }


  /**
   * Retrieves addresses for a specific city
   * 
   * @param string $city The city of addresses. 
   * 
   * @return array|null The array of retrieved addresses, or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAddressesByCity(string $city): ?array
  {
    try {
      // Prepare the SQL query to retrieve addresses by their city.
      $query = $this->db->prepare("SELECT * FROM addresses WHERE city = :city");

      // Bind the parameter with its value.
      $parameter = [
        ":city" => $city
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch the address data from the database
      $addressesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if addresses data is not empty
      if($addressesData) {
        return $this->hydrateAddresses($addressesData);
      } 
      // No address is found, return null.
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find addresses" .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find addresses");
    }
  }


  /**
   * Retrieves all addresses
   * 
   * @return array Address|null The array of the retrieved address, or null if no address is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array  
  {
    try {
      // Prepare the SQL query to retrieve all addresses into the database.
      $query = $this->db->prepare("SELECT * FROM addresses");

      // Execute the query.
      $query->execute();

      // Fetch addresses data from the database
      $addressesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if addresses data is not empty
      if($addressesData) {
        return $this->hydrateAddresses($addressesData);
      } 
      // No address is found, return null.
      return null;
       
    } catch(PDOException $e) {
      error_log("Failed to find addresses" .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to find addresses");
    }
  }


  /**
   * Updates an address in the database
   * 
   * @param Address $address The address to be updated.
   * 
   * @return Address|null The updated address, or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation. 
   */
  public function updateAddress(Address $address): ?Address 
  {
    try {
      // Prepare the SQL query to update the address.
      $query = $this->db->prepare("UPDATE addresses SET 
      city = :city,
      postal_code = :postal_code,
      street = :street,
      number = :number,
      digicode_or_appt_name = :digicode_or_appt_name 
      WHERE id = :id ");

      // Bind parameters with their values.
      $parameters = [
        ":id" => $address->getId(),
        ":city" => $address->getCity(),
        ":postal_code" => $address->getPostalCode(),
        ":street" => $address->getStreet(),
        ":number" => $address->getNumber(),
        ":digicode_or_appt_name" => $address->getDigicodeOrApptName()
      ];

      // Execute the query with parameters.
      $success = $query->execute($parameters);

      // Check if success.
      if($success) {
        return $address;
      } else {
        return null;
      }
    
    } catch(PDOException $e) {
      error_log("Failed to update the address" .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to update the address");
    }
  }


  /**
   * Deletes an address from the database
   * 
   * @param int $addressId The unique identifier of the address to delete.
   * 
   * @return bool True is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation. 
   */
  public function deleteAddressById(int $addressId): bool 
  {
    try {
      // Prepare the SQL query to delete an address by its unique identifier
      $query = $this->db->prepare("DELETE FROM addresses WHERE id = :id");

      // Bind the parameter with its value.
      $parameter = [
        ":id" => $addressId
      ];

      // Execute the query with the parameter
      $success = $query->execute($parameter);

      // Check if success
      if($success) {
        return true;
      } else {
        return false;
      }

    } catch(PDOException $e) {
      error_log("Failed to delete the address" .$e->getMessage(), $e->getCode());
      throw new PDOException("Failed to delete the address");
    }
  }


  /**
   * Helper method to hydrate address objects from data array
   * 
   * @param array $addressesData The array of address data rtrieved from the database.
   * 
   * @return array An array of address objects hydrated from the provided data.
   */
  private function hydrateAddresses(array $addressesData): array 
  {
    // Initialize an empty array to store the hydrated Product objects.
    $addresses = [];

    // Loop through each product data in the array.
    foreach($addressesData as $addressData) {
      // Create a new Address object using the data from the current iteration.
      $address = new Address(
        $addressData["id"],          
        $addressData["city"],        
        $addressData["postal_code"],  
        $addressData["street"],        
        $addressData["number"],
        $addressData["digicode_or_appt_name"]        
      );
    
      // Add the newly created Product object to the array.
      $addresses[] = $address;
    }
    // Return the array of hydrated Product objects.
    return $addresses;
  }

}