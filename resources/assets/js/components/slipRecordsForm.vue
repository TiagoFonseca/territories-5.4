<template>
  <div>
    <ul class="collapsible " data-collapsible="expandable">
     <li>
       <div class="collapsible-header"><i class="material-icons">add_circle</i>Click here to add a new record</div>
       <div class="collapsible-body">
         <form class="publisherRec publisherRecordsForm" id="AssignmentSlipPublisher" method="post" url="/addAssignmentSlipPublisher">
            <div class="row" >

              <div class="input-field col s12">
                  <input type="text" class="datepicker" placeholder="Date worked on" v-model="record.date" id="materialize-select">
                </div>
              <div class="input-field col s12">
                  <select v-model="record.period_of_day">
            <option value="" disabled selected>Period of day</option>
            <option value="1">Before 12PM</option>
            <option value="2">Between 12PM - 6PM</option>
            <option value="3">After 6PM</option>
          </select>

              </div>
              <div class="input-field col s12">
                  <input type="text" placeholder="Up to number" v-model="record.last_house">
              </div>
              <a class="waves-effect waves-light btn">SAVE</a>
            </div>
        </form>
      </div>
     </li>
    </ul>
  </div>
</template>

<script>
  export default{
    data: function() {
      return{
        record: {
          date:'',
          period_of_day:'',
          last_house:''
        }
      }
    },

  }
</script>
