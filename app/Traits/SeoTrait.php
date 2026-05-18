<?php

namespace App\Traits;

trait SeoTrait
{
    /**
     * Установка мета-тегов для страницы
     */
    public function setMeta($title, $description = null, $keywords = null, $image = null)
    {
        view()->share('meta_title', $title);
        view()->share('meta_description', $description ?? config('app.description', 'История компьютерных игр в одном месте'));
        view()->share('meta_keywords', $keywords ?? config('app.keywords', 'игры, видеоигры, история игр'));
        view()->share('meta_image', $image ?? asset('images/og-image.jpg'));

        // Open Graph
        view()->share('og_title', $title);
        view()->share('og_description', $description ?? config('app.description', 'История компьютерных игр в одном месте'));
        view()->share('og_image', $image ?? asset('images/og-image.jpg'));
        view()->share('og_url', url()->current());
    }
}
