<?php

declare(strict_types=1);

use Fi1a\SimpleQuery\CompileSelector;

CompileSelector::addCompiler([CompileSelector::class, 'compileTag']);
CompileSelector::addCompiler([CompileSelector::class, 'compileId']);
CompileSelector::addCompiler([CompileSelector::class, 'compileClass']);
CompileSelector::addCompiler([CompileSelector::class, 'compileWhitespace']);
CompileSelector::addCompiler([CompileSelector::class, 'compileAttribute']);
CompileSelector::addCompiler([CompileSelector::class, 'compilePseudo']);
CompileSelector::addCompiler([CompileSelector::class, 'compileMultiple']);
