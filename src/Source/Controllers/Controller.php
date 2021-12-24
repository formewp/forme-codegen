<?php

namespace Forme\CodeGen\Source\Controllers;

use Forme\Framework\Controllers\AbstractController;
use NameSpacePlaceHolder\Core\View;
use Psr\Http\Message\ServerRequestInterface;

final class Controller extends AbstractController
{
    /** @var View */
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param ServerRequestInterface $request
     */
    public function __invoke($request)
    {
        // sort out the context
        $context = [
            'foo' => 'bar',
        ];

        return $this->view->render('example', $context);
    }
}
