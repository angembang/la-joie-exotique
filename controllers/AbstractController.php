<?php

/*
 * Abstract class AbstractController
 * representing a base controller.
 */
abstract class AbstractController
{
  /**
   * Method to render a template with data.
   *
   * @param string $template The path to the template to render.
   * @param array $data The data to pass to the template.
   * @return void
   */
  protected function render(string $template, array $data): void
  {
    // Include the layout.phtml which wraps around the specified template.
    require "templates/layout.phtml";
  }


  /**
   * Redirects the user to another page.
   *
   * @param string $route The path to the new page.
   * @return void
   */
  protected function redirect(string $route): void 
  {
    // Perform a redirection using the Location header.
    header("Location: $route");
  }


  
  /**
   * Renders the data as JSON.
   *
   * @param array $data The data to encode as JSON.
   * @return void
   */
  protected function renderJSON(array $data): void 
  {
    // Convert the data to JSON and output it.
    echo json_encode($data);
  }
}