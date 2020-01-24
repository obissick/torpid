<?php

namespace App\Http\Controllers;

use App\GlobalQueryReview;
use App\GlobalQueryReviewHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $results = DB::select( DB::raw('SELECT checksum AS checksum, LEFT(dimension.sample,20) AS snippet, ROUND(SUM(Rows_examined_sum)/SUM(rows_sent_sum),2) AS index_ratio, SUM(Query_time_sum) / SUM(ts_cnt) AS query_time_avg, ROUND(SUM(Rows_sent_sum)/SUM(ts_cnt),0) AS rows_sent_avg, SUM(ts_cnt) AS ts_cnt, SUM(Query_time_sum) AS Query_time_sum, SUM(Lock_time_sum) AS Lock_time_sum, SUM(Rows_sent_sum) AS Rows_sent_sum, SUM(Rows_examined_sum) AS Rows_examined_sum, SUM(Tmp_table_sum) AS Tmp_table_sum, SUM(Filesort_sum) AS Filesort_sum, SUM(Full_scan_sum) AS Full_scan_sum FROM global_query_review AS fact JOIN global_query_review_history AS dimension USING (checksum) GROUP BY checksum,snippet ORDER BY dimension.ts_min DESC LIMIT 50'));

        return view('partials.home', compact('results'));
    }
    public function show($id){
        $results = DB::select( DB::raw("SELECT * FROM global_query_review_history WHERE checksum='$id' order by ts_max desc limit 0, 10"));
        return view('partials.query', compact('results'));
    }
    public function get_by_date(Request $request){
        $from = $request->input('from');
        $to = $request->input('to');
        $results = DB::select( DB::raw("SELECT checksum AS checksum, LEFT(dimension.sample,20) AS snippet, ROUND(SUM(Rows_examined_sum)/SUM(rows_sent_sum),2) AS index_ratio, SUM(Query_time_sum) / SUM(ts_cnt) AS query_time_avg, ROUND(SUM(Rows_sent_sum)/SUM(ts_cnt),0) AS rows_sent_avg, SUM(ts_cnt) AS ts_cnt, SUM(Query_time_sum) AS Query_time_sum, SUM(Lock_time_sum) AS Lock_time_sum, SUM(Rows_sent_sum) AS Rows_sent_sum, SUM(Rows_examined_sum) AS Rows_examined_sum, SUM(Tmp_table_sum) AS Tmp_table_sum, SUM(Filesort_sum) AS Filesort_sum, SUM(Full_scan_sum) AS Full_scan_sum FROM global_query_review AS fact JOIN global_query_review_history AS dimension USING (checksum) WHERE dimension.ts_min >= '$from' AND dimension.ts_min <= '$to' GROUP BY checksum,snippet ORDER BY query_time_avg"));
        return view('partials.home', compact('results'));
    }
}
