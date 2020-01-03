<?php namespace App\Widgets;

use App\Repositories\Eloquent\WorldCityRepository;
use Tree;
use Teepluss\Theme\Theme;
use Teepluss\Theme\Widget;
use App\Models\NavCategory;

class Area extends Widget {

    /**
     * Widget template.
     *
     * @var string
     */
    public $template = 'area';

    /**
     * Watching widget tpl on everywhere.
     *
     * @var boolean
     */
    public $watch = false;

    /**
     * Arrtibutes pass from a widget.
     *
     * @var array
     */
    public $attributes = array();

    /**
     * Turn on/off widget.
     *
     * @var boolean
     */
    public $enable = true;

    /**
     * Code to start this widget.
     *
     * @return void
     */
    public function init(Theme $theme)
    {

    }

    /**
     * Logic given to a widget and pass to widget's view.
     *
     * @return array
     */
    public function run()
    {
        /*
        $navs = app('nav_repository')->allNavs()->toArray();

        $navs = Tree::getLevelTree($navs);
        */
        $country_id = $this->attributes['country_id'] ?? '';
        $province_id= $this->attributes['province_id'] ?? '';
        $city_id = $this->attributes['city_id'] ?? '';

        $countries = app(WorldCityRepository::class)->getCountries();

        $this->setAttribute('countries',$countries);

        $attrs = $this->getAttributes();

        return $attrs;
    }

}