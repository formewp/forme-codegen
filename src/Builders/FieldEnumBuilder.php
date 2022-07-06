<?php
declare(strict_types=1);

namespace Forme\CodeGen\Builders;

use Forme\CodeGen\Constants\Placeholders;
use Forme\CodeGen\Utils\PlaceholderReplacer;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;

final class FieldEnumBuilder
{
    public function __construct(private ClassBuilder $classBuilder, private Parser $parser, private Standard $printer, private Printer $nettePrinter, private Resolver $resolver, private PlaceholderReplacer $replacer)
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

        $class = ClassType::fromCode($classData['content']);
        $class->setFinal();

        // get the comments, namespace and use statements back from the original class

        $sourceClass   = $this->resolver->classType()->getSourceClass('field-enum');
        $namespaceBase = $this->resolver->classType()->getNamespaceBase('field-enum');
        $file          = new PhpFile();
        $file->addComment('This boilerplate file is auto-generated.');
        $file->setStrictTypes(); // adds declare(strict_types=1)

        $namespace     = $file->addNamespace(Placeholders::NAMESPACE . '\\' . $namespaceBase);
        $useStatements = $this->resolver->classReflection()->getUseStatements($sourceClass);
        foreach ($useStatements as $alias => $use) {
            $namespace->addUse($use, $alias);
        }
        $namespace->add($class);

        // to generate PHP code use the printer
        $fileContents = $this->nettePrinter->printFile($file);
        $fileContents = $this->replacer->process($fileContents, 'field-enum', $args['name']);

        return [
            'content' => $fileContents,
            'name'    => $classData['name'],
        ];
    }
}
