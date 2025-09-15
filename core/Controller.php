<?php

namespace Core;

abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected CrudGenerator $crud;
    protected View $view;

    public function __construct()
    {
        $app = Application::getInstance();
        $this->request = $app->getRequest();
        $this->response = $app->getResponse();
        $this->crud = $app->getCrud();
        $this->view = $app->view;
    }

    /**
     * Render view
     */
    protected function render(string $view, array $data = [], ?string $layout = null): void
    {
        $this->view->render($view, $data, $layout);
    }

    /**
     * Return JSON response
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        $this->response->setStatusCode($statusCode);
        $this->response->setHeader('Content-Type', 'application/json');
        echo json_encode($data);
    }

    /**
     * Redirect to URL
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        $this->response->redirect($url, $statusCode);
    }

    /**
     * Get validated input
     */
    protected function validate(array $rules): array
    {
        $validator = new Validator($this->request->all());
        return $validator->validate($rules);
    }
}
