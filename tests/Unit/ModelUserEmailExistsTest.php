<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\ModelUser;

final class ModelUserEmailExistsTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = \cinetech_test_pdo();
        // seed one user
        $stmt = $this->pdo->prepare("INSERT INTO user (firstname, lastname, email, password) VALUES (?,?,?,?)");
        $stmt->execute(['John', 'Doe', 'john@example.com', password_hash('secret', PASSWORD_BCRYPT)]);
    }

    public function testEmailExistsTrue(): void
    {
        $model = new ModelUser();
        $this->assertTrue($model->emailExists('john@example.com'));
    }

    public function testEmailExistsFalse(): void
    {
        $model = new ModelUser();
        $this->assertFalse($model->emailExists('jane@example.com'));
    }
}
