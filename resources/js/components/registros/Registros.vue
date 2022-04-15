<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <v-card class="mt-4">
                    <v-toolbar flat>
                        <v-btn class="ma-2" :loading="loading" :disabled="loading" color="primary" @click="pendingToDo">
                            Tareas Pendientes
                        </v-btn>
                        <v-btn class="ma-2"  :loading="loading1" :disabled="loading1" @click="finished">
                            Tareas Terminadas
                            <template v-slot:loader> <span>Cargando...</span> </template>
                        </v-btn>
                        <v-divider class="mx-2" inset vertical ></v-divider>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialogTask" max-width="600px">
                            <template v-slot:activator="{on, attrs}">
                                <v-btn
                                    v-bind="attrs"
                                    v-on="on"
                                    color="success"
                                >
                                    Agregar Tareas
                                    <v-icon right dark> mdi-cloud-upload </v-icon>
                                </v-btn>
                            </template>
                            <template >
                                <v-card>
                                    <v-card-title>
                                        <span class="headline text-center">Nuevo Imputado Fisico</span>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-form class="col-12" ref="formTask">
                                                    <v-col cols="12">
                                                        <v-text-field v-model="task.title" :rules="titleRules" label="Titutlo:" required></v-text-field>
                                                    </v-col>
                                                    <v-col cols="12">
                                                        <v-text-field v-model="task.description" :rules="descriptionRules" label="Descripcion:" required></v-text-field>
                                                    </v-col>
                                                </v-form>
                                                <v-card-actions class="mt-10">
                                                    <v-spacer></v-spacer>
                                                    <div class="text-center">
                                                        <v-btn color="red" text @click="volverPrincipal">Cancelar</v-btn>
                                                        <v-btn color="green" text @click="nuevoTask">Agregar Tarea Nueva</v-btn>
                                                    </div>
                                                </v-card-actions>
                                            </v-row>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                            </template>
                        </v-dialog>
                    </v-toolbar>
                </v-card>
                
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'registrar',
    data() {
        return {
            dialog: false,
            dialogDelete: false,
            loader: null,
            loading: false,
            loading1: false,
            loading2: false,
            dialogTask: false,
            bandTask: false,
            task: {
                title: '',
                description:'',
                status:'pendiente',
                user_id:'',
            },
            titleRules: [
                v => !!v || 'El titulo es requerido',
            ],
            descriptionRules: [
                v => !!v || 'La descripción es requerida',
            ],
        }
    },
    computed: {

    },
    created(){
        this.infoUser()
    },
    methods: {
        finished(){
            
            console.log("Btn terminados")
        },
        pendingToDo(){
            console.log("Btn pedientes")
        },
        volverPrincipal() {
            console.log("Btn volver")
            this.dialogTask = false
        },
        infoUser(){
            let $user = this.$store.getters.currentUser
            this.task.user_id = $user.id 
        },
        async nuevoTask() {
            this.bandTask = true
            console.log("TASK:",this.task)
            try {
                let response = await axios.post('/api/task/create', this.task)
                if (response.status === 200) {
                    console.log(response)
                    this.dialogTask = false
                } else {
                    alert("algo esta mal al registrar")
                }
            } catch (error) {
                alert("algo esta mal al registrar")
            }
        },
        limpiarTask() {
            this.task.title = null,
            this.task.description = null
        }
    }
}
</script>
