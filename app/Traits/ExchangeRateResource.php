<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\ExchangeRate;

trait ExchangeRateResource
{
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);

        if ($this->response->typeIs('json')) {
            $exchange_rates = new ExchangeRate;

            $exchange_rates =$exchange_rates->when($search ,function ($query) use ($search){
                foreach($search as $field => $value)
                {
                    if($value)
                    {
                        if($field == 'currencyCode')
                        {
                            $query->where($field,'=',$value);
                        }else if($field =='date'){
                            $date_range = explode('~',trim($search['date']));
                            if($date_range)
                            {
                                $query->where('date','>=',trim($date_range[0]).'')
                                    ->where('date','<=',trim($date_range[1]).'');
                            }
                        }else{
                            $query->where($field,'like','%'.$value.'%');
                        }
                    }

                }
            });

            $exchange_rates =$exchange_rates
                ->orderBy('id','desc')
                ->paginate($limit);


            return $this->response
                ->success()
                ->count($exchange_rates->total())
                ->data($exchange_rates->toArray()['data'])
                ->output();
        }
        $currencies = Currency::orderBy('currencyCode','asc')->get();
        $sources = ExchangeRate::groupBy('source')->pluck('source');
        return $this->response->title(trans('exchange_rate.title'))
            ->data(compact('currencies','sources'))
            ->view('exchange_rate.index')
            ->output();
    }

}
