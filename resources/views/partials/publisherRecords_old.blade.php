
  <div class="card publisherRecordsTable" id="">
    <div class="card-content">
      <table class="striped">
       <thead>
         <tr>
             <th>Date</th>
             <th>Period of day</th>
             <th>Last house</th>
         </tr>
       </thead>

       <tbody>
           <tr>
             {{-- @php dd($slip['workedOn'])@endphp --}}
         @if(!$slip['workedOn']->isEmpty())
           @foreach($slip['workedOn'] as $value)

             <td>{{$value->date}}</td>
             @if($value->period_of_day == 1)
               <td>Before 12PM</td>
            @endif
             @if($value->period_of_day == 2)
               <td>Between 12PM and 6PM</td>
             @endif
              @if($value->period_of_day == 3)
                <td>After 6PM</td>
             @endif
             <td>{{$value->last_house}}</td>
           @endforeach
        @else
          <td colspan="3">There are no records to display</td>

         @endif
       </tr>

       </tbody>
      </table>
    </div>
  </div>
<ul class="collapsible " data-collapsible="expandable">
 <li>
   <div class="collapsible-header"><i class="material-icons">add_circle</i>Click here to add a new record</div>
   <div class="collapsible-body">
     <form class="publisherRec publisherRecordsForm" id="AssignmentSlipPublisher" method="post" url="/addAssignmentSlipPublisher">
        <div class="row" >

          <div class="input-field col s12">
              <input type="text" class="datepicker" placeholder="Date worked on">
            </div>
          <div class="input-field col s12">
              <select>
        <option value="" disabled selected>Period of day</option>
        <option value="1">Before 12PM</option>
        <option value="2">Between 12PM - 6PM</option>
        <option value="3">After 6PM</option>
      </select>

          </div>
          <div class="input-field col s12">
              <input type="text" placeholder="Up to number">
          </div>
          <a class="waves-effect waves-light btn">SAVE</a>
        </div>
    </form>
  </div>
 </li>
</ul>
