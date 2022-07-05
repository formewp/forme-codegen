<?php
declare(strict_types=1);

namespace Forme\CodeGen\Builders;

use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;

final class FieldEnumBuilder
{
    public function __construct(private ClassBuilder $classBuilder, private Parser $parser, private Standard $printer)
    {
    }

    public function build(array $args): array
    {
        // generate the blank acf enum class using class builder
        $classData = $this->classBuilder->build('field-enum', $args['name']);
        // load the content into php parser
        $ast = $this->parser->parse($classData['content']);
        // $ast[1]->stmts[1]->stmts // class declaration
        // $ast[1]->stmts[1]->stmts[0] fieldNames property

        // fieldnames is an array of acf keys and acf names/values which we generate from args['fields']
        foreach ($args['fields'] as $field) {
            $ast[1]->stmts[1]->stmts[0]->props[0]->default->items[] = new ArrayItem(new String_($field['name']), new String_($field['key']));
        }

        // $ast[1]->stmts[1]->stmts[1] values method
        // values method returns an array of enum names and acf keys which we generate from args['enum_names']
        foreach ($args['enum_names'] as $acfKey => $enumName) {
            $ast[1]->stmts[1]->stmts[1]->stmts[0]->expr->items[] = new ArrayItem(new String_($acfKey), new String_($enumName));
        }
        // $ast[1]->stmts[1]->stmts[2] labels method
        // labels method returns an array of enum names and acf labels which we generate from args['enum_names'] and args['fields']
        foreach ($args['fields'] as $field) {
            $ast[1]->stmts[1]->stmts[2]->stmts[0]->expr->items[] = new ArrayItem(new String_($field['label']), new String_($args['enum_names'][$field['key']]));
        }
        // pretty print it back to the class data
        $classData['content'] = $this->printer->prettyPrintFile($ast);

        return $classData;
    }
}
