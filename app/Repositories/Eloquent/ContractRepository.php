<?php

namespace App\Repositories\Eloquent;

use App\Models\ContractImage;
use App\Models\Media;
use App\Repositories\Eloquent\ContractRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class ContractRepository extends BaseRepository implements ContractRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.contract.contract.search');
    }

    public function model()
    {
        return config('model.contract.contract.model');
    }
    public function createContract($attributes)
    {
        $images = $attributes['images'];
        $date_arr = explode('~',$attributes['date']);
        $attributes['start_time'] = trim($date_arr[0]);
        $attributes['end_time'] = trim($date_arr[1]);

        $contract = $this->create($attributes);
        foreach ($images as $key => $image)
        {
            $contract_image = ContractImage::create([
                'url' => $image,
                'contract_id' => $contract->id,
                'order' => $key+1
            ]);
            Media::where('url',$image)->update([
                'mediaable_id' => $contract->id,
                'mediaable_type' => 'App\Models\Contract'
            ]);
        }
        return $contract;
    }
    public function updateContract($attributes,$contract)
    {
        if(isset($attributes['images']))
        {
            $date_arr = explode('~',$attributes['date']);
            $attributes['start_time'] = trim($date_arr[0]);
            $attributes['end_time'] = trim($date_arr[1]);
        }

        $contract->update($attributes);
        if(isset($attributes['images']))
        {
            $images = $attributes['images'];
            foreach ($images as $key => $image)
            {
                $contract_image = ContractImage::where('url',$image)->where('contract_id',$contract->id)->first();
                if(!$contract_image)
                {
                    $contract_image = ContractImage::create([
                        'url' => $image,
                        'contract_id' => $contract->id,
                        'order' => $key+1
                    ]);
                    Media::where('url',$image)->update([
                        'mediaable_id' => $contract->id,
                        'mediaable_type' => 'App\Models\Contract'
                    ]);
                }else{
                    $contract_image->update([
                        'order' => $key+1
                    ]);
                }
            }
        }
        return $contract;
    }
}