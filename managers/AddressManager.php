<?php

/**
 * Manages the retrieval and persistence of Address object in the platform
 */
class AddressManager extends AbstractManager
{
  /**
   * Creates a new Address and persits its in the database
   * 
   * @param Address $address The address object to be created.
   * 
   * @return Address The created address with the assigned identifier.
   */
  public function createAddress(Address $address): ?Address
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

      // Retrieves the last inserted idntifier
      $addressId = $this->db->lastInsertId();

      // Set the identifier for the created address
      $address->setId($addressId);

      // Return the created address object
      return $address;
    
    } catch(Exception $e) {
        throw new Exception("Failed to create address:");
    }
  }


  /**
   * Retrieves an address by its unique identifier
   * 
   * @param int $addressId the unique identifier of the address. 
   * 
   * @return Address|null The retrieved address. Null if not found.
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
        return $address;
      }
    } catch(Exception $e) {
        throw new Exception("Failed to found address:");
    }
  }


  /**
   * Retrieves addresses for a specific city
   * 
   * @param string $city The city of the address. 
   * 
   * @return array|null The array of retrieved addresses. Null if not found
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
        $addresses = [];
        // Loop through each address data
        foreach($addressesData as $addressData) {
          // Instantiate an address for each address data
          $address = new Address(
            $addressData["id"],
            $addressData["city"],
            $addressData["postal_code"],
            $addressData["street"],
            $addressData["number"],
            $addressData["digicode_or_appt_name"]
          );
          // Add the instatiated Address to the addresses array.
          $addresses[] = $address;
        }
        // Return the array of the address objects.
        return $addresses;
      }
    } catch(Exception $e) {
        throw new Exception("failed to found addresses:");
    }
  }

}