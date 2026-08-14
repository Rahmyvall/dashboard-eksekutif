<?php

/**
 * Helper function untuk cek menu permission
 */
if (!function_exists('canAccessMenu')) {
    function canAccessMenu(string $menuName): bool
    {
        $menuService = app(\App\Services\MenuService::class);
        return $menuService->canAccessMenu($menuName);
    }
}

/**
 * Helper function untuk get semua menu yang dapat diakses user
 */
if (!function_exists('getAccessibleMenus')) {
    function getAccessibleMenus() {
        $menuService = app(\App\Services\MenuService::class);
        return $menuService->getMenus();
    }
}

/**
 * Helper function untuk cek apakah user dapat mengakses fitur spesifik berdasarkan role
 */
if (!function_exists('userHasRole')) {
    function userHasRole(string|array $roles): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        return $user->hasAnyRole((array) $roles);
    }
}

/**
 * Helper function untuk cek permission
 */
if (!function_exists('userCan')) {
    function userCan(string|array $permissions): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        foreach ((array) $permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        return false;
    }
}

/**
 * Helper untuk normalize role name
 */
if (!function_exists('normalizeRoleName')) {
    function normalizeRoleName(string $role): string
    {
        return strtolower(str_replace(['-', ' '], '_', trim($role)));
    }
}
