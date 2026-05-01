<?php

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

if (!function_exists('app_normalize_path')) {
    function app_normalize_path($path)
    {
        return trim(str_replace('\\', '/', (string) $path), '/');
    }

    function app_encode_path($path)
    {
        $normalizedPath = app_normalize_path($path);

        if ($normalizedPath === '') {
            return '';
        }

        $segments = explode('/', $normalizedPath);
        $segments = array_map('rawurlencode', $segments);

        return implode('/', $segments);
    }

    function app_base_path()
    {
        static $basePath = null;

        if ($basePath !== null) {
            return $basePath;
        }

        $configuredBasePath = getenv('APP_BASE_PATH');

        if ($configuredBasePath !== false && $configuredBasePath !== '') {
            $normalizedBasePath = app_normalize_path($configuredBasePath);
            $basePath = $normalizedBasePath === '' ? '' : '/' . $normalizedBasePath;

            return $basePath;
        }

        $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
        $projectRoot = realpath(APP_ROOT);

        if ($documentRoot && $projectRoot) {
            $documentRoot = str_replace('\\', '/', rtrim($documentRoot, '\\/'));
            $projectRoot = str_replace('\\', '/', rtrim($projectRoot, '\\/'));

            if (strpos($projectRoot, $documentRoot) === 0) {
                $relativePath = trim(substr($projectRoot, strlen($documentRoot)), '/');
                $basePath = $relativePath === '' ? '' : '/' . $relativePath;

                return $basePath;
            }
        }

        $basePath = '';

        return $basePath;
    }

    function app_url($path = '')
    {
        $normalizedPath = app_normalize_path($path);
        $basePath = app_base_path();

        if ($normalizedPath === '') {
            return $basePath === '' ? '/' : $basePath . '/';
        }

        return ($basePath === '' ? '' : $basePath) . '/' . $normalizedPath;
    }

    function app_root_path($path = '')
    {
        $normalizedPath = trim((string) $path, '/\\');

        if ($normalizedPath === '') {
            return APP_ROOT;
        }

        $normalizedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $normalizedPath);

        return APP_ROOT . DIRECTORY_SEPARATOR . $normalizedPath;
    }

    function route_url($name, array $params = [])
    {
        switch ($name) {
            case 'root':
                return app_url();
            case 'home':
                return app_url('home');
            case 'shop':
                return app_url('shop');
            case 'login':
                return app_url('login');
            case 'register':
                return app_url('register');
            case 'cart':
                return app_url('cart');
            case 'checkout':
                return app_url('checkout');
            case 'orders':
                return app_url('orders');
            case 'logout':
                return app_url('logout');
            case 'product':
                return app_url('product/' . rawurlencode((string) ($params['id'] ?? '')));
            case 'admin.dashboard':
                return app_url('admin');
            case 'admin.products':
                return app_url('admin/products');
            case 'admin.add_product':
                return app_url('admin/products/add');
            case 'admin.orders':
                return app_url('admin/orders');
            case 'admin.users':
                return app_url('admin/users');
            case 'action.login':
                return app_url('actions/login_action.php');
            case 'action.register':
                return app_url('actions/register_action.php');
            case 'action.add_to_cart':
                return app_url('actions/add_to_cart.php');
            case 'action.remove_cart':
                return app_url('actions/remove_cart.php?id=' . rawurlencode((string) ($params['id'] ?? '')));
            case 'action.update_cart':
                return app_url('actions/update_cart.php');
            case 'action.place_order':
                return app_url('actions/place_order.php');
            case 'action.update_order':
                return app_url('actions/update_order.php');
            case 'asset':
                return app_url('assets/' . app_normalize_path((string) ($params['path'] ?? '')));
            case 'upload':
                return app_url('assets/uploads/' . app_encode_path((string) ($params['file'] ?? '')));
            default:
                return app_url($name);
        }
    }

    function redirect_to_url($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, (int) $statusCode);
        exit();
    }

    function redirect_to_route($routeName, array $params = [], $statusCode = 302)
    {
        redirect_to_url(route_url($routeName, $params), $statusCode);
    }
}
