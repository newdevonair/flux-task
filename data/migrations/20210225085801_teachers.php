<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Teachers extends AbstractMigration
{
    /**
     * Migrate Up
     */
    public function up(): void
    {
        if (!$this->hasTable('flux_test_teachers')) {
            $this->table('flux_test_teachers', ['id' => 'teacher_id'])
                ->addColumn('teacher_name', 'string', ['limit' => 255])
                ->addColumn('teacher_title', 'string', ['limit' => 255])
                ->addColumn('created_by', 'integer')
                ->addColumn('updated_by', 'integer')
                ->addForeignKey('created_by', 'flux_task_users', 'user_id', [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ])
                ->addForeignKey('updated_by', 'flux_task_users', 'user_id', [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ])
                ->save();
        }
        $user_id = $this->query('SELECT `user_id` FROM `flux_task_users` WHERE `username` = "flux-test-user"')->fetch(PDO::FETCH_COLUMN);
        $sql_query =
            "INSERT INTO `flux_test_teachers` (`teacher_name`, `teacher_title`, `created_by`, `updated_by`)
             VALUES ('Teacher1', 'Teacher1', {$user_id}, {$user_id})";
        $this->execute($sql_query);
    }

    /**
     * Migrate Down
     */
    public function down(): void
    {
        if ($this->hasTable('flux_test_teachers')) {
            $this->table('flux_test_teachers')->drop()->save();
        }
    }
}
