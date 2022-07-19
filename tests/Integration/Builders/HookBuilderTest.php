<?php

use Consolidation\Comments\Comments;
use Forme\CodeGen\Builders\HookBuilder;

beforeEach(function () {
    $this->commentManager = Mockery::mock(Comments::class);
    $this->commentManager->shouldReceive('collect');
    $this->commentManager->shouldReceive('inject')->andReturnArg(0);
    $this->hookBuilder    = new HookBuilder($this->commentManager);
});

test('adds expected hook to yaml with priority and arguments set to default values', function () {
    $originalContents = file_get_contents(__DIR__ . '/../../../stubs/tests/good-yaml.stub');
    $hookToAdd        = [
        'type'         => 'action',
        'name'         => 'foo_action',
        'class'        => 'stdClass',
        'priority'     => 10,
        'method'       => 'do_stuff',
        'arguments'    => 1,
    ];
    $expectedContents = file_get_contents(__DIR__ . '/../../../stubs/tests/yaml-with-hook.stub');
    $result           = $this->hookBuilder->build($originalContents, $hookToAdd);
    expect($result)->toEqual($expectedContents);
});

test('adds expected hook to yaml with priority and arguments set to empty strings', function () {
    $originalContents = file_get_contents(__DIR__ . '/../../../stubs/tests/good-yaml.stub');
    $hookToAdd        = [
        'type'         => 'action',
        'name'         => 'foo_action',
        'class'        => 'stdClass',
        'priority'     => '',
        'method'       => 'do_stuff',
        'arguments'    => '',
    ];
    $expectedContents = file_get_contents(__DIR__ . '/../../../stubs/tests/yaml-with-hook.stub');
    $result           = $this->hookBuilder->build($originalContents, $hookToAdd);
    expect($result)->toEqual($expectedContents);
});

test('adds expected hook to yaml with priority and arguments not set', function () {
    $originalContents = file_get_contents(__DIR__ . '/../../../stubs/tests/good-yaml.stub');
    $hookToAdd        = [
        'type'         => 'action',
        'name'         => 'foo_action',
        'class'        => 'stdClass',
        'method'       => 'do_stuff',
    ];
    $expectedContents = file_get_contents(__DIR__ . '/../../../stubs/tests/yaml-with-hook.stub');
    $result           = $this->hookBuilder->build($originalContents, $hookToAdd);
    expect($result)->toEqual($expectedContents);
});

test('adds expected hook to yaml with priority and arguments set to ad hoc values', function () {
    $originalContents = file_get_contents(__DIR__ . '/../../../stubs/tests/good-yaml.stub');
    $hookToAdd        = [
        'type'         => 'action',
        'name'         => 'foo_action',
        'class'        => 'stdClass',
        'priority'     => 100,
        'method'       => 'do_stuff',
        'arguments'    => 3,
    ];
    $expectedContents = file_get_contents(__DIR__ . '/../../../stubs/tests/yaml-with-hook-adhoc-values.stub');
    $result           = $this->hookBuilder->build($originalContents, $hookToAdd);
    expect($result)->toEqual($expectedContents);
});
