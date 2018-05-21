<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Domain\Model\Widget;

use Symfony\Component\HttpFoundation\Response;

interface WidgetInterface
{
    public function render(): Response;
}
