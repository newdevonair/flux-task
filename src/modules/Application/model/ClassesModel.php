<?php

declare(strict_types=1);

namespace Application\model;

use Core\ValidationModel;

class ClassesModel
{
    public const TABLE_CLASSES = 'flux_test_classes';
    public const TABLE_CLASSES_SCHEDULES = 'flux_test_classes_schedules';

    private \PDO $adapter;
    private array $validation_errors;


    /**
     * TeacherModel constructor.
     * @param \PDO $adapter
     */
    public function __construct(\PDO $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return array
     */
    public function getClassesList(): array
    {
        $sql_query =
            "SELECT 
                SQL_CALC_FOUND_ROWS
                `classes`.`class_id`,
                `classes`.`class_title`,
                `teachers`.`teacher_name`,
                `teachers`.`teacher_title`,
                JSON_ARRAYAGG(JSON_OBJECT(
                    'week_day', `schedules`.`week_name`,
                    'start_time', `schedules`.`start_time`,
                    'end_time', `schedules`.`end_time`
                )) AS `schedules`
            FROM `" . self::TABLE_CLASSES . "` AS `classes`
            INNER JOIN `" . TeacherModel::TABLE_TEACHERS . "` AS `teachers`
                ON (`teachers`.`teacher_id` = `classes`.`teacher_id`)
            LEFT JOIN `" . self::TABLE_CLASSES_SCHEDULES . "` AS `schedules`
                ON (`schedules`.`class_id` = `classes`.`class_id`)
            GROUP BY `classes`.`class_id`";
        $data = $this->adapter->query($sql_query)->fetchAll(\PDO::FETCH_ASSOC);
        $count = $this->adapter->query('SELECT FOUND_ROWS()')->fetch(\PDO::FETCH_COLUMN);
        return [
            'data' => $data,
            'count' => $count
        ];
    }

    /**
     * @param array $request_data
     * @return array
     */
    public function validate(array $request_data): bool
    {
        $validator_helper = new ValidationModel();
        $validator_helper->setValidations($this->getValidationRules());
        $validator_helper->setRequestData($request_data);
        $has_error = $validator_helper->validate();
        $error_messages = [];
        if (!$has_error) {
            // run custom dependency validations
            if ($this->validateTwoNumbers((int)$request_data['start_time_hour'], (int)$request_data['end_time_hour'])) {
                $error_messages['end_time_hour'][] = "Start time hour can not bigger then end time";
            } elseif($request_data['start_time_hour'] == $request_data['end_time_hour'] &&
                $this->validateTwoNumbers((int)$request_data['start_time_minutes'], (int)$request_data['end_time_minutes'])
            ){
                $error_messages['end_time_minutes'][] = "Start time minutes can not bigger then end time minutes";
            }

        } else {
            $error_messages = $validator_helper->getErrorMessages();
        }
        $this->validation_errors = $error_messages;
        return empty($this->validation_errors);
    }

    public function create(array $request_data)
    {
        $sql_query =
            "INSERT INTO `" . self::TABLE_CLASSES_SCHEDULES . "` 
             (`class_id`, `week_name`, `start_time`, `end_time`)
             VALUES (:class_id, :week_name, :start_time, :end_time)";
        $this->adapter->prepare($sql_query)->execute([
            ':class_id' => $request_data['class_id'],
            ':week_name' => $request_data['week_name'],
            ':start_time' => $request_data['start_time_hour'] . ':'  . $request_data['start_time_minutes'],
            ':end_time' => $request_data['end_time_hour'] . ':'  . $request_data['end_time_minutes'],
        ]);
    }


    /**
     * @return array[]
     */
    private function getValidationRules(): array
    {
        return [
            'start_time_hour' => [
                'type' => 'number',
                'min' => 00,
                'max' => 24,
                'required' => true,
            ],
            'end_time_hour' => [
                'type' => 'number',
                'min' => 00,
                'max' => 24,
                'required' => true,
            ],
            'start_time_minutes' => [
                'type' => 'number',
                'min' => 00,
                'max' => 60,
                'required' => true,
            ],
            'end_time_minutes' => [
                'type' => 'number',
                'min' => 00,
                'max' => 60,
                'required' => true,
            ],
        ];
    }

    /**
     * @param int $numberOne
     * @param int $numberTwo
     * @return bool
     */
    private function validateTwoNumbers(int $numberOne, int $numberTwo): bool
    {
        return !($numberTwo >= $numberOne);
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validation_errors;
    }
}
