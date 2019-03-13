<template>
  <div>
    <header-user name="Users List"/>
    <div class="container-fluid mt--7">
      <div class="card shadow">
        <div class="card-header border-0">
          <div class="row align-items-center">
            <div class="col-6">
              <div class="navbar-search navbar-search-light">
                <div class="form-group mb-0">
                  <div class="input-group input-group-alternative shadow ">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input v-model="search" class="form-control" placeholder="Search" type="text">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6 text-right">
              <router-link class="btn  btn-primary" :to="`/user/create`">
                Add New
              </router-link>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th scope="col" @click="sort('name')">Fullname<i class="fas fa-sort-alpha-down float-right"></i></th>
                  <th scope="col" @click="sort('email')">Email<i class="fas fa-sort-alpha-down float-right"></i></th>
                  <th scope="col">Rol</th>
                  <th scope="col">Country</th>
                  <th scope="col">Time Zone</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in (sortedActivity, filteredList)" :key="index">
                  <td>{{ item.name }}</td>
                  <td>{{ item.email }}</td>
                  <td>{{ item.rol }}</td>
                  <td>{{ item.country }}</td>
                  <td>{{ item.timeZone }}</td>
                  <td class="text-right">
                    <router-link class="btn btn-sm btn-icon-only text-light" :to="`/user/${item.id}`">
                      <i class="fas fa-ellipsis-v"></i>
                    </router-link>
                  </td>
                </tr>
              </tbody>
            </table>
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
import headerUser from './header'

export default {
  components: {
  headerUser
  },
  data(){
  return {
    users: [],
    currentSort:'name',
    currentSortDir:'asc',
    search: '',
    searchSelection: '',
    pageSize: 5,
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
    if((this.currentPage*this.pageSize) < this.users.length) this.currentPage++;
  },
  prevPage:function() {
    if(this.currentPage > 1) this.currentPage--;
  }
  },
  computed: {
  sortedActivity:function() {
    return this.users.sort((a,b) => {
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
    let list = this.users.filter((data) => {
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
  this.$store.dispatch('sendGet', { url:`/api/user/list`, auth: true}).then(res => {
    if(res.data.users) this.users = JSON.parse(res.data.users)
  })
  }
}
</script>
