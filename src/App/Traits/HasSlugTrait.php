<?php

namespace Dotlogics\Grapesjs\App\Traits;

use Spatie\Sluggable\HasSlug as BaseTrait;
use Spatie\Sluggable\SlugOptions;

trait HasSlug
{
	use BaseTrait;

	public function resolveRouteBinding($value, $field = null)
	{	
		$field = is_numeric($value) ? 'id' : 'slug';
	    return self::where($field, $value)->firstOrFail();
	}

	protected function slugOptions(): SlugOptions
	{
		return SlugOptions::create()
	        ->generateSlugsFrom( static::$slugFrom ?? 'title' )
	        ->saveSlugsTo('slug')
	        ->slugsShouldBeNoLongerThan(30);
	}

	public function getSlugOptions(): SlugOptions
	{
	    return $this->slugOptions();
	}
}
