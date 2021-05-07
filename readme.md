# Naico
> A simple mysqli wrapper written in PHP, made for simplicity.

### Set a key
```php
<?php
    include "naico.php"
    $db = new Naico(["hostname" => "localhost", "username" => "root", "password" => "root", "database" => "testing"]);

    // Set "someKey" to "someValue" in table "someTable"
    $db->set("someTable", "someKey", "someValue"); // Returns true

    // Set "settings" to ["transparency" => 0, "color" => "red"] in table "someTable"
    $db->set("someTable", "someKey", ["transparency" => 0, "color" => "red"]); // Returns true

>?
```

### Get a key
```php
<?php
    include "naico.php"
    $db = new Naico(["hostname" => "localhost", "username" => "root", "password" => "root", "database" => "testing"]);

    // Set "preference" to ["transparency" => 0, "color" => "red"] in table "someTable"
    $db->set("someTable", "preference", ["font" => "Verdana", "city" => "Amsterdam", "shop" => false]); // Returns true

    // Get "preference" from table "someTable"
    $db->get("someTable", "preference"); // Returns ["font" => "Verdana", "city" => "Amsterdam", "shop" => false]
>?
```

### Check if a table has a key
```php
<?php
    include "naico.php"
    $db = new Naico(["hostname" => "localhost", "username" => "root", "password" => "root", "database" => "testing"]);

    // Set "admin" to ["orderCount" => 0, "cash" => 10] in table "users"
    $db->set("users", "admin", ["orderCount" => 0, "cash" => 10); // Returns true

    // Check if "admin" exists in table "users"
    $db->has("users", "admin"); // Returns true

    // Check if "Roelof" exists in table "users"
    $db->has("users", "Roelof"); // Returns false
>?
```
