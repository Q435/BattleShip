<!DOCTYPE html>
<html>
<body>

<?php

$ship = "00";
$ship[1] = "1";

$result = stripos("$ship","0");
if ($result === false) {
    echo "error | " . $ship;
} else {
    echo $result;
}

?>

</body>
</html>
