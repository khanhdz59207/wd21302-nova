<?php

namespace App\Core;

abstract class Controller {
    protected Request $request;

    public function __construct() {
        $this->request = new Request();
    }

    protected function render(string $view, array $data = [], ?string $layout = 'client'): void {
        View::render($view, $data, $layout);
    }

    protected function json(mixed $data, int $status = 200): void {
        Response::json($data, $status);
    }

    protected function redirect(string $path, int $status = 302): void {
        Response::redirect(base_url($path), $status);
    }

    protected function back(): void {
        $referer = $_SERVER['HTTP_REFERER'] ?? base_url();
        Response::redirect($referer);
    }

    protected function validate(array $rules, array $messages = []): array {
        $data = $this->request->all();
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            $_SESSION['_form_errors'] = $validator->errors();
            $_SESSION['_old_inputs'] = $data;
            Flash::error('Vui lòng kiểm tra lại thông tin đã nhập.');
            $this->back();
        }

        return $data;
    }
}

