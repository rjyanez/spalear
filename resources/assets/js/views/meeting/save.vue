<template>
<div class="text-center mb-3">
  <h3 class="my-5">
    Follow these steps to schedule your class
  </h3>
  <ul class="nav nav-pills nav-pills-circle d-flex justify-content-center" id="step" role="tablist">
    <li class="nav-item text-center">
      <a 
        ref="stepTimeTab"
        class="nav-link rounded-circle" 
        id="step-time-tab" 
        data-toggle="pill" 
        href="#step-time" 
        role="tab" 
        aria-controls="step-time" 
        aria-selected="true"
        title="Time Shedule"
      >
       <span class="nav-link-icon d-block"><strong>1</strong></span>
      </a>       
    </li>
    <li class="nav-item text-center">
      <a 
        class="nav-link rounded-circle" 
        :class="{'disabled bg-light text-muted' : (step < 2)}"
        :aria-disabled="(step < 2)? 'true' : 'false'"
        id="step-type-tab" 
        data-toggle="pill" 
        href="#step-type" 
        role="tab" 
        aria-controls="step-type" 
        aria-selected="false"
        title="Class Type"
      >
        <span class="nav-link-icon d-block"><strong>2</strong></span>
      </a>         
    </li>
    <li class="nav-item text-center">
      <a 
        class="nav-link rounded-circle" 
        id="step-lesson-tab"
        :class="{'disabled bg-light text-muted' : (step < 3)}"
        :aria-disabled="(step < 3)? 'true' : 'false'" 
        data-toggle="pill" 
        href="#step-lesson" 
        role="tab" 
        aria-controls="step-lesson" 
        aria-selected="false"
        title="Lesson"
      >
        <span class="nav-link-icon d-block"><strong>3</strong></span>
      </a>      
    </li>
  </ul>
  <div class="tab-content" id="stepContent">
    <div class="tab-pane fade show active" id="step-time" role="tabpanel" aria-labelledby="step-time-tab">
        <h3 class="text-muted my-5">
          Select the classes you want to schedule
        </h3>
        <calendar
          minToday="true"
          isEdit="true"
          :byShift="(hours.length > 5)"
          :hours="hours"
          :timeDlockedDates="teacher.bookedDates"
          :timeAllowedDates="teacher.timeSchedule"
          :timeSelectedDates="timeSelectedDates"
          @timeSelectedDate="setTimeSelectedDates($event) "       
        />
    </div>
    <div class="tab-pane fade" id="step-type" role="tabpanel" aria-labelledby="step-type-tab">
      <h3 class="text-muted my-3">Select your class type</h3>
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
    <div class="tab-pane fade" id="step-lesson" role="tabpanel" aria-labelledby="step-lesson-tab">
      <h3 class="text-muted my-3">Select your lesson</h3>
      <lessons :list="list.lessons" isSelect="true" @selectLesson="selectLesson($event)"/>
    </div>
  </div>
  <button-submit :loading="loading" :submit="valid" @submit="submit"/>
</div>
</template>

<script>
import {datesBackendFormater, addJsonToFormData} from './../../helpers/general'
import {createMeeting} from './../../sevices/zoom'
import calendar from './../../components/calendar'
import lessons  from './../../components/lessons'
import buttonSubmit from './../../components/buttonSubmit'

export default {
  name: 'meeting-save',
  components: {
    calendar,
    buttonSubmit,
    lessons
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
        lessons: []
      },
      loading: false,
      type: '',
      lesson: {},
      timeSelectedDates: []
    }
  },
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    },
    step(){
      return (this.timeSelectedDates.length > 0)? (this.type === 'GR' ? 3 : 2 ): 1
    },
    valid(){
      return ((this.type === 'CN' && this.step === 2) || ( this.lesson.hasOwnProperty('id') && this.step ===3))? true : false
    },
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
        }
    })

  },
  methods:{
    selectLesson(event){
      this.lesson = event
    },
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
    setInfoEmpty(){
      this.type = ''
      this.lesson = {}
      this.timeSelectedDates = []
    },
    submit(){
      let data = [],
      student=  this.currentUser.id,
      teacher=  this.teacher.id,
      lesson = (this.lesson.hasOwnProperty('id'))? this.lesson.id : '',
      type   =  this.type;
      for (const i in this.timeSelectedDates) {
        let date = datesBackendFormater(this.timeSelectedDates[i])
        
        let meeting = createMeeting('',date)
        .then((res) => {
          console.log('RES',res)
        }).catch((err)=>{
          console.log('ERR', err)
        })
        
        // data.push({
        //   createMeeting
        //   date: datesBackendFormater(this.timeSelectedDates[i])
        // })       
      }     
    
    //   this.loading = true

    //   this.$store.dispatch("sendPost", { 
    //     url: `/api/meeting/`, 
    //     data: addJsonToFormData({
    //       student,
    //       teacher,
    //       lesson,
    //       type,
    //       data: JSON.stringify(data)
    //     }), 
    //     auth: true 
    //   }).then(res => {
    //     this.loading = false
    //     if (res) {  
    //       this.$refs.stepTimeTab.click()      
    //       this.setInfoEmpty()
    //       this.$emit('scheduleClass')
    //       this.$toasted.success(res.message)
    //     } else {
    //       this.$toasted.error("Something went wrong, please try again.")
    //     }
    //   })
    //   .catch(error => {
    //     this.loading = false
    //     this.$toasted.error("Something went wrong, please try again.")          
    //   });     
    // }
    }
  },
  watch: {
    valid(value){
      if(value) this.$toasted.info("You can now schedule your class.")  
    }
  }
}
</script>
