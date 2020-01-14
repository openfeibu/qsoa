<?php namespace App\Widgets;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Supplier;
use App\Repositories\Eloquent\WorldCityRepository;
use Tree;
use Teepluss\Theme\Theme;
use Teepluss\Theme\Widget;
use App\Models\NavCategory;

class BillSearch extends Widget {

    /**
     * Widget template.
     *
     * @var string
     */
    public $template = 'bill_search';

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
        $airports = Airport::orderBy('id','desc')->get();
        $airlines = Airline::orderBy('id','desc')->get();
        $suppliers = Supplier::orderBy('id','desc')->get();

        $this->setAttribute('airports',$airports);
        $this->setAttribute('airlines',$airlines);
        $this->setAttribute('suppliers',$suppliers);

        $attrs = $this->getAttributes();

        return $attrs;
    }

}