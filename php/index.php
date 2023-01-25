<?php
$title = "Product List";
$head_buttons = [
    [ "id"=>"add_sku", "name" => "ADD" ],
    [ "id"=>"delete-product-btn", "name" => "MASS DELETE" ]
];
require_once "blocks/header.php";

class Sku{

    private int $id;
    private string $name;
    private string $sku;
    private float $price;
    private string $params;
    public function set_id(int $id){
        $this->id = $id;
    }
    public function set_name(string $name){
        $this->name = $name;
    }
    public function set_sku(string $sku){
        $this->sku = $sku;
    }
    public function set_price(float $price){
        $this->price = $price;
    }
    public function set_params(string $jparams, string $property_template_string){
        $data_params = json_decode($jparams);
        $params = $property_template_string;     
        foreach ($data_params as $code => $value) { 
            $params = str_replace("{{".$code."}}","$value",$params);
        }   
        $this->params = $params;    
    }
    public function draw(){
        echo  "<div class = 'sku-box'>
                <input class='delete-checkbox' type='checkbox' date-sku_id='$this->id'>
                <p>$this->sku</p>
                <p>$this->name</p>
                <p>$this->price $</p>
                <p>$this->params</p>
            </div>";
    }
}
class Data extends Base{
   
    private array $skus;
    function __construct(){
        $this->connect_base();
    }
    public function get_skus(){
        $this->skus = [];
        $result = $this->mysqli -> query("
            select
                s.id,
                s.sku,
                s.name,
                s.price,
                sp.jparams,
                c.property_template_string
            from
                skus as s
                left join categories as c on c.id = s.category_id
                left join (
                    SELECT
                        sp.sku_id,
                        JSON_OBJECTAGG(cp.code,sp.value) as jparams
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
                $sku = new Sku();
                $sku->set_id($row->id);
                $sku->set_name($row->name);
                $sku->set_sku($row->sku);
                $sku->set_price($row->price);
                $sku->set_params($row->jparams, $row->property_template_string);
                $this->skus[$row->id] = $sku;
            }
        }    
    }
    public function draw_skus(){
        foreach($this->skus as $id => $sku){
            $sku->draw();
        }
    }
}

$data = new Data();
$data->get_skus();
?>

<div class="content">
    <div class="skus">
        <?php $data->draw_skus() ?>
    </div>
</div>  
<?php require_once "blocks/footer.php" ?>


<script src="/static/IndexPage.js"></script>