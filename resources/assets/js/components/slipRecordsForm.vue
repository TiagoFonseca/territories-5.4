<template>
  <div>
    <ul class="collapsible " data-collapsible="expandable">
     <li>
       <div class="collapsible-header cyan lighten-5"><i class="material-icons">add_circle</i>Click here to add a new record</div>
       <div class="collapsible-body">
         <form class="publisherRec publisherRecordsForm" id="AssignmentSlipPublisher" url="/addAssignmentSlipPublisher" method="PATCH" @submit.prevent="onSubmit">
            <div class="row" >

              <div class="input-field col s12">
                  <input type="text" class="datepicker" placeholder="Date worked on" v-model="date" :id="assignment_slip_id">
                </div>
              <div class="input-field col s12">
                  <select v-model="period_of_day">
            <option value="" disabled selected>Period of day</option>
            <option value="1">Before 12PM</option>
            <option value="2">Between 12PM - 6PM</option>
            <option value="3">After 6PM</option>
          </select>

              </div>
              <div class="input-field col s12">
                  <input type="text" placeholder="Up to number" v-model="last_house">
              </div>
              <button class="waves-effect waves-light btn" type="submit">SAVE</button>
            </div>
        </form>
      </div>
     </li>
    </ul>
  </div>
</template>

<script>
  import moment from 'moment'
  import axios from 'axios'

  class Errors{

    constructor() {

      this.errors = {};

    }

    has(field){

      //if this.errors contains a "field" property.

      return this.errors.hasOwnProperty(field);

    }

    any(){

      return Object.keys(this.errors).length > 0;

    }

    get(field){

      if(this.errors[field]) {
        return this.errors[field][0];
      }

    }

    record(errors) {

      this.errors = errors;

    }

    clear(field) {

      delete this.errors[field];

    }

  }

  export default{
    props: ['id'],
    data: function() {
      return{

          assignment_slip_id: this.id,
          period_of_day:'',
          last_house:'',
          date:'',
          errors: new Errors()


      }
    },
	 methods: {



      onSubmit: function() {
          axios.post('/api/slips-records-form/add', this.$data)
               .then(this.onSuccess)
               .catch(error => this.errors.record(error.response))
            },

      onSuccess: function(response) {
      this.$emit('recordCreated',1);

        this.period_of_day='';
        this.last_house='';
        this.date='';
        var instance = M.Collapsible.getInstance(elem);
        instance.close(0);
      }


			//	}	else {
/*							swal("There was a problem creating the user, please check if the email or code already exist in the database", {
                      icon: "error",
                      timer:5000,
					});
				}
                })
                .catch(error => {
                  console.log(error);

                /*  swal("There was a problem creating the user, please check if the email or code already exist in the database", {
                    icon: "error",
                    timer:5000,
                  });*/
              //  });
        //},



      },
    mounted: function(){
      var vm = this
      $('input[id='+this.assignment_slip_id+']').datepicker({
        format: 'dd/mm/yyyy',
        onSelect: function(date) {
          vm.date = moment(date).format('DD/MM/YYYY')
        }
      })
    },



  }
</script>
