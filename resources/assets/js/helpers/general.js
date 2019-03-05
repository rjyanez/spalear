import { getLocalUser } from "./auth";

export function  formDataToJson(el){
  const formData = new FormData(el)
  let jsonObject = {}
  for(const [key, value]  of formData.entries()) {
    jsonObject[key] = value
  }
  return jsonObject			
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
