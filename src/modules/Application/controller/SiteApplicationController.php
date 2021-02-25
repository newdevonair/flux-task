<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\MainApplication;
use Application\model\ClassesModel;
use Application\model\TeacherModel;
use Core\AbstractController;

class SiteApplicationController extends AbstractController
{
    private TeacherModel $teacher_model;
    private ClassesModel $class_model;

    /**
     * SiteApplicationController constructor.
     * @param string $current_path
     */
    public function __construct(string $current_path)
    {
        parent::__construct($current_path);
        $this->teacher_model = new TeacherModel(MainApplication::$adapter);
        $this->class_model = new ClassesModel(MainApplication::$adapter);
    }

    /**
     * open main page of application
     */
    public function indexAction()
    {
        $this->render('index');
    }

    /**
     * get teachers list
     */
    public function teachersAction()
    {
        $teachers_list = $this->teacher_model->getTeacherList();

        $this->render('teachers', [
            'model' => $teachers_list
        ]);
    }

    /**
     * get teachers list
     */
    public function classesAction()
    {
        $class_list = [];
        if ($_POST) {
            if ($this->class_model->validate($_POST)) {
                $this->class_model->create($_POST);
            } else {
                $class_list['error_messages'] = $this->class_model->getValidationErrors();
            }
        }
        $class_list = array_merge($this->class_model->getClassesList(), $class_list);
        $this->render('classes', [
            'model' => $class_list
        ]);
    }
}
