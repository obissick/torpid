<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalQueryReviewHistory extends Model
{
    protected $table = 'global_query_review_history';


    public function globalqueryreview()
    {
        return $this->belongsTo('App\GlobalQueryReview', 'checksum');
    }
}
