<?php

namespace App\Core;

use Exception;

class View {
    public static function render(string $viewPath, array $data = [], ?string $layout = 'client'): void {
        $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View template không tồn tại: {$viewPath} ({$viewFile})");
        }

        extract($data);

        // Render view content into buffer
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Render layout if specified
        if ($layout) {
            $layoutFile = APP_PATH . '/Views/layouts/' . $layout . '.php';
            if (!file_exists($layoutFile)) {
                throw new Exception("Layout không tồn tại: {$layout} ({$layoutFile})");
            }
            require $layoutFile;
        } else {
            echo $content;
        }

        // Dọn dẹp old input session sau khi render xong
        unset($_SESSION['_old_inputs']);
        unset($_SESSION['_form_errors']);
    }

    public static function partial(string $partialName, array $data = []): void {
        $partialFile = APP_PATH . '/Views/partials/' . str_replace('.', '/', $partialName) . '.php';
        if (file_exists($partialFile)) {
            extract($data);
            require $partialFile;
        }
    }
}

