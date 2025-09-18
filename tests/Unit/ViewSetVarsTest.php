<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Classes\View;

final class ViewSetVarsTest extends TestCase
{
    public function testSetVarsStoresData(): void
    {
        $view = new View('index');
        $ref = new ReflectionClass($view);
        $method = $ref->getMethod('setVars');
        $method->invoke($view, ['title' => 'Cinetech']);

        $prop = $ref->getProperty('variables');
        $prop->setAccessible(true);
        $vars = $prop->getValue($view);

        $this->assertSame('Cinetech', $vars['title']);
    }
}
