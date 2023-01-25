<?php
require_once "../code/start_connection.php";


class SaveSku{
    private $mysqli;
    private array $errors;
    private bool $error;
    private int $category_id;
    private string $sku;
    private string $name;
    private float $price;
    private array $properties;
    
    function __construct() {
        global $mysqli;
        $this->errors = [];
        $this->errors['not_null'] = [];
        $this->error=false;
        $this->mysqli=$mysqli;        
    }
    public function set_sku(string $sku){
        if ($sku) {
            $this->sku = $sku;
            $this->check_sku_exist();
        } else {
            $this->error = true;
            $this->errors['not_null']['sku']=1;
        }
    }
    public function set_name(string $name){
        if ($name) {
            $this->name = $name;
        } else {
            $this->error = true;
            $this->errors['not_null']['name']=1;
        }
    }
    public function set_price(int $price){
        if ($price) {
            $this->price = $price;
        } else {
            $this->error = true;
            $this->errors['not_null']['price']=1;
        }
    }
    public function set_category_id(int $category_id){
        if ($category_id) {
            $this->category_id = $category_id;
        } else {
            $this->error = true;
            $this->errors['not_null']['category_id']=1;
        }
    }
    public function set_properties(array $properties){
        $this->properties = [];
        foreach ($properties as $key => $value) {
            if ($value) {
                $this->properties[$key] = $value;
            }
        }
    }
    private function check_sku_exist(){
        $result = $this->mysqli->prepare("select * from skus where sku = ?");
        $result->bind_param("s", $this->sku );
        $result->execute();
        $result->store_result();
        if ($result->num_rows) {
            $this->errors['sku_exist'] = 1;
            $this->error = true;
        }
    }
    function save_sku(){
        $result = $this->mysqli->prepare("insert into skus (category_id, sku, name, price) values(?,?,?,?)");
        $result->bind_param("ssss", $this->category_id, $this->sku, $this->name, $this->price);
        $result->execute();
        $result->store_result();
    }
    function save_sku_params(){
        foreach ($this->properties as $key => $value) {
            $result = $this->mysqli->prepare("
                insert into sku_params (sku_id, category_property_id, value) 
                select
                    ( select id from skus where sku = ? ), 
                    id, 
                    ?
                from
                    category_properties
                where 
                    category_id = ? and 
                    code = ?
                ");
            $result->bind_param("ssss", $this->sku, $value, $this->category_id, $key);
            $result->execute();
            $result->store_result();
        }
    }
    public function save(){
        if ($this->error){
            echo json_encode($this->errors);
            exit();
        }
        $this->save_sku();
        $this->save_sku_params();
        echo '{"ok":1}';
    }
};

$sku = new SaveSku();
$sku->set_sku($_POST['sku']);
$sku->set_name($_POST['name']);
$sku->set_price($_POST['price']);
$sku->set_category_id($_POST['category_id']);
$sku->set_properties($_POST['cat_properties']);
$sku->save();


require_once "../code/end_connection.php";

?>