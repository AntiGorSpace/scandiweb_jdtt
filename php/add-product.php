<?php
$title = "Product Add";
$head_buttons = [
    [ "id"=>"save", "name" => "Save" ],
    [ "id"=>"cansel", "name" => "Cansel" ]
];
require_once "blocks/header.php";

?>
<div class="content">
    <form id = "product_form">
        <div class="form-row">
            <label for="sku">SKU</label><input id="sku" name="sku">
        </div>
        <div class="form-row">
            <label for="name">Name</label><input id="name" name="name">
        </div>
        <div class="form-row">
            <label for="price">Price $</label><input type="number" id="price" name="price">
        </div>
        <div class="form-row">
            <label for="productType">Type Switcher</label>
            <select id="productType" name="category_id">
                <?php
                    $result = $mysqli -> query('select * from categories order by id');
                    if($result->num_rows){
                        while ($row = $result->fetch_object()) {
                            echo '<option value = "'.$row->id.'">'.$row->name.'</option>';
                        }
                    }    
                ?>
            </select>
        </div>
        <?php
            $result = $mysqli -> query('select * from category_properties order by id');
            if($result->num_rows){
                while ($row = $result->fetch_object()) {
                    echo '<div class="form-row hidden" data-category_id="' . $row->category_id . '"><label for="cat_' . $row->code . '">' . $row->label . '</label><input type="number" id="cat_' . $row->code . '" name="cat_' . $row->code . '"></div>';
                }
            }    
        ?>
    </form>
</div>  
<?php require_once "blocks/footer.php" ?>


<script src="/static/AddProduct.js"></script>


