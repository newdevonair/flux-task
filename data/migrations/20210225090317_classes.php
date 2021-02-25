<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Classes extends AbstractMigration
{
    /**
     * Migrate Up
     */
    public function up(): void
    {
        if (!$this->hasTable('flux_test_classes')) {
            $this->table('flux_test_classes', ['id' => 'class_id'])
                ->addColumn('class_title', 'string', ['limit' => 255])
                ->addColumn('teacher_id', 'integer')
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
        if (!$this->hasTable('flux_test_classes_schedules')) {
            $this->table('flux_test_classes_schedules', ['id' => 'schedule_id'])
                ->addColumn('class_id', 'integer')
                ->addColumn('week_name', 'enum', ['values' => [
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                ]])
                ->addColumn('start_time', 'integer')
                ->addColumn('end_time', 'integer')
                ->addForeignKey('class_id', 'flux_test_classes', 'class_id', [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ])
                ->save();
        }

        $teacher_id = $this->query("SELECT `teacher_id` FROM `flux_test_teachers` WHERE `teacher_name` = 'Teacher1'")->fetch(PDO::FETCH_COLUMN);
        $user_id = $this->query('SELECT `user_id` FROM `flux_task_users` WHERE `username` = "flux-test-user"')->fetch(PDO::FETCH_COLUMN);
        $sql_query = "INSERT INTO `flux_test_classes` (`class_title`, `teacher_id`, `created_by`, `updated_by`) 
                      VALUES ('Class1', {$teacher_id}, {$user_id}, {$user_id})";
        $this->execute($sql_query);
    }

    /**
     * Migrate Down
     */
    public function down(): void
    {
        if ($this->hasTable('flux_test_classes_schedules')) {
            $this->table('flux_test_classes_schedules')->drop()->save();
        }
        if ($this->hasTable('flux_test_classes')) {
            $this->table('flux_test_classes')->drop()->save();
        }
    }
}
