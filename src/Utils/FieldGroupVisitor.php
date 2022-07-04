<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitorAbstract;

final class FieldGroupVisitor extends NodeVisitorAbstract
{
    public array $result = [];

    public function enterNode(Node $node)
    {
        if ($node instanceof Expression) {
            if ($node->expr->name->parts[0] === 'acf_add_local_field_group') {
                $argumentNode   = $node->expr->args[0]->value;
                // cycle through the arguments and find 'key', 'title' and 'fields'
                foreach ($argumentNode->items as $item) {
                    if ($item->key->value === 'key') {
                        $key = $item->value->value;
                    } elseif ($item->key->value === 'title') {
                        $title = $item->value->value;
                    } elseif ($item->key->value === 'fields') {
                        // for each field, we need to get the name, key and label
                        $fields = [];
                        foreach ($item->value->items as $field) {
                            $fields[] = [
                                'name'  => $field->value->items[0]->value->value,
                                'key'   => $field->value->items[1]->value->value,
                                'label' => $field->value->items[2]->value->value,
                            ];
                        }
                    }
                }
                $this->result[] = [
                    'key'    => $key,
                    'title'  => $title,
                    'fields' => $fields,
                    'node'   => $node,
                ];
            }
        }

        return null;
    }
}
