@extends('welcome')
@section('content')
<a class="btn btn-primary" href="javascript:history.back()">Back</a>
<div class="row">
    <div class="col-sm-6">
        <div class="container container-fluid" style="">
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
    <div class="col-sm-6">
        <div class="container container-fluid" style="">
            <div class="card">
                <div class="card-header">
                  Last 10 Executions
                </div>
                <div class="card-body">
                    <table class="table table-responsive" id="servers-table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Query Time</th>
                                <th>Lock Time</th>
                                <th>Rows Sent</th>
                                <th>Rows Examined</th>
                                <th>Host</th>
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
                                    <td>{{$results[$i]->hostname_max}}</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="card">
                <div class="card-header">
                  
                </div>
                <div class="card-body">
                    {{$results[0]->db_max}}
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="card">
                <div class="card-header">
                    Explain
                </div>
                <div class="card-body">
                    <table class="table table-responsive" id="">
                        <thead>
                            <tr>
                                <th>Select Type</th>
                                <th>Table</th>
                                <th>Type</th>
                                <th>Possible Keys</th>
                                <th>Key</th>
                                <th>Key Length</th>
                                <th>Ref</th>
                                <th>Rows</th>
                                <th>Extra</th>
                            </tr>
                        </thead>
                        <tbody id="" name="">
                            @for ($i = 0; $i < count($explain); $i++)
                                <tr id="{{$explain[$i]->id}}">
                                    <td>{{$explain[$i]->select_type}}</td>
                                    <td>{{$explain[$i]->table}}</td>
                                    <td>{{$explain[$i]->type}}</td>
                                    <td>{{$explain[$i]->possible_keys}}</td>
                                    <td>{{$explain[$i]->key}}</td>
                                    <td>{{$explain[$i]->key_len}}</td>
                                    <td>{{$explain[$i]->ref}}</td>
                                    <td>{{$explain[$i]->rows}}</td>
                                    <td>{{$explain[$i]->Extra}}</td>
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