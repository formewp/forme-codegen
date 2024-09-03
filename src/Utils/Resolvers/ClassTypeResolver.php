<?php

declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Constants\Files;

final class ClassTypeResolver
{
    /**
     * @var array<string, string>
     */
    private const MAP = [
        'controller'           => 'Controllers\Controller',
        'template-controller'  => 'Controllers\TemplateController',
        'job'                  => 'Jobs\Job',
        'field'                => 'Fields\FieldGroup',
        'post-type'            => 'Models\PostType',
        'model'                => 'Models\Model',
        'transformer'          => 'Transformers\Transformer',
        'registry'             => 'Registry\Registry',
        'service'              => 'Services\Service',
        'middleware'           => 'Middleware\Middleware',
        'field-enum'           => 'Enums\Field',
        'command'              => 'Commands\Wrangle\Command',
    ];

    /**
     * @var string
     */
    private const NAME_SPACE              = 'Forme\CodeGen\Source';

    /**
     * @var string
     */
    private const TEMPLATE_CONTROLLER_DIR = 'template-controllers';

    public function getSourceClass(string $type): string
    {
        return self::NAME_SPACE . '\\' . self::MAP[$type];
    }

    public function getNamespaceBase(string $type): string
    {
        $classEnd = self::MAP[$type];

        $exploded = explode('\\', $classEnd);
        array_pop($exploded);

        return implode('\\', $exploded);
    }

    public function getTargetDirectory(string $type): string
    {
        if ($type === 'template-controller') {
            return self::TEMPLATE_CONTROLLER_DIR;
        } else {
            $directoryEnd = str_replace('\\', '/', $this->getNamespaceBase($type));

            return Files::APP_DIR . '/' . $directoryEnd;
        }
    }
}
