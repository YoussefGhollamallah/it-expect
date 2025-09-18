<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Controllers\FavorisController;

final class FavorisControllerTest extends TestCase
{
    public function testAddThenDuplicate(): void
    {
        $ctrl = new FavorisController();
        $res1 = $ctrl->addFavori(1, 777, 'film', 'Seven', '/p.jpg');
        $this->assertTrue($res1['success']);

        $res2 = $ctrl->addFavori(1, 777, 'film', 'Seven', '/p.jpg');
        $this->assertFalse($res2['success']);
        $this->assertStringContainsString('déjà', $res2['message']);
    }
}
