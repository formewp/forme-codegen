<?php
declare(strict_types=1);

namespace Forme\CodeGen\Builders;

use Forme\CodeGen\Utils\Resolvers\Resolver;
use Forme\CodeGen\Visitors\FieldGroupReplaceVisitor;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;

final class EnumFieldGroupBuilder
{
    public function __construct(private Parser $parser, private NodeTraverser $traverser, private FieldGroupReplaceVisitor $visitor, private Standard $printer, private Resolver $resolver, private PsrPrinter $nettePrinter)
    {
        $this->traverser->addVisitor($this->visitor);
    }

    public function build(array $args): array
    {
        // load up the original field group class file into php parser
        $ast= $this->parser->parse(file_get_contents($args['file']));
        // use the field group replace visitor to replace the field name, key and label string values with the relevant enum values
        $this->visitor->set($args);
        $ast      = $this->traverser->traverse($ast);
        $contents = $this->printer->prettyPrintFile($ast);
        // pass it through nette, being careful to preserve namespace and use statements
        $class = ClassType::fromCode($contents);
        $class->setFinal();
        $file          = new PhpFile();
        $file->addComment('This file has been auto-amended.');
        $file->setStrictTypes(); // adds declare(strict_types=1)

        $namespace     = $file->addNamespace($this->resolver->classFile()->getNameSpace($args['file']));
        $useStatements = $this->resolver->classReflection()->getUseStatements($args['class']);
        foreach ($useStatements as $alias => $use) {
            $namespace->addUse($use, $alias);
        }
        $namespace->addUse($args['enum_file']['class']->getNamespace()->getName() . '\\' . $args['enum_file']['class']->getName(), $args['enum_file']['class']->getName());
        $namespace->add($class);

        // to generate PHP code use the printer
        $contents = $this->nettePrinter->printFile($file);

        // add a use statement for the enum class
        // save it back to the original file
        return [
            'content'  => $contents,
            'name'     => $args['file'],
            'class'    => array_values($namespace->getClasses())[0],
        ];
    }
}
