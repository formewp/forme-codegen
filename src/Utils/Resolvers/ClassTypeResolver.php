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
        'translator'           => 'Translators\Translator',
        'registry'             => 'Registry\Registry',
        'service'              => 'Services\Service',
        'middleware'           => 'Middleware\Middleware',
        'field-enum'           => 'Enums\Field',
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

        return explode('\\', $classEnd)[0];
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
