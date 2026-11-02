<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    /**
     * Render a view file inside an optional layout
     */
    public static function render(string $view, array $data = [], ?string $layout = 'storefront'): string
    {
        $viewsPath = App::getBasePath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
        $viewFile = $viewsPath . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View file not found: {$viewFile}");
        }

        // Extract variables to local scope
        extract($data, EXTR_SKIP);

        // Capture view content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // If no layout is requested, return content directly
        if ($layout === null) {
            return $content;
        }

        // Render with layout
        $layoutFile = $viewsPath . 'layouts' . DIRECTORY_SEPARATOR . $layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new \RuntimeException("Layout file not found: {$layoutFile}");
        }

        ob_start();
        require $layoutFile;
        return ob_get_clean();
    }

    /**
     * Render an isolated component
     */
    public static function component(string $component, array $data = []): string
    {
        $viewsPath = App::getBasePath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR;
        $componentFile = $viewsPath . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $component) . '.php';

        if (!file_exists($componentFile)) {
            throw new \RuntimeException("Component file not found: {$componentFile}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $componentFile;
        return ob_get_clean();
    }
}

