<?php
declare(strict_types=1);

namespace Forme\CodeGen\Builders;

use Forme\CodeGen\Constants\Placeholders;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use Jawira\CaseConverter\Convert;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;

class MigrationBuilder
{
    /**
     * @var class-string<\Forme\CodeGen\Source\Migrations\Migration>|string
     */
    private const SOURCE_CLASS     = "Forme\CodeGen\Source\Migrations\Migration";

    /**
     * @var string
     */
    private const TARGET_DIRECTORY = 'app/Database/Migrations';

    public function __construct(private PsrPrinter $printer, private Resolver $resolver)
    {
    }

    public function build(string $className): array
    {
        // create file
        $file = new PhpFile();
        $file->addComment('This boilerplate file is auto-generated.');
        $file->setStrictTypes(); // adds declare(strict_types=1)

        // we add the namespace but we need to remove it later
        $namespace = $file->addNamespace(Placeholders::NAMESPACE);
        // we have to add the uses separately as the nette library won't read them from the file
        $useStatements = $this->resolver->classReflection()->getUseStatementsFromLoadedClass(self::SOURCE_CLASS);
        foreach ($useStatements as $alias => $use) {
            $namespace->addUse($use, $alias);
        }

        $class = ClassType::from(self::SOURCE_CLASS, withBodies: true);
        // sort out the class name
        // add the prefix onto the class name
        $classNameConversion = new Convert($className);
        $className           = $classNameConversion->toPascal();
        $class->setName($className);

        $namespace->add($class);

        // generate PHP code using the printer
        $fileContents = $this->printer->printFile($file);
        // remove the namespace
        $fileContents = str_replace('namespace ' . Placeholders::NAMESPACE . ";\n\n", '', $fileContents);

        // sort out the filename
        $timeString = gmdate('YmdHis');
        $fileName   = $timeString . '_' . $classNameConversion->toSnake();
        $fileName   = self::TARGET_DIRECTORY . '/' . $fileName . '.php';

        return ['name' => $fileName, 'content' => $fileContents];
    }
}
