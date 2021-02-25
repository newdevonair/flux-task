<?php

namespace Application\Controller;

use Core\AbstractController;

class SiteApplicationController extends AbstractController
{
    public function indexAction()
    {
        $this->render('index', ['title' => 'aaa']);
    }
}
