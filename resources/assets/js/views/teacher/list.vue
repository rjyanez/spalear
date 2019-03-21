<template>
  <div>
    <header-teacher name="Teachers List"/>
    <div class="container-fluid mt--7">
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
                        <option value="name">Fullname</option>
                        <option value="email">Email</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="row">
            <div class="col-xl-4" v-for="(item, index) in (sortedActivity, filteredList)" :key="index">
              <div class="card border-0 my-4">
                <div class="card-profile-image">
                  <router-link :to="`/teachers/${item.id}`">
                    <img class="card-img-top rounded-circle" :src="`/uploads/avatar/${item.avatar}`" ref="img" style="width: 7rem; height: 7rem">
                  </router-link>
                </div>
                <div class="card-body text-center mt-6">               
                  <h3>
                    {{ item.name }}
                  </h3>
                  <div class="h5"><i class="ni business_briefcase-24 mr-2"></i>{{ item.country }} - {{ item.timeZone }}</div>
                </div>                        
              </div>
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
              <li class="page-item " :class="{ 'disabled' : currentPage === 1 }">
                <button @click="prevPage" class="page-link" aria-label="« Previous"><i class="fas fa-chevron-left fa-lg"></i></button> 
              </li>
              <li class="page-item" :class="{ 'disabled' : currentPage ===  sortedActivity.length }" >
                <button @click="nextPage" class="page-link" aria-label="Next »"><i class="fas fa-chevron-right fa-lg"></i></button>        
              </li>
            </ul>
          </nav>
        </div>
      </div>  
    </div>    
  </div>
</template>
<script>
import headerTeacher from './header'

export default {
  components: {
  headerTeacher
  },
  data(){
  return {
    teachers: [],
    currentSort:'name',
    currentSortDir:'asc',
    search: '',
    searchSelection: '',
    pageSize: 6,
    currentPage: 1,
    total: 0            
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
      let rol = data.rol.toLowerCase().match(this.search.toLowerCase());
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
  this.$store.dispatch('sendGet', { url:`/api/teacher/list`, auth: true}).then(res => {
    if(res.data.teachers) this.teachers = JSON.parse(res.data.teachers)
  })
  }
}
</script>
