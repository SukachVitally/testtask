<?php

passthru(sprintf(
    'php bin/console doctrine:mongodb:fixtures:load --fixtures=src/AppBundle/DataFixtures --env=test -n'
));

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

$fs = new Filesystem();
$fs->remove(['web/testvideo']);
require __DIR__.'/autoload.php';

