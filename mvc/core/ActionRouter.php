<?php
/**
 * Only dispatches to a fixed, internally defined route map.
 * Request parameters are never interpolated into a filesystem path.
 */
final class ActionRouter
{
    public static function dispatch(string $action, array $routes, array $arguments = []): void
    {
        if (!isset($routes[$action])) {
            http_response_code(404);
            echo 'Trang không tồn tại!';
            return;
        }

        [$controller, $file] = $routes[$action];
        require_once $file;
        $controller::handle(...$arguments);
    }
}
