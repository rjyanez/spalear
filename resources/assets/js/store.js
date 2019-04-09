import {
	getLocalUser
} from "./helpers/auth";

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
			state.currentUser = Object.assign({}, payload.user, {
				token: payload.access_token,
				session: new Date()
			});
			localStorage.setItem("user", JSON.stringify(state.currentUser));
		},
		refresh(state) {
			axios.get(`/api/auth/user?token=${state.currentUser.token}`).then((response) => {
				state.currentUser = Object.assign({}, response.data, {
					token: state.currentUser.token,
					session: new Date()
				});
				localStorage.setItem("user", JSON.stringify(state.currentUser));
			})
		},
		logout(state) {
			localStorage.removeItem("user");
			state.isLoggedIn = false;
			state.currentUser = null;
		}
	},
	actions: {
		sendGet({
			commit,
			state
		}, {
			url,
			auth = false
		}) {
			let token = ''
			if (state.isLoggedIn) token = `?token=${state.currentUser.token}`
			return new Promise((resolve, reject) => {
				axios.get(`${window.location.origin}${url}${token}`).then(res => {
						resolve(res.data)
					})
					.catch(error => {
						reject(error)
					})
			})
		},
		sendPost({
			commit,
			state
		}, {
			url,
			data,
			auth = false
		}) {
			let headers = {
				'content-type': 'multipart/form-data'
			}
			let token = ''
			if (state.isLoggedIn) token = `?token=${state.currentUser.token}`

			return new Promise((resolve, reject) => {
				axios.post(`${window.location.origin}${url}${token}`, data, {
						headers
					}).then(res => {
						resolve(res.data)
					})
					.catch(error => {
						reject(error)
					})
			})
		},
		sendDelete({
			commit,
			state
		}, {
			url,
			data,
			auth = false
		}) {
			let headers = {
				'content-type': 'multipart/form-data'
			}
			let token = ''
			if (state.isLoggedIn) token = `?token=${state.currentUser.token}`

			return new Promise((resolve, reject) => {
				axios.delete(`${window.location.origin}${url}${token}`, {params: data}, {
						headers
					}).then(res => {
						resolve(res.data)
					})
					.catch(error => {
						reject(error)
					})
			})
		},
		sessiosExpired({
			commit,
			state
		}){
			return new Promise((resolve, reject) => {
				if(state.isLoggedIn){
					const session = new Date(state.currentUser.session),
					now = new Date(),
					diff = now.getTime() - session.getTime();
					if(Math.round(Math.trunc(diff / 60)  / 1000) >= 360){	
						console.log('ya paso el tiempo')				
						resolve()						
					}
					reject()
				} 
				reject()				
			})
		}
	}
};