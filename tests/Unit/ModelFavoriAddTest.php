<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\ModelFavori;

final class ModelFavoriAddTest extends TestCase
{
    public function testAddFavoriReturnsTrue(): void
    {
        $model = new ModelFavori();
        $ok = $model->addFavori(1, 12345, 'film', 'Inception', '/poster.jpg');
        $this->assertTrue($ok);
    }
}
