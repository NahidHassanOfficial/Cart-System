<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <div class="text-start">
            <h5 id="offcanvasRightLabel" class="mb-0 fs-4">Shop Cart</h5>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div>
            <div class="py-3">
                <ul class="list-group list-group-flush" id="productList"></ul>
            </div>
            <div class="d-grid">
                <a id="checkoutButton" href="#"
                    class="btn btn-success btn-lg d-flex justify-content-between align-items-center">
                    Go to Checkout <span class="fw-bold" id="totalAmount">0.00</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function updateCart() {
    // clean the old cart items before updating with new
    $('#productList').empty();

    // get the cart items from localstorage
    const cartList = JSON.parse(localStorage.getItem('cart')) || [];

    let totalAmount = 0;

    // generate cart list view and append  in productlist container
    cartList.forEach(product => {
        totalAmount += product.price * product.quantity;

        const productItem = `
                <li class="list-group-item py-3 px-0 border-top">
                    <div class="row align-items-center">

                        <div class="col-5">
                            <h6 class="mb-0">${product.name}</h6>
                             <span><small class="text-muted">${product.variantName}</small></span>
                            <div class="mt-2 small">
                                <button class="bg-none btn btn-sm text-danger remove-item" data-id="${product.variantId}">
                                    Remove
                                </button>
                            </div>
                        </div>
                         <div class="col-3">
                             <span class="fw-bold">${product.quantity}</span>
                         </div>
                        <div class="col-2 text-end">
                            <span class="fw-bold">${product.price * product.quantity}</span>
                        </div>
                    </div>
                </li>
            `;
        $('#productList').append(productItem);
    });

    // update total amount
    $('#totalAmount').text(totalAmount.toFixed(2));


}

// handle cart item removal feature
$('#productList').on('click', '.remove-item', function() {
    const variantId = $(this).data('id');

    //get localStorage data and filter cartlist
    const cartList = JSON.parse(localStorage.getItem('cart')) || [];
    const updatedCart = cartList.filter(product => product.variantId !== variantId);

    // update the localstorage data with filtered data and update cart
    localStorage.setItem('cart', JSON.stringify(updatedCart));
    updateCart();
});
</script>
