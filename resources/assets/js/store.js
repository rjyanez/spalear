import { getLocalUser } from "./helpers/auth";

const user = getLocalUser();

export default {
  state: {
    currentUser: user,
    isLoggedIn: !!user,
  },
  getters: {
    isLoggedIn(state) {
      return state.isLoggedIn;
    },
    currentUser(state) {
      return state.currentUser;
    },
    isRole(state) {
      return (code) => state.currentUser.roles.map(el => el.key).includes(code.toUpperCase())
    }
  },
  mutations: {
    loginSuccess(state, payload) {
      state.isLoggedIn = true;
      state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});
      localStorage.setItem("user", JSON.stringify(state.currentUser));
    },
    refresh(state) {
      axios.get(`/api/auth/user?token=${state.currentUser.token}`).then((response) => {
        state.currentUser = Object.assign({}, response.data, {token: state.currentUser.token});
        localStorage.setItem("user", JSON.stringify(state.currentUser));
      })
    },

    logout(state) {
      localStorage.removeItem("user");
      state.isLoggedIn = false;
      state.currentUser = null;
    },
  },
  actions: {
    sendGet({ commit, state}, {url, auth = false}){
      let token = ''
      if(state.isLoggedIn) token = `?token=${state.currentUser.token}`
      return new Promise((resolve, reject) => {
        axios.get(`${window.location.origin}${url}${token}`).then(res => {
          resolve(res.data)
        })
        .catch(error => {
          reject(error)
        })
      })
    },
    sendPost({ commit, state}, {url, data, auth = false}){
      let headers = {
        'content-type': 'multipart/form-data'
      }
      let token = ''
      if(state.isLoggedIn) token = `?token=${state.currentUser.token}`
    
      return new Promise((resolve, reject) => {
        axios.post(`${window.location.origin}${url}${token}`, data,{headers}).then(res => {
        resolve(res.data)
        })
        .catch(error => {
          reject(error)
        })
      })
    }
  }
};