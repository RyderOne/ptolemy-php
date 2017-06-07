<?php

use Animal\Bear;

$bear1 = (new Bear())
    ->setSize(2)
;

$bear2 = (new Bear())
    ->setSize(3)
;

$bear1->fight($bear2);
