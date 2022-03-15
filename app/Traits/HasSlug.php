<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $generated = static::$genSlug;

            $separator = '-';

            $slug = trim($model->$generated);

            $slug = mb_strtolower($slug, "UTF-8");;

            $slug = preg_replace("/[^a-z0-9_\sءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $slug);

            $slug = preg_replace("/[\s-]+/", " ", $slug);

            $slug = preg_replace("/[\s_]/", $separator, $slug);

            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            $model->slug = $count ? "{$slug}-{$count}" : $slug;
        });

        static::updating(function ($model) {
            $generated = static::$genSlug;

            $separator = '-';

            $slug = trim($model->$generated);

            $slug = mb_strtolower($slug, "UTF-8");;

            $slug = preg_replace("/[^a-z0-9_\sءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $slug);

            $slug = preg_replace("/[\s-]+/", " ", $slug);

            $slug = preg_replace("/[\s_]/", $separator, $slug);

            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            $model->slug = $count ? "{$slug}-{$count}" : $slug;
        });

    }
}
