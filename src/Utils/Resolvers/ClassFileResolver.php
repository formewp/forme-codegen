<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use League\Flysystem\Filesystem;

final class ClassFileResolver
{
    public function __construct(private Filesystem $filesystem)
    {
    }

    /**
     * Extract the namespace from file contents.
     */
    public function getNameSpace(string $file): ?string
    {
        $fileContents = $this->filesystem->read($file);
        if (preg_match('#^namespace\s+(.+?);$#sm', $fileContents, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * Extract class from file
     * See https://stackoverflow.com/questions/7153000/get-class-name-from-file/7153391.
     */
    public function getClass(string $file): ?string
    {
        // todo: rewrite this to use flysystem
        $stream = $this->filesystem->readStream($file);
        $class = '';
        $buffer  = '';
        $i      = 0;
        while (!$class) {
            if (feof($stream)) {
                break;
            }

            $buffer .= fread($stream, 512);
            $tokens = \PhpToken::tokenize($buffer);
            if (!str_contains($buffer, '{')) {
                continue;
            }

            $tokensCount = count($tokens);
            for (; $i < $tokensCount; ++$i) {
                if ($tokens[$i][0] === T_CLASS) {
                    $tokensCount = count($tokens);
                    for ($j = $i + 1; $j < $tokensCount; ++$j) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i + 2][1];
                        }
                    }
                }
            }
        }

        return $class;
    }

    public function getClassAndNamespace(string $file): string
    {
        $nameSpace = $this->getNameSpace($file);
        $class     = $this->getClass($file);

        return $nameSpace . '\\' . $class;
    }
}
