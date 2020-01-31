@extends('welcome')
@section('content')
<script>
    $( function() {
        $( "#from" ).datetimepicker({ format: "yyyy-mm-dd HH:mm:ss" }).val();    
    } );
    $( function() {    
        $( "#to" ).datetimepicker({ format: "yyyy-mm-dd HH:mm:ss" }).val();
    } );
</script>
<form autocomplete="off" action="{{route('filter')}}" method="POST" novalidate="">
    <div class="row">
        {{ csrf_field() }}
        <div class="col-sm-2">
            From: <input type="text" id="from" name="from" />
        </div>
        <div class="col-sm-2">
            To: <input type="text" id="to"name="to" />
        </div>
        <div class="col-sm-1">
            Host: 
            <select id="host" name="host">
                <option></option>
                @foreach($hosts as $host)
                    <option value="{{$host->hostname_max}}">{{$host->hostname_max}}</option>
                @endforeach
            </select>
        </div>
        
    </div>
    <div class="row">
        &nbsp;
    </div>
    <div class="row">
        <div class="col-sm-2">
            Order By: <input type="text" id="orderby"name="orderby" value=""/>
        </div>
        <div class="col-sm-2">
            Limit: <input type="text" id="limit"name="limit" value="50"/>
        </div>
    </div>
    <div class="row" style="margin-top:10px">
        <button type="submit" class="btn btn-primary">Search</button>
    </div> 
    <div class="row">
        &nbsp;
    </div>
</form>
<div class="row" style="margin-top:20px">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-sm table-responsive" id="queries" style="width:100%">
            <thead>
                <tr>
                    <th>checksum</th>
                    <th>snippet</th>
                    <th>index_ratio</th>
                    <th>query_time_avg</th>
                    <th>rows_sent_avg</th>
                    <th>ts_cnt</th>
                    <th>query_time_sum</th>
                    <th>Lock_time_sum</th>
                    <th>rows_sent_sum</th>
                    <th>rows_examined_sum</th>
                    <th>tmp_table_sum</th>
                    <th>filesort_sum</th>
                    <th>full_scan_sum</th>
                </tr>
            </thead>
            <tbody id="servers-list" name="servers-list">
                @for ($i = 0; $i < count($results); $i++)
                <tr id="query{{$results[$i]->checksum}}">
                    <td><a href="{{route('show', $results[$i]->checksum)}}">{{$results[$i]->checksum}}</a></td>
                    <td>{{$results[$i]->snippet}}</td>
                    <td>{{$results[$i]->index_ratio}}</td>
                    <td>{{$results[$i]->query_time_avg}}</td>
                    <td>{{$results[$i]->rows_sent_avg}}</td>
                    <td>{{$results[$i]->ts_cnt}}</td>
                    <td>{{$results[$i]->Query_time_sum}}</td>
                    <td>{{$results[$i]->Lock_time_sum}}</td>
                    <td>{{$results[$i]->Rows_sent_sum}}</td>
                    <td>{{$results[$i]->Rows_examined_sum}}</td>
                    <td>{{$results[$i]->Tmp_table_sum}}</td>
                    <td>{{$results[$i]->Filesort_sum}}</td>
                    <td>{{$results[$i]->Full_scan_sum}}</td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        var table = $('#queries').DataTable({"ordering": false});
    } );
</script>
@endsection