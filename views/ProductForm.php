<?php

$uploader = new FileUploader();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $productData = [
            'name' => $_POST['name'],
            'category' => $_POST['category'],
            'variants' => [],
        ];

        foreach ($_POST['variants'] as $key => $variant) {
            $imagePath = '';
            if (isset($_FILES['images']['name'][$key])) {
                $imagePath = $uploader->upload([
                    'name' => $_FILES['images']['name'][$key],
                    'tmp_name' => $_FILES['images']['tmp_name'][$key],
                    'error' => $_FILES['images']['error'][$key],
                ]);
            }

            $productData['variants'][] = [
                'name' => $variant['name'],
                'price' => $variant['price'],
                'image' => $imagePath,
            ];
        }

        if ($product->create($productData)) {
            $success = 'Product added successfully!';
        } else {
            $error = 'Failed to add product.';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?>


<?php if ($error): ?>
<div class="alert alert-danger"><?=$error?></div>
<?php endif;?>

<?php if ($success): ?>
<div class="alert alert-success"><?=$success?></div>
<?php endif;?>

<div class="col-md-6">

    <div class="card">
        <div class="card-header">
            <h2>Add Product</h2>
        </div>
        <div class="card-body">
            <form id="productForm" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Product Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Category</label>
                    <select name="category" class="form-control" required>
                        <option value="Electronics">Electronics</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Books">Books</option>
                    </select>
                </div>
                <div id="variantsContainer">
                    <div class="variant-group mb-3">
                        <h4>Vaiant 1</h4>
                        <input type="text" name="variants[0][name]" class="form-control mb-2" placeholder="Variant Name"
                            required>
                        <input type="file" name="images[]" class="form-control mb-2" required>
                        <input type="number" name="variants[0][price]" class="form-control mb-2" placeholder="Price"
                            required>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" id="addVariant">Add Variant</button>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let variantCount = 1;

    // add new variant
    $('#addVariant').click(function() {
        variantCount++;
        const newVariant = `
                    <div class="variant-group mb-3">
                        <h4>Variant ${variantCount} <button type="button" class="btn btn-danger btn-sm remove-variant">Remove</button></h4>
                        <input type="text" name="variants[${variantCount-1}][name]" class="form-control mb-2" placeholder="Variant Name" required>
                        <input type="file" name="images[]" class="form-control mb-2" required>
                        <input type="number" name="variants[${variantCount-1}][price]" class="form-control mb-2" placeholder="Price" required>
                    </div>
                `;
        $('#variantsContainer').append(newVariant);
    });

    // remove variant on click
    $(document).on('click', '.remove-variant', function() {
        $(this).closest('.variant-group').remove();
    });

});
</script>