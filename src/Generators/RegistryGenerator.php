<?php
declare(strict_types=1);

namespace Forme\CodeGen\Generators;

use Forme\CodeGen\Utils\Resolvers\Resolver;

final class RegistryGenerator implements GeneratorInterface
{
    public function __construct(private ClassGenerator $classGenerator, private HookGenerator $hookGenerator, private Resolver $resolver)
    {
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

        return array_merge($messages, $this->hookGenerator->generate($hookArgs));
    }
}
