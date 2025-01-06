<?php
$products = $product->getAll();

$groupedProducts = [];
foreach ($products as $product) {
    $id = $product['id'];

    //if product id is not in the array
    if (!isset($groupedProducts[$id])) {
        $groupedProducts[$id] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'category' => $product['category'],
            'created_at' => $product['created_at'],
            'variants' => [],
        ];
    }

    $groupedProducts[$id]['variants'][] = [
        'id' => $product['variant_id'],
        'name' => $product['variant_name'],
        'price' => $product['variant_price'],
        'image' => "public/uploads/products/" . $product['variant_image'],
    ];
}
?>

<div class="row">
    <?php foreach ($groupedProducts as $product): ?>
    <div class="col-md-4 mb-4">

        <div class="card product-card" style="max-width: 300px;">

            <img src="<?php echo $product['variants'][0]['image']; ?>" id="product-image-<?=$product['id'];?>"
                class="card-img-top" style="height: 200px;
        object-fit: cover;">

            <div class="card-body">

                <h5 class="card-title" id="product-name-<?=$product['id'];?>">
                    <?php echo htmlspecialchars($product['name']); ?>
                </h5>

                <p class="card-text">Price: $<span
                        id="product-price-<?=$product['id'];?>"><?php echo $product['variants'][0]['price'] ?>
                    </span>
                </p>

                <div class="btn-group mb-3" role="group" aria-label="Product Variants">

                    <?php foreach ($product['variants'] as $variant): ?>

                    <button class="btn btn-outline-success variant-button"
                        data-product-id="<?php echo $product['id']; ?>" data-variant-id="<?php echo $variant['id']; ?>"
                        data-variant-image="<?php echo $variant['image']; ?>"
                        data-variant-price="<?php echo $variant['price']; ?>">

                        <?php echo htmlspecialchars($variant['name']); ?>
                    </button>

                    <?php endforeach;?>

                </div>

                <button data-product-id="<?php echo $product['id']; ?>" id="add-to-cart-<?php echo $product['id']; ?>"
                    class="btn btn-success w-100">
                    Add to Cart
                </button>
            </div>

        </div>
    </div>
    <?php endforeach;?>
</div>

<script>
$(document).ready(function() {

    $('.variant-button').on('click', function() {

        //fetch value from data based selector
        const productId = $(this).data('product-id');
        const variantId = $(this).data('variant-id');
        const variantName = $(this).text();
        const image = $(this).data('variant-image');
        const price = $(this).data('variant-price');

        // update the main product card image and price
        $(`#product-image-${productId}`).attr('src', image);
        $(`#product-price-${productId}`).text(parseFloat(price).toFixed(2));

        // active the selected button and remove old button active class
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
    });

    $('[id^="add-to-cart-"]').on('click', function() {

        const productId = $(this).data('product-id');
        const name = $(`#product-name-${productId}`).text();
        const price = $(`#product-price-${productId}`).text();


        //find the add cart button closest selected variant info or else select the first one as default.
        const variantId =
            $(this).closest('.product-card').find('.variant-button.active').data('variant-id') ||
            $(this).closest('.product-card').find('.variant-button').first().data('variant-id');

        const variantName = $(this).closest('.product-card').find('.variant-button.active').text() ||
            $(this).closest('.product-card').find('.variant-button').first().text();


        //make json ProductData
        const productData = {
            "id": productId,
            "name": name,
            "price": Number(price),
            variantId,
            variantName,
            "quantity": 1
        };

        //fetch data from localstorage and add new item on front
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');

        //check if cartlist have item with the same variantId then just increse the quantity
        const index = cart.findIndex((productData) => productData.variantId == variantId);

        if (index != -1) cart[index].quantity += 1;
        else cart.unshift(productData);

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCart();

        alert("Product added to cart");
    });

});
</script>