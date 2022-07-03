<?php

use Forme\CodeGen\Utils\YamlFormattingFixer;

test('repairs weird yaml', function () {
    $weirdYaml = file_get_contents(__DIR__ . '/../../../stubs/tests/weird-yaml.stub');
    $goodYaml  = file_get_contents(__DIR__ . '/../../../stubs/tests/good-yaml.stub');
    $result    = YamlFormattingFixer::repair($weirdYaml);
    expect($result)->toEqual($goodYaml);
});
