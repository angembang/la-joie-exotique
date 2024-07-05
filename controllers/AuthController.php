<?php

/**
 * 
 */
class AuthController extends AbstractController
{
  /**
   * 
   */
  public function registerLogin(): void 
  {
      $this->render("registerLogin", []);
  }


  /**
   * 
   */
  public function checkRegister(): void 
  {
      try {
          if($_SERVER["REQUEST_METHOD"] === "POST") {
              if((isset($_POST["emailOrPhone"])) && 
              (isset($_POST["password"])) &&
              (isset($_POST["confirm-password"])) && 
              (isset($_POST["city"])) && 
              (isset($_POST["postalCode"])) && 
              (isset($_POST["street"])) && 
              (isset($_POST["number"]))) {
                  // Check the token
                  $tokenManager = new CSRFTokenManager();
                  if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                      // Check the if the password match with the confirm password
                      if($_POST["password"] === $_POST["confirm-password"]) {
                          //Validate password against password pattern regex
                          $passwordRegex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>]).{8,}$/';
                          if (preg_match($passwordRegex, $_POST["password"])) {
                              // Retrieve and sanitize input data
                              $emailOrPhone = htmlspecialchars($_POST["emailOrPhone"]);
                              $password = htmlspecialchars($_POST["password"]);
                              $city = htmlspecialchars($_POST["city"]);
                              $postalCode = htmlspecialchars($_POST["postalCode"]);
                              $street = htmlspecialchars($_POST["street"]);
                              $number = htmlspecialchars($_POST["number"]);
                              $digicodeOrApptName = isset($_POST["digicode-or-appt-name"]) ? htmlspecialchars($_POST["digicode-or-appt-name"]) : null;
                              // Instantiate the user
                              $userManager = new UserManager();
                              // Check if user with the provided email or phone already exist
                              $user = $userManager->findUserByEmailOrPhone($emailOrPhone);
                              if($user === null) {
                                  // Create an new objet address with the provided data
                                  $address = new Address(null, $city, $postalCode, $street, $number, $digicodeOrApptName);
                                  // INstantiate the address manager 
                                  $addressManager = new AddressManager();
                                  $addressCreated = $addressManager->createAddress($address);

                                  // Check if the address is create for creating an user
                                  if($addressCreated) {
                                      // Retrieve the address identifier
                                      $addressId = $addressCreated->getId();
                                      // Hash the password
                                      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                                      // Create a new user objet
                                      $role = "user";
                                      $user = new User(null, $emailOrPhone, $hashedPassword, $role,  $addressId);
                                      $userManager = new UserManager();
                                      $userCreated = $userManager->createUser($user);

                                      // Check if the user is created and persisted to the database for redirected him to the home page
                                      if($userCreated) {
                                        $_SESSION["user"] = $userCreated->getId();
                                        // Unset error message
                                        unset($_SESSION["error-message"]);
                                        $this->redirect("index.php?route=home");
                                        exit();

                                      } else {
                                        $_SESSION["error-message"] = "Echec lors de l'inscription";
                                        $this->render("registerLogin", []);
                                        return;
                                      }
                                     
                                  } else {
                                    $_SESSION["error-message"] = "Echec lors de l'enregistrement de l'addresse";
                                    $this->render("registerLogin", []);
                                    return;
                                  }

                              } else {
                                $_SESSION["error-message"] = "L'utilisateur avec cet email ou numéro de téléphone existe déjà";
                                $this->render("registerLogin", []);
                                return;
                              }
                          } else {
                            $_SESSION["error-message"] = "Le mot de passe doit contenir au 
                                moins 8 caractères, un chiffre, une lettre en majuscule, 
                                une lettre en minuscule et un caractère spécial.";
                            $this->render("registerLogin", []);
                            return;
                          }
                      } else {
                        $_SESSION["error-message"] = "Les mots de passe ne correspondent pas";
                        $this->render("registerLogin", []);
                        return;
                      }                     
                  } else {
                    $_SESSION["error-message"] = "Invalid token";
                    $this->render("registerLogin", []);
                    return;
                  }
            } else {
              $_SESSION["error-message"] = "Veuillez renseigner tous les champs obligatoires";
              $this->render("registerLogin", []);
              return;
            }
        } else {
          $_SESSION["error-message"] = "Le formulaire n'est pas soumis par la méthode Post";
          $this->render("registerLogin", []);
         return;
        }

      } catch(Exception $e) {
        error_log("An orror occurs during the operation: ".$e->getMessage().$e->getcode());
        $_SESSION["error-message"] = "An orror occurs during the operation";
      
      }
  }


  /**
   * 
   */
  public function checkLogin(): void 
  {
      try {
          if($_SERVER["REQUEST_METHOD"] === "POST") {
              if((isset($_POST["emailOrPhone"])) && (isset($_POST["password"]))) {
                  // Check the token
                  $tokenManager = new CSRFTokenManager();
                  if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                      // Instantiate the user manager and check if the user with provided email or phone exist
                      $userManager = new UserManager();
                      $emailOrPhone = htmlspecialchars($_POST["emailOrPhone"]);
                      $user = $userManager->findUserByEmailOrPhone($emailOrPhone);

                      // Check if user is found
                      if($user) {
                          $userPassword = $user->getPassword();
                          // Check if password match 
                          $password = htmlspecialchars($_POST["password"]);
                          if(password_verify($password, $userPassword)) {
                              $userRole = $user->getRole();
                              $_SESSION["user"] = $user->getId();
                              $_SESSION["user_role"] = $userRole;
                              unset($_SESSION["error-message"]);
                              
                              // Redirect based on user role
                              if($userRole === "admin") {
                                  $this->redirect("index.php?route=admin");
                              }
                              else if($userRole === "user") {
                                if (isset($_POST['redirect']) && $_POST['redirect'] === 'payment') {
                                    $this->redirect('index.php?route=payment-form&redirect=payment');
                                } else {
                                    $this->redirect('index.php?route=home');
                                }
                              }
                              exit();
                          } else {
                            $_SESSION["error-message"] = "Mot de passe incorrect"; 
                            $this->render("registerLogin", []);
                            return;
                          }                          
                      } else {
                        $_SESSION["error-message"] = "L'utilisateur avec cet email ou téléphone n'existe pas";
                        $this->render("registerLogin", []);
                        return;
                      }

                  } else {
                    $_SESSION["error-message"] = "Invalid token";
                    $this->render("registerLogin", []);
                    return;
                  }
                 
              } else {
                $_SESSION["error-message"] = "Veuillez renseigner tous les champs obligatoires";
                $this->render("registerLogin", []);
                return;
              }
              
          } else {
            $_SESSION["error-message"] = "Le formulaire n'est pas soumis par la méthode Post";
            $this->render("registerLogin", []);
            return;
          }
          
      } catch(Exception $e) {
        error_log("an error occurs during the operation: ".$e->getMessage().$e->getCode());
        $_SESSION["error-message"] = "An orror occurs during the operation";
        $this->render("registerLogin", []);
      }
  }


  /**
   * 
   */
  public function logout(): void
  {

  }
}