<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter\Standard;

final class FieldGroupVisitor extends NodeVisitorAbstract
{
    public array $result = [];

    public function __construct(private Standard $prettyPrinter)
    {
        $this->prettyPrinter = $prettyPrinter;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Expression) {
            if ($node->expr->name->parts[0] === 'acf_add_local_field_group') {
                $argumentNode   = $node->expr->args[0]->value;
                $this->result[] = $this->prettyPrinter->prettyPrintExpr($argumentNode);
            }
        }

        return null;
    }
}
