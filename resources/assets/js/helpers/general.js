import { getLocalUser } from "./auth";

export function  formDataToJson(el){
  const formData = new FormData(el)
  let jsonObject = {}
  for(const [key, value]  of formData.entries()) {
    jsonObject[key] = value
  }
  return jsonObject			
}

export function addJsonToFormData(Obj, formData = null){
  if(!formData) formData = new FormData()
  for(let key in Obj){
    formData.append(key, Obj[key])
  }
  return formData
}

export function  formData(el){
  return new FormData(el)		
}

export function initialize(store, router) {
  router.beforeEach((to, from, next) => {
    const currentUser = store.state.currentUser;
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const deniedRoles = to.matched.some(record => (record.meta.deniedRoles)? record.meta.deniedRoles.includes(currentUser.rol_code) : false)
    if(requiresAuth && !currentUser) {
      next('/');
    } else if(to.path == '/login' && currentUser) {
      next('/dashboard');
    } else if(deniedRoles){
        next('/');
    } else {    
      next();
    }
  });
  
  axios.interceptors.response.use(null, (error) => {
    if (error.resposne.status == 401) {
      store.commit('logout');
      router.push('/');
    }

    return Promise.reject(error);
  });

  if (store.getters.currentUser) {
    setAuthorization(store.getters.currentUser.token);
  }
}

export function setAuthorization(token) {
  axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}


export function removeEmpty(obj) {
  Object.keys(obj).forEach(key => {
    if (obj[key] && typeof obj[key] === 'object') removeEmpty(obj[key]);
    else if (obj[key] === undefined) delete obj[key];
  });
  return obj;
}

export function formatDateToDataBase(time){

  let val = []

  for (let i in time) {
    if(typeof time[i] === 'string') {
      val.push(datesFrontendFormater(time[i]))
    } else {
      for(let j in time[i]){
        if(typeof time[i][j] === 'string'){
          val.push(datesFrontendFormater(time[i][j], i))
        }
      }
    }
  }
  return val

}

export function datesFrontendFormater(time, key = null){
  let d = new Date(time),
  date = false ,
  year = false ,
  month = false ,
  week = false ,
  day = false ,
  hour = false ,
  min = false

  if(d instanceof Date && !isNaN(d)){
    date = d
    year = parseInt(d.getFullYear())
    month = parseInt(d.getMonth())
    week = parseInt(d.getDay())
    day = parseInt(d.getDate())
    hour  = parseInt(d.getHours())
    min  = parseInt(d.getMinutes())
  } else {
    time = time.split(':')
    week = parseInt(key)
    hour = parseInt(time[0])
    min = parseInt(time[1])
  }
  return {date, year, month, week, day, hour, min}
}

export function datesBackendFormater(time){
  return `${time.year}-${1 + parseInt(time.month)}-${time.day} ${time.hour}:${time.min}:00`
}