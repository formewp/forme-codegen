<?php

use Forme\CodeGen\Flysystem\Tempdir;

it('creates a temporary directory', function () {
    $fs  = new Tempdir();
    $dir = $fs->getPath();
    expect(is_dir($dir))->toBeTrue();
});

it('removes temporary directory', function () {
    $fs  = new Tempdir();
    $dir = $fs->getPath();
    unset($fs);

    expect(file_exists($dir))->toBeFalse();
});
