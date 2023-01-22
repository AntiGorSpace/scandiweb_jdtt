<?php
require_once "../code/start_connection.php";


class SaveSku{
    public array $errors;
    public bool $error;
    function __construct() {
        $this->errors = [];
        $this->error=false;
        $this->check_exist_code();
        $this->check_data();
        if ($this->error){
            echo json_encode($this->errors);
            exit();
        }
        $this->save_sku();
        $this->save_sku_params();
    }
    function check_exist_code(){
        global $mysqli;
        $result = $mysqli->prepare("select * from skus where sku = ?");
        $result->bind_param("s", $_POST['sku'] );
        $result->execute();
        $result->store_result();
        if ($result->num_rows) {
            $this->errors['sku_exist'] = 1;
            $this->error = true;
        }
    }
    function check_data(){
        foreach(['category_id', 'sku', 'price'] as $fname){
            $this->errors['not_null'] = [];
            if (!$_POST[$fname] ){
                $this->errors['not_null'][$fname]=1;
            }
        }
    }
    function save_sku(){
        global $mysqli;
        $result = $mysqli->prepare("insert into skus (category_id, sku, name, price) values(?,?,?,?)");
        $result->bind_param("ssss", $_POST['category_id'], $_POST['sku'], $_POST['name'], $_POST['price']);
        $result->execute();
        $result->store_result();
    }
    function save_sku_params(){
        global $mysqli;
        foreach ($_POST['cat_properties'] as $key => $value) {
            $result = $mysqli->prepare("
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
            $result->bind_param("ssss", $_POST['sku'], $value, $_POST['category_id'], $key);
            $result->execute();
            $result->store_result();
        }
    }
};

$obj = new SaveSku();
echo '{"ok":1}';


require_once "../code/end_connection.php";

?>