<?php
declare(strict_types=1);

namespace Forme\CodeGen\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitorAbstract;

final class FieldGroupReplaceVisitor extends NodeVisitorAbstract
{
    private array $args;

    public function enterNode(Node $node)
    {
        // replace the field name, key and label string values with the relevant enum values
        if ($node instanceof Expression && isset($node->expr->name)) {
            if ($node->expr->name->toString() === 'acf_add_local_field_group') {
                $argumentNode   = $node->expr->args[0]->value;
                $isTarget       = (bool) array_filter($argumentNode->items, function ($item) {
                    return $item->key->value === 'key' && $item->value->value === $this->args['group'];
                });
                if ($isTarget) {
                    // cycle through the arguments and find 'fields'
                    foreach ($argumentNode->items as $key => $item) {
                        if ($item->key->value === 'fields') {
                            // for each field, get the name, key and label and replace with the enum expression
                            $fields = $item->value->items;
                            foreach ($fields as $fieldKey => $field) {
                                $keyNode    = $field->value->items[0]->value;
                                $labelNode  = $field->value->items[1]->value;
                                $nameNode   = $field->value->items[2]->value;
                                if ($nameNode instanceof Node\Scalar\String_ && $labelNode instanceof Node\Scalar\String_ && $keyNode instanceof Node\Scalar\String_) {
                                    $argumentNode->items[$key]->value->items[$fieldKey]->value->items[0]->value = $this->createEnumNode($keyNode->value, 'key');
                                    $argumentNode->items[$key]->value->items[$fieldKey]->value->items[1]->value = $this->createEnumNode($keyNode->value, 'label');
                                    $argumentNode->items[$key]->value->items[$fieldKey]->value->items[2]->value = $this->createEnumNode($keyNode->value, 'name');
                                }
                            }
                            // replace the fields with the new ones
                        }
                    }
                    $node->expr->args[0]->value = $argumentNode;

                    return $node;
                }
            }
        }

        return null;
    }

    public function set(array $args): void
    {
        $this->args = $args;
    }

    private function createEnumNode(string $fieldKey, string $property): Node\Expr\PropertyFetch
    {
        $method = $this->args['enum_names'][$fieldKey];
        $class  = $this->args['enum_file']['class']->getNamespace()->getName() . '\\' . $this->args['enum_file']['class']->getName();

        $call = new Node\Expr\StaticCall(
            new Node\Name\FullyQualified($class),
            $method,
            []
        );

        return new Node\Expr\PropertyFetch($call, $property);
    }
}
