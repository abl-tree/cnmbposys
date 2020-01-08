<table>
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            @foreach($summaries as $summary)
            <th colspan="2">{{ $summary['date'] }}</th>
            @endforeach
        </tr>
        <tr>
            <th>Cluster</th>
            <th>Employee Name</th>
            <th>Email Address</th>
            <th>Team Lead</th>
            <th>Average Conformance</th>
            @foreach($summaries as $summary)
            <th>Conformance</th>
            <th>Tagged Status</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php

        $summaryData = [];

        foreach($summaries as $summary) {
            foreach($summary['data'] as $agentkey => $agent) {
                if($value = $agent->schedule->first() ? $agent->schedule->first() : null) {
                    $cluster = $value->om_info ? $value->om_info : $agent->operations_manager;
                    $team_lead = $value->tl_info ? $value->tl_info : $agent->team_leader;
                    
                    if(isset($summaryData[$agentkey])) {

                        array_push($summaryData[$agentkey], (!$value->leave_id) ? ($value->conformance / 100) * 100 : 0, ($agent->status === 'inactive') ? $agent->type : ((!$value->leave_id) ? $value->remarks : (($value->leave->status === 'approved') ? 'LEAVE' : null)));

                        if(!$value->leave_id) $summaryData[$agentkey][4] += (($value->conformance / 100) * 100) / 2;

                    } else {
                        $summaryData[$agentkey] = [
                            $cluster ? $cluster->firstname : null, 
                            $agent->full_name, 
                            $agent->p_email, 
                            $team_lead ? $team_lead->fullname : null,
                            (!$value->leave_id) ? ($value->conformance / 100) * 100 : 0, 
                            (!$value->leave_id) ? ($value->conformance / 100) * 100 : 0, 
                            ($agent->status === 'inactive') ? $agent->type : ((!$value->leave_id) ? $value->remarks : (($value->leave->status === 'approved') ? 'LEAVE' : null))
                        ];
                    }
                } else {
                    $team_lead = $agent->accesslevelhierarchy()->first() && $agent->accesslevelhierarchy()->first()->parentInfo->first() && $agent->accesslevelhierarchy()->first()->parentInfo()->first() ? $agent->accesslevelhierarchy()->first()->parentInfo()->first() : null;
                    $cluster = $team_lead && $team_lead->accesslevelhierarchy()->first() && $team_lead->accesslevelhierarchy()->first()->parentInfo->first() && $team_lead->accesslevelhierarchy()->first()->parentInfo()->first() ? $team_lead->accesslevelhierarchy()->first()->parentInfo()->first() : null;
                    
                    if(isset($summaryData[$agentkey])) {

                        array_push($summaryData[$agentkey], 'OFF', 'OFF');

                    } else {
                        $summaryData[$agentkey] = [
                            $cluster ? $cluster['firstname'] : null, 
                            $agent->full_name, 
                            $agent->p_email, 
                            $team_lead ? $team_lead['full_name'] : null,
                            null,
                            'OFF',
                            'OFF'
                        ];
                    }
                }
            }
        }

        @endphp

        @foreach($summaryData as $summary)
        <tr>
            @foreach($summary as $value)
            <td>{{ $value }}</td>
            @endforeach
        </tr>
        @endforeach

    </tbody>
</table>