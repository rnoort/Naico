<?php
    include "naico.php";
    $db = new Naico(["hostname" => "localhost", "username" => "root", "password" => "root", "database" => "testing"]);

    var_dump($db->set("someTable", "someKey", "someValue")); // Returns true
    var_dump($db->get("someTable ", "someKey")); // Returns "someValue"
    var_dump($db->set("someTable", "someKey", "aWeirdValue")); // Returns true
    var_dump($db->get("someTable", "someKey")); // Returns "aWeirdValue"

?>
