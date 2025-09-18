<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Controllers\commentaireController;

final class CommentaireControllerTest extends TestCase
{
    public function testAddAndFetchComment(): void
    {
        $ctrl = new commentaireController();
        $ok = $ctrl->addComment('super film', 2, 1234);

        $this->assertTrue($ok, "toto");

        // naive way: last inserted row has the highest id
        $pdo = \cinetech_test_pdo();
        $id = (int)$pdo->query("SELECT MAX(id_commentaire) FROM commentaire")->fetchColumn();
        $row = $ctrl->getCommentById($id);

        $this->assertSame('super film', $row['content']);
        $this->assertSame(1234, (int)$row['id_media']);
    }

    public function testDeleteCommentByUser(): void
    {
        $pdo = \cinetech_test_pdo();
        $pdo->prepare("INSERT INTO commentaire (content, id_user, id_media, created_at) VALUES (?,?,?,NOW())")
            ->execute(['to delete', 9, 999]);
        $id = (int)$pdo->lastInsertId();

        $ctrl = new commentaireController();
        $ok = $ctrl->deleteCommentByUser($id, 9);
        $this->assertTrue($ok);

        $left = (int)$pdo->query("SELECT COUNT(*) FROM commentaire WHERE id_commentaire={$id}")->fetchColumn();
        $this->assertSame(0, $left);
    }
}
