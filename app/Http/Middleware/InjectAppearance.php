<?php

namespace App\Http\Middleware;

use App\Services\SettingsEnforcer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectAppearance
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip non-HTML responses
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response;
        }

        $content = $response->getContent();
        if (!is_string($content) || !str_contains(strtolower($content), '<html')) {
            return $response;
        }

        // Gracefully skip if database is not available (e.g., Render before DB config)
        try {
            $appearance = SettingsEnforcer::getAppearanceData();
        } catch (\Exception $e) {
            return $response;
        }
        $css = '<style>' . $appearance['css'] . "\n" . self::getHelperCss() . '</style>';
        $bodyClass = $appearance['body_class'];

        // Inject CSS before </head> (or after <head> if </head> missing)
        if (stripos($content, '</head>') !== false) {
            $content = str_ireplace('</head>', $css . '</head>', $content);
        } elseif (stripos($content, '<head>') !== false) {
            $content = str_ireplace('<head>', '<head>' . $css, $content);
        } else {
            $content = preg_replace('/<html[^>]*>/i', '$0' . $css, $content, 1);
        }

        // Inject body class
        if ($bodyClass) {
            // Case 1: body already has class attribute
            if (preg_match('/<body\s+[^>]*class=["\']/i', $content)) {
                $content = preg_replace('/(<body\s+[^>]*class=["\'])([^"\']*)/i', '$1$2 ' . $bodyClass, $content, 1);
            }
            // Case 2: body tag without class
            elseif (preg_match('/<body(\s|>)/i', $content)) {
                $content = preg_replace('/<body(\s|>)/i', '<body class="' . $bodyClass . '"$1', $content, 1);
            }
        }

        // Inject real-time cross-tab sync script
        $syncScript = '<script>(function(){function a(){var d=localStorage.getItem("app_dark_mode")==="1";var c=localStorage.getItem("app_compact_mode")==="1";var n=localStorage.getItem("app_animations")!=="0";document.body.classList.toggle("dark-mode",d);document.body.classList.toggle("compact-mode",c);document.body.classList.toggle("animations-disabled",!n);var p=localStorage.getItem("app_primary_color"),s=localStorage.getItem("app_secondary_color"),ac=localStorage.getItem("app_accent_color");if(p)document.documentElement.style.setProperty("--primary-color",p);if(s)document.documentElement.style.setProperty("--secondary-color",s);if(ac)document.documentElement.style.setProperty("--accent-color",ac);}window.addEventListener("storage",function(e){if(["app_dark_mode","app_compact_mode","app_animations","app_primary_color","app_secondary_color","app_accent_color"].includes(e.key)){a();}});if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",a);}else{a();}})();</script>';

        if (stripos($content, '</body>') !== false) {
            $content = str_ireplace('</body>', $syncScript . "\n" . '</body>', $content);
        } else {
            $content .= $syncScript;
        }

        $response->setContent($content);
        return $response;
    }

    private static function getHelperCss(): string
    {
        return '.dark-mode,.dark-mode *{transition:none !important}.dark-mode{filter:invert(1) hue-rotate(180deg)}.dark-mode img,.dark-mode video,.dark-mode iframe,.dark-mode .avatar-circle,.dark-mode .empty-state-icon{filter:invert(1) hue-rotate(180deg)}.compact-mode .p-4{padding:.5rem !important}.compact-mode .p-6{padding:.75rem !important}.compact-mode .p-8{padding:1rem !important}.compact-mode .gap-4{gap:.5rem !important}.compact-mode .gap-6{gap:.75rem !important}.animations-disabled *,.animations-disabled *::before,.animations-disabled *::after{animation-duration:.01ms !important;animation-iteration-count:1 !important;transition-duration:.01ms !important}';
    }
}
