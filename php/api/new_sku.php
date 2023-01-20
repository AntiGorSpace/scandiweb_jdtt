<?php
require_once "../code/start_connection.php";


class SaveSku{
    function __construct() {
        $this->check_exist_code();
        $this->check_data();
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
            echo '{"error":"sku code alredy exist"}';
            exit();
        }
    }
    function check_data(){
        foreach(['category_id', 'sku', 'price'] as $fname){
            if (!$_POST[$fname] ){
                echo '{"error":"'.$fname.' cant be null"}';
                exit();
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
        foreach ($_POST as $key => $value) {
            $code = str_replace("cat_", "", $key);
            if ($code == $key) {
                continue;
            }
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
            $result->bind_param("ssss", $_POST['sku'], $value, $_POST['category_id'], $code);
            $result->execute();
            $result->store_result();
        }
    }
};

$obj = new SaveSku();
echo '{"ok":1}';


require_once "../code/end_connection.php";

?>