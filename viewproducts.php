<?php

//maping all the fields
$fields = [
    'name' => ['label' => 'Name (required)', 'type' => 'text', 'required' => true],
    'price' => ['label' => 'Price', 'type' => 'number', 'step' => '0.01'],
    'quantity' => ['label' => 'Quantity', 'type' => 'number'],
    'category' => ['label' => 'Category', 'type' => 'text'],
    'category_id' => ['label' => 'Category ID', 'type' => 'text'],
    'manufacturer' => ['label' => 'Manufacturer', 'type' => 'text'],
    'barcode' => ['label' => 'Barcode', 'type' => 'text'],
    'weight' => ['label' => 'Weight', 'type' => 'text'],
    'instock' => ['label' => 'In Stock (Y/N)', 'type' => 'radio', 'options' => ['Y' => 'Yes', 'N' => 'No']],
    'availability' => ['label' => 'Availability', 'type' => 'text'],
];
?>

<!-- With this form you can submit products! -->
<h2>Add New Product</h2>
<form method="POST" action="">
 
<?php foreach ($fields as $field => $attributes): ?>
    <label for="<?= $field; ?>"><?= $attributes['label']; ?>:</label><br>
    
    <?php if ($attributes['type'] === 'radio' && isset($attributes['options'])): ?>
        <?php foreach ($attributes['options'] as $value => $display): ?>
            <input 
                type="radio" 
                id="<?= $field . '_' . $value; ?>" 
                name="<?= $field; ?>" 
                value="<?= $value; ?>" 
                <?= ($_POST[$field] ?? 'N') === $value ? 'checked' : ''; ?>
            >
            <label for="<?= $field . '_' . $value; ?>"><?= $display; ?></label>
        <?php endforeach; ?>
        <br><br>
    <?php else: ?>
        <input 
            type="<?= $attributes['type']; ?>" 
            id="<?= $field; ?>" 
            name="<?= $field; ?>" 
            <?= $attributes['type'] === 'number' && isset($attributes['step']) ? 'step="' . $attributes['step'] . '"' : ''; ?>
            <?= $attributes['required'] ?? false ? 'required' : ''; ?>
            value="<?= htmlspecialchars($_POST[$field] ?? ''); ?>"
        >
        <br><br>
    <?php endif; ?>

<?php endforeach; ?>

<input type="submit" value="Add Product">
 
</form> 