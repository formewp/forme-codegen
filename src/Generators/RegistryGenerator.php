<?php
declare(strict_types=1);

namespace Forme\CodeGen\Generators;

use Forme\CodeGen\Utils\Resolvers\Resolver;

final class RegistryGenerator implements GeneratorInterface
{
    /** @var ClassGenerator */
    private $classGenerator;

    /** @var HookGenerator */
    private $hookGenerator;

    /** @var Resolver */
    private $resolver;

    public function __construct(ClassGenerator $classGenerator, HookGenerator $hookGenerator, Resolver $resolver)
    {
        $this->classGenerator     = $classGenerator;
        $this->hookGenerator      = $hookGenerator;
        $this->resolver           = $resolver;
    }

    public function generate(array $args): array
    {
        $messages        = $this->classGenerator->generate($args);
        $messageArray    = explode(' ', $messages[0]);
        $mainClassFile   = array_pop($messageArray);
        // also create a hooks entry
        $hookArgs = [
                    'type'  => 'action',
                    'class' => $this->resolver->classFile()->getClassAndNamespace($mainClassFile),
                    'name'  => 'init',
                ];
        $messages = array_merge($messages, $this->hookGenerator->generate($hookArgs));

        return $messages;
    }
}
