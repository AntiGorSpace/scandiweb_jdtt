<?php

require_once "../code/start_connection.php";


foreach ($_POST["ids"] as $id) {
    $result = $mysqli->prepare("delete from skus where id = ?");
    $result->bind_param("s", $id);
    $result->execute();
    $result->store_result();
}

echo '{"ok":1}';

require_once "../code/end_connection.php";

?>