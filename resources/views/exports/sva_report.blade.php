<table>
    <thead>
    <tr>
        <th>Cluster</th>
        <th>Employee Name</th>
        <th>Email Address</th>
        <th>Team Lead</th>
        <th>SCHEDULE</th>
        <th>TIME-IN</th>
        <th>TIME-OUT</th>
        <th>TIME-IN</th>
        <th>TIME-OUT</th>
        <th>Rendered Time</th>
        <th>Conformance</th>
        <th>Status</th>
        <th>Override Status</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($agents as $agent)

        @if($value = $agent->schedule->first() ? $agent->schedule->first() : null)

            @php
                
                $cluster = $value->om_info ? $value->om_info : $agent->operations_manager;
                $team_lead = $value->tl_info ? $value->tl_info : $agent->team_leader;

            @endphp
            <tr>
                <td>{{ $cluster ? $cluster->full_name : null }}</td>
                <td>{{ $agent->full_name }}</td>
                <td>{{ $agent->p_email }}</td>
                <td>{{ $team_lead ? $team_lead->fullname : null }}</td>
                <td>{{ $value->start_event->format('h:i A').'-'.$value->end_event->format('h:i A') }}</td>
                <td>{{ $value->start_event }}</td>
                <td>{{ $value->end_event }}</td>
                <td>{{ ($agent->status === 'inactive') ? $agent->type : ((!$value->leave_id) ? $value->time_in : (($value->leave->status === 'approved') ? 'LEAVE' : null)) }}</td>
                <td>{{ ($agent->status === 'inactive') ? $agent->type : ((!$value->leave_id) ? $value->time_out : (($value->leave->status === 'approved') ? 'LEAVE' : null)) }}</td>
                <td>{{ $value->overtime_id ? $value->overtime['billable']['decimal'] : $value->rendered_hours['billable']['decimal'] }}</td>
                <td>{{ (!$value->leave_id) ? $value->conformance / 100 : null }}</td>
                <td>{{ (!$value->leave_id) ? implode(', ', $value->log_status) : null }}</td>
                <td>{{ ($agent->status === 'inactive') ? $agent->type : ((!$value->leave_id) ? null : (($value->leave->status === 'approved') ? 'LEAVE' : null)) }}</td>
                <td>{{ $value->remarks }}</td>
            </tr>

        @else
        
            @php
                
                $team_lead = $agent->accesslevelhierarchy()->first() && $agent->accesslevelhierarchy()->first()->parentInfo->first() && $agent->accesslevelhierarchy()->first()->parentInfo()->first() ? $agent->accesslevelhierarchy()->first()->parentInfo()->first() : null;
                $cluster = $team_lead && $team_lead->accesslevelhierarchy()->first() && $team_lead->accesslevelhierarchy()->first()->parentInfo->first() && $team_lead->accesslevelhierarchy()->first()->parentInfo()->first() ? $team_lead->accesslevelhierarchy()->first()->parentInfo()->first() : null;

            @endphp
            <tr>
                <td>{{ $cluster->full_name }}</td>
                <td>{{ $agent->full_name }}</td>
                <td>{{ $agent->p_email }}</td>
                <td>{{ $team_lead->full_name }}</td>
                <td>OFF</td>
                <td>OFF</td>
                <td>OFF</td>
                <td>OFF</td>
                <td>OFF</td>
                <td>OFF</td>
                <td></td>
                <td>OFF</td>
                <td></td>
                <td></td>
            </tr>

        @endif

    @endforeach
    </tbody>
</table>