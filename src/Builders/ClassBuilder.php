<?php
declare(strict_types=1);

namespace Forme\CodeGen\Builders;

use Forme\CodeGen\Utils\PlaceholderReplacerInterface;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use Forme\Framework\Jobs\JobInterface;
use Forme\Framework\Jobs\Queueable;
use Forme\Framework\Models\CustomPostable;
use Jawira\CaseConverter\Convert;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;

class ClassBuilder
{
    public function __construct(private PsrPrinter $printer, private PlaceholderReplacerInterface $replacer, private Resolver $resolver)
    {
    }

    public function build(string $type, string $classPrefix, ?string $method = null): array
    {
        $namespacePlaceHolder = $this->resolver->nameSpace()->getPlaceHolder();

        $sourceClass   = $this->resolver->classType()->getSourceClass($type);
        $namespaceBase = $this->resolver->classType()->getNamespaceBase($type);
        $file          = new PhpFile();
        $file->addComment('This boilerplate file is auto-generated.');
        $file->setStrictTypes(); // adds declare(strict_types=1)

        $namespace = $file->addNamespace($namespacePlaceHolder . '\\' . $namespaceBase);
        // we do have to add the uses separately as the nette library won't read them from the file
        $useStatements = $this->resolver->classReflection()->getUseStatements($sourceClass);
        foreach ($useStatements as $alias => $use) {
            $namespace->addUse($use, $alias);
        }
        $class = ClassType::withBodiesFrom($sourceClass);
        // add the prefix onto the class name
        $classPrefixConversion = new Convert($classPrefix);
        $classPrefixPascal     = $classPrefixConversion->toPascal();
        // if this is a model, we don't add a suffix
        $classSuffix           = $type === 'model' ? '' : $class->getName();
        $className             = $classPrefixPascal . $classSuffix;
        // if this is a TemplateController, we need to change it to just Controller
        $className = str_replace('TemplateController', 'Controller', $className);
        $class->setName($className);
        // if this is a service class, let's add the method
        if ($type === 'service') {
            $class->addMethod($method);
        }
        // if this is a template controller class, let's add the template name
        if ($type === 'template-controller') {
            $templateNameConversion = new Convert($classPrefix);
            $templateName           = $templateNameConversion->toTitle();
            $file->addComment('Template Name: ' . $templateName);
        }
        // if this is a job, add the trait and interface
        if ($type === 'job') {
            $class->addImplement(JobInterface::class);
            $class->addTrait(Queueable::class);
        }

        // if this is a model, add the trait
        if ($type === 'model') {
            $class->addTrait(CustomPostable::class);
        }

        $namespace->add($class);

        // to generate PHP code use the printer
        $fileContents = $this->printer->printFile($file);
        // then we replace placeholders according to the file type
        $fileContents = $this->replacer->process($fileContents, $type, $classPrefix);

        // then let's create the new file in the relevant location - PSR4 ftw
        $targetDirectory     = $this->resolver->classType()->getTargetDirectory($type);
        $fileName            = $targetDirectory . '/' . $className . '.php';

        return ['name' => $fileName, 'content' => $fileContents];
    }
}
