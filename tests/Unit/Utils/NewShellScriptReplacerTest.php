<?php

use Forme\CodeGen\Utils\NewShellScriptReplacer;

beforeEach(function () {
    $this->replacer = new NewShellScriptReplacer();
});

test('replaces placeholders in shell script', function () {
    // placeholders: project-type, project-name, ProjectName, github.com (if args[host] is set), VendorName, ViewEngine
    $testString = 'project-type: project-name: ProjectName: github.com: VendorName: ViewEngine';

    $args = [
        'name'   => 'Foo_Bar',
        'vendor' => 'Bar Foo',
        'type'   => 'Quz',
        'host'   => 'example.com',
        'view'   => 'Blade',
    ];

    $expected = 'Quz: foo-bar: FooBar: example.com: BarFoo: Blade';

    expect($this->replacer->replace($testString, $args))->toBe($expected);
});
