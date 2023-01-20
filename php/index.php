<?php
$title = "Product List";
$head_buttons = [
    [ "id"=>"add_sku", "name" => "ADD" ],
    [ "id"=>"delete-product-btn", "name" => "MASS DELETE" ]
];
    require_once "blocks/header.php" 
?>
<div class="content">
    <div class="skus">
        <?php
            $result = $mysqli -> query("

            select
                s.id,
                s.sku,
                s.name,
                s.price,
                sp.params
            from
                skus as s
                left join (
                    SELECT
                        sp.sku_id,
                        case 
                            when max(cp.code)='size' then CONCAT('Size: ', max(sp.value), ' MB')
                            when max(cp.code)='weight' then CONCAT('Weight: ', max(sp.value), ' KG')
                            else CONCAT(
                                'Dimension: ', 
                                max(case when cp.code='height' then sp.value end), 'x', 
                                max(case when cp.code='width' then sp.value end), 'x', 
                                max(case when cp.code='length' then sp.value end),
                                ' CM'
                            )
                        end params
                    from
                        sku_params as sp
                        left join category_properties as cp on cp.id = sp.category_property_id
                    group BY
                        sp.sku_id
                ) as sp on sp.sku_id = s.id
                order by s.id
            ");
            if($result->num_rows){
                while ($row = $result->fetch_object()) {
                    echo "<div class = 'sku-box'>
                        <input class='delete-checkbox' type='checkbox' date-sku_id='$row->id'>
                        <p>$row->sku</p>
                        <p>$row->name</p>
                        <p>$row->price $</p>
                        <p>$row->params</p>
                    </div>";
                    // echo '<div class="form-row hidden" data-category_id="' . $row->category_id . '"><label for="cat_' . $row->code . '">' . $row->label . '</label><input type="number" id="cat_' . $row->code . '" name="cat_' . $row->code . '"></div>';
                }
            }
        ?>
    </div>
</div>  
<?php require_once "blocks/footer.php" ?>


<script src="/static/IndexPage.js"></script>