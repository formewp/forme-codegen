<?php

use Carbon\Carbon;
use Forme\CodeGen\Builders\MigrationBuilder;
use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    $container     = bootstrap();
    $this->builder = $container->get(MigrationBuilder::class);
});

test('builds expected migration file', function () {
    testTime()->freeze();
    $time   = Carbon::now()->format('YmdHis');
    $result = $this->builder->build('add_foobar_table');
    // file_put_contents(__DIR__ . '/../../../stubs/tests/TestMigration.stub', $result['content']);
    $expectedContent = file_get_contents(__DIR__ . '/../../../stubs/tests/TestMigration.stub');
    expect($result['content'])->toEqual($expectedContent);
    expect($result['name'])->toEqual('app/Database/Migrations/' . $time . '_add_foobar_table.php');
});
