<?php

namespace Forme\CodeGen\Source\Controllers;

use Forme\Framework\Controllers\AbstractController;
use NameSpacePlaceHolder\Core\View;
use Psr\Http\Message\ServerRequestInterface;

final class TemplateController extends AbstractController
{
    public function __construct(private View $view)
    {
    }

    /**
     * @param ServerRequestInterface $request
     */
    public function __invoke($request)
    {
        $context = $request->input('fields');

        return $this->view->render('example', $context);
    }
}
