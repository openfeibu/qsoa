<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\QuotationRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class QuotationRepository extends BaseRepository implements QuotationRepositoryInterface
{
    public function model()
    {
        return config('model.quotation.quotation.model');
    }
    public function boot()
    {
        $this->fieldSearchable = config('model.quotation.quotation.search');
    }

}