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
             <template v-if="records[0]">
               <tr v-for="item in records">
                   <td>{{ moment(item.date) }}</td>
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
        <slip-records-form :id='this.assslipid' @recordCreated="fetchRecords"></slip-records-form>

      </div>


  </div>
</template>

<script>
  import moment from 'moment'
  export default{
    props: ['assslipid'],
    data: function() {
      return {
        records:[],
        id: this.assslipid
      }
    },
    created: function()
    {
        this.fetchRecords();
    },



    methods:{

      moment: function(date){
        return moment(date).format('DD/MM/YYYY');
      },
      fetchRecords()
      {
        //var id = this.assslipid;
        let uri = 'http://tfonseca.uk/api/get-records';
        axios.post(uri, {
          id: this.assslipid,
        }).then((response) => {
            this.records = response.data.data;
        });
      },
    },
    mounted () {
        // Do something useful with the data in the template
        console.log("Im in")
    },
  }
</script>
