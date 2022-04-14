<template>
    <v-card
        class="mx-auto my-12"
        max-width="574"
    >
        <v-card-text>
            <v-form ref="form" v-model="valid">
                <v-text-field
                    prepend-icon="mdi-email"
                    name="email"
                    label="Email"
                    type="emial"
                    :rules="emailRules"
                    v-model="form.email"
                ></v-text-field>
                <v-text-field
                    id="password"
                    prepend-icon="mdi-key"
                    name="password"
                    label="Password"
                    :rules="passwordRules"
                    v-model="form.password"
                    :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="show1 = !show1"
                    counter
                    :type="show1 ? 'text' : 'password'"
                ></v-text-field>
                <v-spacer></v-spacer>
                <v-btn
                    :disabled="!valid"
                    text
                    color="teal accent-4"
                    @click="authenticate"
                >
                    Login
                </v-btn>
                <v-btn
                    :disabled="valid"
                    text
                    color="teal accent-4"
                    @click="register"
                >
                    Register
                </v-btn>
            </v-form>
            <template v-if="errorLogin"> 
                <v-alert type="error">
                    Verifique sus datos por favor <br>
                    {{posError}}
                </v-alert>
            </template>
        </v-card-text>
    </v-card>
</template>
<script>
    import {login} from '../../helpers/auth';
    export default {
        name : 'loginView',
        data() {
            return {
                show1: false,
                valid: true,
                errorLogin: false,
                posError: '',
                form : {
                    email: '',
                    password: '',
                    band: false,
                },
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                passwordRules: [
                    v => !!v || 'Password is required',
                ],
                error: null
            }
        },
        
        methods : {
            authenticate() {
                this.$store.dispatch('login')
                login(this.form)
                    .then((res)=>{
                        this.$store.commit("loginSuccess",res)
                        this.$router.push({path: '/'})
                    })
                    .catch((error)=>{
                        this.posError = error
                        this.errorLogin = true
                        this.$store.commit("loginFailed",{error})
                    })
            },
            register() {
                this.$router.push({path: '/register'})
            }
        }
    }
</script>
<style>
    #login {
        background-color: aqua;
    }
</style>