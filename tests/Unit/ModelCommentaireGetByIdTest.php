<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\ModelCommentaire;

final class ModelCommentaireGetByIdTest extends TestCase
{
    public function testGetCommentByIdReturnsRow(): void
    {
        $pdo = \cinetech_test_pdo();
        $pdo->prepare("INSERT INTO commentaire (content, id_user, id_media, created_at) VALUES (?,?,?,NOW())")
            ->execute(['hello', 1, 42]);
        $id = (int)$pdo->lastInsertId();

        $model = new ModelCommentaire();
        $row = $model->getCommentById($id);

        $this->assertIsArray($row);
        $this->assertSame('hello', $row['content']);
        $this->assertSame(42, (int)$row['id_media']);
    }
}
