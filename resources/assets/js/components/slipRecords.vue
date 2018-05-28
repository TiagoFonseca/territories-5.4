<template>
  <div>

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
             <template v-if="slip[0]">
               <tr v-for="item in slip">
                   <td>{{ item.date }}</td>
                   <td>
                     <span v-if="item.period_of_day == 1">
                       Before 12PM
                     </span>
                     <span v-else-if="item.period_of_day == 2">
                        Between 12PM and 6PM
                     </span>
                     <span v-else-if="item.period_of_day == 3">
                        After 6PM
                     </span>
                   </td>
                   <td>{{ item.last_house }}</td>
               </tr>
             </template>
             <template v-else>
               <tr>
                   <td colspan="3">There are no records to display</td>
               </tr>
             </template>
            </tbody>
          </table>
        </div>
      </div>


  </div>
</template>

<script>
  export default{
    props: ['slip'],
    mounted () {
        // Do something useful with the data in the template
        console.log("Im in")
    }
  }
</script>
