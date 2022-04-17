<template>
    <div>
        <v-app-bar color="deep-purple accent-4" dense dark>
            <v-app-bar-nav-icon></v-app-bar-nav-icon>
            <v-toolbar-title>To Do List</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon>
                <v-icon @click="salir">mdi-account-convert</v-icon>
            </v-btn>

        </v-app-bar>
    </div>
</template>
<script>
export default {
    methods: {
        salir() {
            this.$store.commit('logout')
            this.$router.push('/login')
        }
    }
}
</script>