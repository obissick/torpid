<?php

namespace App\Http\Controllers;

use App\GlobalQueryReview;
use App\GlobalQueryReviewHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\CustomClass\query_slow_host;

class HomeController extends Controller
{
    public function index()
    {
        $results = DB::select( DB::raw('SELECT checksum AS checksum, LEFT(dimension.sample,20) AS snippet, ROUND(SUM(Rows_examined_sum)/SUM(rows_sent_sum),2) AS index_ratio, SUM(Query_time_sum) / SUM(ts_cnt) AS query_time_avg, ROUND(SUM(Rows_sent_sum)/SUM(ts_cnt),0) AS rows_sent_avg, SUM(ts_cnt) AS ts_cnt, SUM(Query_time_sum) AS Query_time_sum, SUM(Lock_time_sum) AS Lock_time_sum, SUM(Rows_sent_sum) AS Rows_sent_sum, SUM(Rows_examined_sum) AS Rows_examined_sum, SUM(Tmp_table_sum) AS Tmp_table_sum, SUM(Filesort_sum) AS Filesort_sum, SUM(Full_scan_sum) AS Full_scan_sum FROM global_query_review AS fact JOIN global_query_review_history AS dimension USING (checksum) GROUP BY checksum,snippet ORDER BY dimension.ts_min DESC LIMIT 50'));
        $hosts = $this->get_hosts();

        return view('partials.home', compact('results', 'hosts'));
    }
    public function show($id){  
        try{
            $explainErr = null;
            $results = DB::select( DB::raw("SELECT * FROM global_query_review_history WHERE checksum='$id' order by ts_max desc limit 0, 10"));
            $comment = DB::select( DB::raw("SELECT comments FROM global_query_review WHERE checksum='$id'"));
            $explain = query_slow_host::run($results[0]->hostname_max, $results[0]->db_max, $results[0]->sample);  
            return view('partials.query', compact('results', 'explain', 'comment', 'explainErr'));
        }catch(\Illuminate\Database\QueryException $ex){
            report($ex);
            $explainErr = $ex->getMessage();
            $message = explode(' ', $ex->getMessage());
            $dbCode = rtrim($message[1], ']');
            $dbCode = trim($dbCode, '[');

            // codes specific to MySQL
            switch ($dbCode)
            {
                case 1049:
                    $userMessage = 'Unknown database - probably config error:';
                    break;
                case 2002:
                    $userMessage = 'Unable to connect to host.';
                    break;
                case 42000:
                    $userMessage = 'Syntax error, probably trying to explain an unexplainable query. haha';
                    break;
                default:
                    $userMessage = 'Untrapped Error:';
                    break;
            }
            $explainErr = $userMessage;
            return view('partials.query', compact('results', 'comment', 'explainErr'));
        }
    }
    public function filter(Request $request){
        $filters = "";
        $from = $request->input('from');
        $to = $request->input('to');
        $host = $request->input('host');
        $orderby = $request->input('orderby');
        $limit = $request->input('limit');
        $hosts = $this->get_hosts();
        if(isset($host)){
            $filters .= "dimension.hostname_max = '$host'";
        }
        if(isset($from) && isset($host)){
            $filters .= " AND dimension.ts_min >= '$from'";
        }
        elseif(isset($from) && !isset($host)){
            $filters .= "dimension.ts_min >= '$from'";
        }
        elseif(!isset($from) && !isset($host)){
            $filters .= "dimension.ts_min >= NOW() - INTERVAL 1 DAY";
        }
        if(isset($to)){
            $filters .= " AND dimension.ts_min <= '$to'";
        }else{
            $filters .= " AND dimension.ts_min <= NOW()";
        }
        if(!isset($limit)){
            $limit = 50;
        }
        if(!isset($orderby)){
            $orderby = 'query_time_avg desc';
        }
        $results = DB::select( DB::raw("SELECT checksum AS checksum, LEFT(dimension.sample,20) AS snippet, ROUND(SUM(Rows_examined_sum)/SUM(rows_sent_sum),2) AS index_ratio, SUM(Query_time_sum) / SUM(ts_cnt) AS query_time_avg, ROUND(SUM(Rows_sent_sum)/SUM(ts_cnt),0) AS rows_sent_avg, SUM(ts_cnt) AS ts_cnt, SUM(Query_time_sum) AS Query_time_sum, SUM(Lock_time_sum) AS Lock_time_sum, SUM(Rows_sent_sum) AS Rows_sent_sum, SUM(Rows_examined_sum) AS Rows_examined_sum, SUM(Tmp_table_sum) AS Tmp_table_sum, SUM(Filesort_sum) AS Filesort_sum, SUM(Full_scan_sum) AS Full_scan_sum FROM global_query_review AS fact JOIN global_query_review_history AS dimension USING (checksum) WHERE $filters GROUP BY checksum, snippet ORDER BY $orderby"." LIMIT 0, $limit"));
        return view('partials.home', compact('results', 'hosts'));
    }
    private function get_hosts(){
        $hosts = DB::select( DB::raw('SELECT DISTINCT(hostname_max) FROM global_query_review_history'));
        return $hosts;
    }
    public function comment(Request $request, $id){
        $query = DB::table('global_query_review')->where('checksum', $id)->update(['reviewed_by' => 'Default', 'reviewed_on' => now(), 'comments' => $request->comment]);
        return $this->show($id);
    }
}
