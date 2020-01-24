@extends('welcome')
@section('content')
<div class="row">
    <div class="col-sm-6">
        <div class="container container-fluid" style="margin: 50px">
            <div class="card">
                <div class="card-header">
                Query
                </div>
                <div class="card-body">
                    <pre class="prettyprint lang-sql"><code>{{$results[0]->sample}}</code></pre>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="container container-fluid" style="margin: 50px">
            <div class="card">
                <div class="card-header">
                  Last 10 Executions
                </div>
                <div class="card-body">
                    <table class="table" id="servers-table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Query Time</th>
                                <th>Lock Time</th>
                                <th>Rows Sent</th>
                                <th>Rows Examined</th>
                            </tr>
                        </thead>
                        <tbody id="servers-list" name="servers-list">
                            @for ($i = 0; $i < count($results); $i++)
                                <tr id="{{$results[$i]->checksum}}">
                                    <td>{{$results[$i]->ts_max}}</td>
                                    <td>{{$results[$i]->Query_time_max}}</td>
                                    <td>{{$results[$i]->Lock_time_max}}</td>
                                    <td>{{$results[$i]->Rows_sent_max}}</td>
                                    <td>{{$results[$i]->Rows_examined_max}}</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
      </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var table = $('#queries').DataTable();
    } );
</script>
@endsection