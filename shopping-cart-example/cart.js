function addToCart(product_id) {
  console.log(`Add product ${product_id} to cart.`);

  let formData = new FormData();
  formData.append('product_id', product_id);
  const options = {
      method: 'POST',
      body: formData
  };

  fetch('index.php?route=ajouter-au-panier', options)
      .then(response => response.json())
      .then(data => {
          console.log(data);
      });
}

export {addToCart};