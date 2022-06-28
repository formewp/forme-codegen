<?php

use Forme\CodeGen\Constants\Files;
use Forme\CodeGen\Utils\Resolvers\ClassFileResolver;
use Forme\CodeGen\Utils\Resolvers\NameSpaceResolver;
use League\Flysystem\Filesystem;

beforeEach(function () {
    $this->fileSystem        = Mockery::mock(Filesystem::class);
    $this->classFileResolver = Mockery::mock(ClassFileResolver::class);
});

test('get returns the project name space if set in the Main.php class', function () {
    $fileSystem        = $this->fileSystem->shouldReceive('fileExists')->with(Files::MAIN_CLASS)->andReturn(true)->getMock();
    $resolver          = $this->classFileResolver->shouldReceive('getNameSpace')->with(Files::MAIN_CLASS)->andReturn('Acme\\TestNameSpace')->getMock();
    $nameSpaceResolver = new NameSpaceResolver($fileSystem, $resolver);
    expect($nameSpaceResolver->get())->toBe('Acme\\TestNameSpace');
});

test('get returns the default name space if no Main.php class is found', function () {
    $fileSystem        = $this->fileSystem->shouldReceive('fileExists')->with(Files::MAIN_CLASS)->andReturn(false)->getMock();
    $nameSpaceResolver = new NameSpaceResolver($fileSystem, $this->classFileResolver);
    expect($nameSpaceResolver->get())->toBe(NameSpaceResolver::DEFAULT_NAMESPACE);
});
