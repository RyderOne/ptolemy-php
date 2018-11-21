<?php

use Animal\Bear;
use Animal\Lion;

$bear1 = (new Bear())
    ->setSize(2)
;

$lion1 = (new Lion())
    ->setSize(3)
;

$bear1->fight($lion1);
$lion1->fight($bear1);
$lion1->fight($bear1);
