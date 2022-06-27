<?php
use Forme\CodeGen\Utils\Resolvers\ClassFileResolver;
use Forme\CodeGen\Utils\Resolvers\NameSpaceResolver;
use League\Flysystem\Filesystem;

test('get returns the project name space if set in the Main.php class', function () {
    $fileSystem        = Mockery::mock(Filesystem::class)->shouldReceive('fileExists')->with('/app/Main.php')->andReturn(true)->getMock();
    $resolver          = Mockery::mock(ClassFileResolver::class)->shouldReceive('getNameSpace')->with('/app/Main.php')->andReturn('Acme\\TestNameSpace')->getMock();
    $nameSpaceResolver = new NameSpaceResolver($fileSystem, $resolver);
    expect($nameSpaceResolver->get())->toBe('Acme\\TestNameSpace');
});

test('get returns the default name space if no Main.php class is found', function () {
    $fileSystem        = Mockery::mock(Filesystem::class)->shouldReceive('fileExists')->with('/app/Main.php')->andReturn(false)->getMock();
    $resolver          = Mockery::mock(ClassFileResolver::class);
    $nameSpaceResolver = new NameSpaceResolver($fileSystem, $resolver);
    expect($nameSpaceResolver->get())->toBe('Foobar\\ProjectNameSpace');
});

test('getPlaceHolder returns the namespace placeholder', function () {
    $nameSpaceResolver = new NameSpaceResolver(Mockery::mock(Filesystem::class), Mockery::mock(ClassFileResolver::class));
    expect($nameSpaceResolver->getPlaceHolder())->toBe(NameSpaceResolver::PLACEHOLDER);
});
