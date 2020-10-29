<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Jinqi;

trait JinqiResource
{
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);

        if ($this->response->typeIs('json')) {
            $Jinqis = new Jinqi;

            $Jinqis = $Jinqis->when($search ,function ($query) use ($search){
                foreach($search as $field => $value)
                {
                    if($value)
                    {
                        if($field =='FDate'){
                            $date_range = explode('~',trim($search['FDate']));
                            if($date_range)
                            {
                                $query->where('FDate','>=',trim($date_range[0]).'')
                                    ->where('FDate','<=',trim($date_range[1]).'');
                            }
                        }else{
                            $query->where($field,'like','%'.$value.'%');
                        }
                    }

                }
            });

            $Jinqis =$Jinqis
                ->orderBy('id','desc')
                ->paginate($limit);


            return $this->response
                ->success()
                ->count($Jinqis->total())
                ->data($Jinqis->toArray()['data'])
                ->output();
        }
        return $this->response->title(trans('jinqi.title'))
            ->view('jinqi.index')
            ->output();
    }

}
