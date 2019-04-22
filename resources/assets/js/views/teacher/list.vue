<template>
  <div>
    <header-teacher name="Teachers List"/>
    <div class="container mt--7">
      <div class="card shadow">
        <div class="card-header border-0">
          <div class="row align-items-center">
            <div class="col-6">
              <div class="navbar-search navbar-search-light">
                <div class="form-group">
                  <div class="input-group input-group-alternative shadow ">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input v-model="search" class="form-control" placeholder="Search" type="text">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6 float-right">
              <div class="navbar-search navbar-search-light">
                <div class="form-group">
                  <div class="input-group input-group-alternative shadow ">
                    <div class="input-group-prepend">
                      <button class="btn btn-link" type="button" @click="sort(currentSort)">
                        <i class="fas fa-filter"></i> {{ currentSortDir.toUpperCase() }}
                      </button>
                    </div>
                    <select class="custom-select form-control" @change="sort($event.target.value)">
                        <option value="ranking">Stars</option>
                        <option value="name">Fullname</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="row">
            <div class="col-xl-3" v-for="(item, index) in (sortedActivity, filteredList)" :key="index">
              <teacher 
                :index="index" 
                :teacher="item" 
                @refreshTeacher="refreshTeacher($event, item.id)" 
                @showTeacherTimeSchedule="showModalTime($event)"
              />
            </div>
          </div>
        </div>
        <div class="card-footer py-4">
          <nav class="d-flex justify-content-between" aria-label="...">
            <span>
              {{ rangeList.from }} - {{ rangeList.to }}
              of <b class="text-primary">{{ total }}</b> 
            </span>
            <ul class="pagination" role="navigation">
              <li class="page-item " v-show="currentPage > 1">
                <button @click="prevPage" class="page-link" >
                  <i class="fas fa-chevron-left fa-lg"></i>
                </button> 
              </li>
              <li class="page-item" v-show="rangeList.to < total">
                <button @click="nextPage" class="page-link" >
                  <i class="fas fa-chevron-right fa-lg"></i>
                </button>        
              </li>
            </ul>
          </nav>
        </div>
      </div>  
    </div>
    <modal-time-schedule :options="modal" @close="closeModalTime"/>
  </div>
</template>
<script>
import headerTeacher from './header'
import teacher from './teacher'
import modalTimeSchedule from './modalTimeSchedule'

export default {
  components: {
    headerTeacher,
    teacher,
    modalTimeSchedule
  },
  data(){
    return {
      teachers: [],
      currentSort:'ranking',
      currentSortDir:'desc',
      search: '',
      searchSelection: '',
      pageSize: 8,
      currentPage: 1,
      total: 0,
      modal: {
        show : false,
        teacher : {},
        time: [],
      }, 
    }
  },
  methods:{
    sort:function(s) {
      if(s === this.currentSort) {
      this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
      }
      this.currentSort = s;
    },
    nextPage:function() {
      if((this.currentPage*this.pageSize) < this.teachers.length) this.currentPage++;
    },
    prevPage:function() {
      if(this.currentPage > 1) this.currentPage--;
    },
    showModalTime(event){
      this.modal = { 
        show : true, 
        teacher : event.teacher,
        time: event.time 
      }
    },
    closeModalTime(){
      this.modal = { show : false, teacher : {}, time: [] }
    },
    refreshTeacher(index, id){
      this.$store.dispatch('sendGet', { url:`/api/teacher/${id}`, auth: true}).then(res => {
            this.$set(this.teachers, index, res.data.teacher)
        })
    },
    searchList(){
      const url = (this.$router.currentRoute.name === "teachers.favorite")? `/api/teacher/list/favorite` : `/api/teacher/list`
      this.$store.dispatch('sendGet', { url, auth: true}).then(res => {
        if(res.data.teachers) this.teachers = JSON.parse(res.data.teachers)
      })
    }
  },
  computed: {
    sortedActivity:function() {
      return this.teachers.sort((a,b) => {
      let modifier = 1;
      if(this.currentSortDir === 'desc') modifier = -1;
      if(a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
      if(a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
      return 0;
      }).filter((row, index) => {
      let start = (this.currentPage-1)*this.pageSize;
      let end = this.currentPage*this.pageSize;
      if(index >= start && index < end) return true;
      });
    },
    filteredList () {
      let list = this.teachers.filter((data) => {
        let email = data.email.toLowerCase().match(this.search.toLowerCase());
        let name = data.name.toLowerCase().match(this.search.toLowerCase());
        let rol = data.roles.includes(this.search.toLowerCase()) ;
        let country = data.country.toLowerCase().match(this.search.toLowerCase());
        let timeZone = data.timeZone.toLowerCase().match(this.search.toLowerCase());        
        return email || name || rol || timeZone || country;
      });

      this.total = list.length;

      return list.filter((row, index) => {
        let start = (this.currentPage-1)*this.pageSize;
        let end = this.currentPage*this.pageSize;
        if(index >= start && index < end) return true;
      });
    },
    rangeList() {
      let base = (this.pageSize * this.currentPage)
      let list = (this.sortedActivity, this.filteredList)
      let from = base-(this.pageSize -1)
      let to = (list.length < this.pageSize)? from + (list.length - 1) :  base
      return {from, to}
    }
  },
  mounted(){
    this.searchList()
  },
  watch:{
    $route (to, from){
      this.searchList()
    }
  }
}
</script>
