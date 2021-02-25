<?php

declare(strict_types=1);

namespace Application\model;

class TeacherModel
{
    public const TABLE_TEACHERS = 'flux_test_teachers';

    private \PDO $adapter;

    /**
     * TeacherModel constructor.
     * @param \PDO $adapter
     */
    public function __construct(\PDO $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getTeacherList(): array
    {
        /**
         * @todo by this query we get only 100 teachers data
         * for more we need to add page and page_size parameters and some filters
         */
        $sql_query =
            "SELECT  
                SQL_CALC_FOUND_ROWS
                `teacher_id`,
                `teacher_name`,
                `teacher_title`
             FROM `" . self::TABLE_TEACHERS . "`
             ORDER BY `teacher_id` DESC";
        $data = $this->adapter->query($sql_query)->fetchAll(\PDO::FETCH_ASSOC);
        $count = $this->adapter->query('SELECT FOUND_ROWS()')->fetch(\PDO::FETCH_COLUMN);
        return [
            'data' => $data,
            'count' => $count
        ];
    }
}
