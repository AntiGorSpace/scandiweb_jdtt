<?php
$title = "Product Add";
$head_buttons = [
    [ "id"=>"save", "name" => "Save" ],
    [ "id"=>"cansel", "name" => "Cansel" ]
];
require_once "blocks/header.php";

class Category{
    private int $id;
    private string $name;
    public function set_id(int $id){
        $this->id = $id;
    }
    public function set_name(string $name){
        $this->name = $name;
    }
    public function draw(){
        echo '<option value = "'.$this->id.'">'.$this->name.'</option>';
    }
}
class CategoryProperty{
    private string $code;
    private string $label;
    private int $category_id;
    public function set_code(string $code){
        $this->code = $code;
    }
    public function set_label(string $label){
        $this->label = $label;
    }
    public function set_category_id(int $category_id){
        $this->category_id = $category_id;
    }
    public function draw(){
        echo "<div class='form-row hidden' data-category_id='$this->category_id'>
                <label for='$this->code'>$this->label</label>
                <input type='number' id='$this->code' name='cat_$this->code'>
            </div>";
    }
}
class Data extends Base{
    private array $categories;
    private array $category_properties;
    public function get_categories(){
        $this->categories = [];
        $result = $this->mysqli -> query('select * from categories order by id');
        if($result->num_rows){
            while ($row = $result->fetch_object()) {
                $category = new Category();
                $category->set_id($row->id);
                $category->set_name($row->name);
                $this->categories[$row->id] = $category;
            }
        }    
    }
    public function get_category_properties(){
        $this->category_properties = [];
        $result = $this->mysqli -> query('select * from category_properties order by id');
        if($result->num_rows){
            while ($row = $result->fetch_object()) {
                $property = new CategoryProperty();
                $property->set_code($row->code);
                $property->set_label($row->label);
                $property->set_category_id($row->category_id);
                $this->category_properties[$row->id] = $property;
            }
        }    
    }
    public function draw_categories(){
        foreach($this->categories as $id => $category){
            $category->draw();
        }
    }
    public function draw_category_properties(){
        foreach($this->category_properties as $code => $property){
            $property->draw();
        }
    }
}

$data = new Data();
$data->get_categories();
$data->get_category_properties();
$data->close();

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
                <?php $data->draw_categories() ?>
            </select>
        </div>
        <?php $data->draw_category_properties() ?>
    </form>
</div>  


<script src="/static/AddProduct.js"></script>


<?php require_once "blocks/footer.php" ?>