<?php

require_once "../code/start_connection.php";


class DeleteSku extends Base{
    private array $sku_ids;

    public function set_sku_ids(array $sku_ids){
        $this->sku_ids = [];
        foreach ($sku_ids as $id) {
            if ($id) {
                array_push($this->sku_ids, $id);
            }
        }
    }
    public function delete(){
        foreach ($this->sku_ids as $id) {
            $result = $this->mysqli->prepare("delete from skus where id = ?");
            $result->bind_param("s", $id);
            $result->execute();
            $result->store_result();
        }
        echo '{"ok":1}';
    }
};

$sku = new DeleteSku();
$sku->set_sku_ids($_POST['ids']);
$sku->delete();
$sku->close();



require_once "../code/end_connection.php";

?>