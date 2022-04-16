import { getLocalUser } from "./helpers/auth";
const user = getLocalUser();
export default {
    state: {
        currentUser: user,
        isLoggerIn: !!user,
        loading: false,
        auth_error: null,
        tasks:[],
        welcomeMessage: 'Welcome to my vue app'
    },
    getters: {
        welcome(state){
            return state.welcomeMessage
        },
        isLoading(state){
            return state.loading
        },
        isLoggerIn(state){
            return state.isLoggerIn
        },
        currentUser(state){
            return state.currentUser
        },
        auth_error(state){
            return state.auth_error
        },
        getTasks(state) {
            return state.tasks
        },

    },
    mutations: {
        login(state){
            state.loading = true
            state.auth_error = null
        },
        loginSuccess(state, payload){
            state.auth_error = null
            state.isLoading = true
            state.loading = false
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token})
            localStorage.setItem("user", JSON.stringify(state.currentUser))
        },
        loginFailed(state, payload){
            state.loading = false
            state.auth_error = payload.error
        },
        logout(state){
            localStorage.removeItem("user")
            state.isLoggerIn = false
            state.currentUser = null
        },
        setTasks(state,payload) {
            state.tasks = payload
        }
    },
    actions: {
        login(context){
            context.commit("login")
        }
    }
}