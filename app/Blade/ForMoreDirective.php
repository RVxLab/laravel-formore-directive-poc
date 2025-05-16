<?php

declare(strict_types=1);

namespace App\Blade;

use Illuminate\Contracts\View\ViewCompilationException;
use function trim;

final class ForMoreDirective
{
    /** Start a formore loop */
    public static function forMore(string $expression): string
    {
        preg_match('/(.+)\s+as\s+(.+)\s*,\s+(\d+)$/is', $expression, $matches);

        if (\count($matches) === 0) {
            throw new ViewCompilationException('Malformed @formore statement');
        }

        $iteratee = trim($matches[1]);
        $iteration = trim($matches[2]);
        $limit = (int) trim($matches[3]);

        $code = <<<PHP
            \$__forMoreEmpty = true;
            \$__currentLoopDataSource = collect({$iteratee});
            \$__currentLoopData = \$__currentLoopDataSource->slice(0, $limit);
            \$__currentLoopMoreData = \$__currentLoopDataSource->slice($limit)->values();
            \$__env->addLoop(\$__currentLoopData);

            foreach (\$__currentLoopData as {$iteration}):
                \$__env->incrementLoopIndices();
                \$loop = \$__env->getLastLoop();

                \$__forMoreEmpty = false;
        PHP;

        return \sprintf('<?php %s ?>', trim($code));
    }

    /**
     * End the foreach loop block. If there are more items available, they will be available in the $more variable
     *
     * The $more variable is a collection
     */
    public static function more(): string
    {
        $code = <<<PHP
            endforeach;
            \$__env->popLoop();
            \$loop = \$__env->getLastLoop();

            if (\$__currentLoopMoreData->isNotEmpty()):
                \$more = \$__currentLoopMoreData;
        PHP;

        return \sprintf('<?php %s ?>', trim($code));
    }

    /** Add an empty check */
    public static function empty(?string $expression): string
    {
        if ($expression) {
            return "<?php if (empty($expression)): ?>";
        }

        return '<?php endif; if ($__forMoreEmpty): ?>';
    }

    /** End the formore loop */
    public static function endForMore(): string
    {
        return '<?php endif; ?>';
    }
}
