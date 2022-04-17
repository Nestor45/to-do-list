<template>
    <div class="container">
        <v-card
            class="mx-auto"
            max-width="344"
        >
            <v-card-text>
                {{welcome}}
                <div>Word of the Day</div>
                <p class="text-h4 text--primary">
                    el·ee·mos·y·nar·y
                </p>
                <p>adjective</p>
                <div class="text--primary">
                    relating to or dependent on charity; charitable.<br>
                    "an eleemosynary educational institution."
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn
                    text
                    color="teal accent-4"
                    @click="reveal"
                >
                    Ir a Registrar
                </v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script>
    export default {
        name: 'home',
        data() {
            return {
            }            
        },
        computed: {
            welcome() {
                return this.$store.getters.welcome
            }
        },
        methods: {
            reveal(){
                this.$router.push('/registrar')
            }
        }
    }
</script>