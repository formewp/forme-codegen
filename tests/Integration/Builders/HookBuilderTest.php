<?php

use Consolidation\Comments\Comments;
use Forme\CodeGen\Builders\HookBuilder;

beforeEach(function () {
    $this->commentManager = Mockery::mock(Comments::class);
    $this->commentManager->shouldReceive('collect');
    $this->commentManager->shouldReceive('inject')->andReturnArg(0);
    $this->hookBuilder    = new HookBuilder($this->commentManager);
});

test('adds a hook to a yaml config string', function () {
    $originalContents = file_get_contents(__DIR__ . '/../../../stubs/tests/good-yaml.stub');
    $hookToAdd        = [
        'type'         => 'action',
        'name'         => 'foo_action',
        'class'        => 'stdClass',
        'priority'     => 10,
        'method'       => 'do_stuff',
    ];
    $expectedContents = file_get_contents(__DIR__ . '/../../../stubs/tests/yaml-with-hook.stub');
    $result           = $this->hookBuilder->build($originalContents, $hookToAdd);
    expect($result)->toEqual($expectedContents);
});
