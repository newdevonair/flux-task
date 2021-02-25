<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Init extends AbstractMigration
{
    /**
     * Migration UP
     **/
    public function up(): void
    {
        if (!$this->hasTable('flux_task_roles')) {
            $this->table('flux_task_roles', ['id' => 'role_id'])
                ->addColumn('role_name', 'string', ['limit' => 25])
                ->addColumn('role_description', 'string', ['limit' => 255, 'null' => true])
                ->save();
        }
        if (!$this->hasTable('flux_task_users')) {
            $this->table('flux_task_users', ['id' => 'user_id'])
                ->addColumn('username', 'string', ['limit' => 255])
                ->addColumn('password', 'string', ['limit' => 255])
                ->addColumn('role_id', 'integer')
                ->addColumn('first_name', 'string', ['limit' => 255])
                ->addColumn('last_name', 'string', ['limit' => 255])
                ->addForeignKey('role_id', 'flux_task_roles', 'role_id', [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT'
                ])
                ->save();
        }
        $sql_query = "
            INSERT INTO `flux_task_roles` (`role_name`, `role_description`)
            VALUES ('flux-admin', 'This role used to manage all part of application')";
        $this->execute($sql_query);
        $role_id = $this->query('SELECT LAST_INSERT_ID()')->fetch(PDO::FETCH_COLUMN);
        $password = '$2y$12$Ocbs1WUpiEuXpkGqbYtWjeSQN/einqDs/3Tu17BWMTXNH/8nuqI/G';
        $sql_query = "INSERT INTO `flux_task_users` (`username`, `password`, `role_id`, `first_name`, `last_name`)
                      VALUES ('flux-test-user', '{$password}', {$role_id}, 'Flux-User', 'Flux-User-Last')";
        $this->execute($sql_query);
    }

    /**
     * Migration down
     */
    public function down(): void
    {
        if ($this->hasTable('flux_task_users')) {
            $this->table('flux_task_users')->drop()->save();
        }
        if ($this->hasTable('flux_task_roles')) {
            $this->table('flux_task_roles')->drop()->save();
        }
    }
}
