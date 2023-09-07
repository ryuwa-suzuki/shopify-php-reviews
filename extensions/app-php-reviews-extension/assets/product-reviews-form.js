function submit(productId) {
  let inputData = {
    name: document.getElementById('review-name').value,
    comment: document.getElementById('review-comment').value,
    product_id: productId
  }

  const ratings = document.getElementsByName('review-rating');

  for (rating of ratings) {
    if (rating.checked) {
      inputData.rating = rating.value;
      break;
    }
  }


  const fetchOptions = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(inputData)
  };

  fetch("/apps/app-php-reviews/api/reviews/create", fetchOptions);
}
