<?php

use Forme\CodeGen\Flysystem\TempdirAdapter as Tempdir;
use League\Flysystem\Filesystem;

beforeEach(function () {
    $this->adapter    = new Tempdir();
    $this->filesystem = new Filesystem($this->adapter);
});

it('creates a temporary directory', function () {
    $dir = $this->adapter->getPath();
    expect(is_dir($dir))->toBeTrue();
});

it('removes temporary directory', function () {
    $dir = $this->adapter->getPath();

    unset($this->filesystem, $this->adapter);
    expect(file_exists($dir))->toBeFalse();
});
