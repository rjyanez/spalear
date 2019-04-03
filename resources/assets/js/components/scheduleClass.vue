<template>
  <div class="text-center">
    <div class="row">
      <div class="col">
        <h3 class="text-muted mt-4 mb-2">
          Select the classes you want to schedule
        </h3>
        <calendar
          minToday="true"
          :byShift="(hours.length > 5)"
          isEdit="true"
          :hours="hours"
          :timeAllowedDates="teacher.timeSchedule"
          :timeSelectedDates="timeSelectedDates"
          @timeSelectedDate="setTimeSelectedDates($event) "       
        />
      </div>
    </div>
    <div class="row">
      <div class="col">
        <h3 class="text-muted mt-4 mb-2"> Class Type</h3>
        <div class="btn-group w-100" role="group">
          <button 
            type="button" 
            :class="{'btn btn-primary w-50':true, 'disabled':(type != value)}" 
            v-for="(name, value) in list.type" 
            :key="value"
            @click="type = value"
          >
            {{ name }}
          </button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <h3 class="text-muted mt-4 mb-2">
          Select your lesson
        </h3>
        <select          
          :class="{ 'custom-select form-control': true}"
          v-model="index"
        >
          <option 
            v-for="(value, index) in list.lessons" 
            :value="index" 
            :key="index" 
          >
            Lesson {{ value.name }}
          </option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="card card-stats my-4 ">
          <div class="card-body">
            <div class="row">
              <div class="col-2">
                <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                  <strong>{{ type }}</strong> 
                </div>                
              </div>
              <div class="col text-left">
                <div v-show="type === 'GR'" class="col ">
                  <h5 class="card-title text-uppercase text-muted mb-0"> Lesson {{ lesson.name }}</h5>
                  <span class="h4 font-weight-bold mb-0">{{ lesson.description }}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <p class="mt-3 mb-0 text-muted text-sm" v-for="(item, i) in timeSelectedDates" :key="i">
                  <span class="text-success mr-2">{{ 1 + i }}</span>
                  <span class="text-nowrap">Since last month</span>
                </p>                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</template>

<script>
import calendar from './calendar'

export default {
  name: 'schedule-class',
  components: {
    calendar
  },
  props: [
    'teacher'
  ],
  data(){
    return {
      list:{
        type: {
          GR: 'Gramatical',
          CN: 'Conversational'
        },
        lessons:[]
      },
      type: 'CN',
      index: 0,
      lesson: {},
      timeSelectedDates: []
    }
  },
  computed: {
    hours(){
      if (this.teacher.timeSchedule){
        let hours = [], values =this.teacher.timeSchedule.map(el => el.hour)
        for (const key in values) {
          if(!hours.includes(values[key])) hours.push(values[key])
        }
        return hours 
      } else { 
        return [[0,24]] 
      }
    },
    currentUser() {
      return this.$store.getters.currentUser;
    }
  },
  mounted(){
    this.$store.dispatch('sendGet', { url:`/api/lesson/${this.currentUser.meta_data.level}`, auth: true}).then(res => {
        if(res.data.lessons){ 
          this.list.lessons = res.data.lessons
          this.lesson = this.list.lessons[0]  
        }
    })

  },
  methods:{
    setTimeSelectedDates(event){
      let index ,
      find = this.timeSelectedDates.some((el, i) => {
        index = i
        return (
          el.year === event.year &&  
          el.month === event.month && 
          el.week === event.week &&
          el.day === event.day && 
          el.hour === event.hour && 
          el.min === event.min
        )
      });

      (!find)? this.timeSelectedDates.push(event) : this.timeSelectedDates.splice(index,1)
    },
  },
  watch:{
    index(val){
      this.lesson = this.list.lessons[val]
    }
  }
}
</script>
