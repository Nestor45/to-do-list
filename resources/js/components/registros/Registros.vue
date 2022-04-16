<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <v-card class="mt-4">
                    <v-toolbar flat>
                        <v-btn class="ma-2" :loading="loading" :disabled="loading" color="primary" @click="pendingToDo">
                            Tareas Pendientes
                        </v-btn>
                        <v-btn class="ma-2"  :loading="loading1" :disabled="loading1" @click="toDoFinished">
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
                    <div class="col-md-12">
                        <div class="text-left mt-2">
                            <p class="text-h4 text--primary text-center">TAREAS</p>
                        </div>
                        <div class="mt-4 col-md-12">
                            <v-data-table
                                :headers="encabezadoTasks"
                                :items="tasksInfoPend"
                                :items-per-page="itemsPerPage"
                                no-data-text="No se encontraron Datos"
                                hide-default-footer
                                @page-count="pageCount = $event"
                                :page.sync="page"
                            >
                            <template v-slot:[`item.actions`]="{item}">
                                <v-icon
                                    small
                                    color="0D3E67"
                                    @click="editItem(item)"
                                >
                                    mdi-pencil
                                </v-icon>
                                <v-icon
                                    small
                                    color="#BC2222"
                                    @click="deleteItem(item)"
                                >
                                    mdi-delete
                                </v-icon>
                                
                                <v-icon small color="#0D3E67" @click="finished(item)" >
                                    mdi-checkbox-marked-circle-outline
                                </v-icon>
                            </template>
                            </v-data-table>
                            <div class="text-center pt-2">
                                <v-pagination
                                    v-model="page"
                                    :length="pageCount"
                                ></v-pagination>
                            </div>
                        </div>
                    </div>
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
            page: 1,
            pageCount: 1,
            itemsPerPage: 4,
            dialog: false,
            dialogDelete: false,
            delete: false,
            loader: null,
            loading: false,
            loading1: false,
            loading2: false,
            dialogTask: false,
            bandTask: false,
            there_is_task: false,
            tasks: [],
            editedIndex: -1,
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
            encabezadoTasks: [
                {
                    text: 'Titulo', value: 'title'
                },
                {
                    text: 'Descripcion', value: 'description'
                },
                {
                    text: 'Creado', value: 'created_at'
                },
                {
                    text: 'Actulizado', value: 'updated_at'
                },
                {   
                    text: 'Acciones', value: 'actions', sortable: false 
                },
            ]
        }
    },
    computed: {
        tasksInfoPend() {
            return this.$store.getters.getTasks
        },
    },
    created(){
        this.infoUser()
        this.pendingToDo()
    },
    methods: {
        async finished(item){
            try {
                if (confirm('Realmente Desea Terminar esta Tarea')){
                    let response = await axios.post('/api/task/update',item)
                    if (response.status === 200) {
                        console.log(response)
                        this.pendingToDo()
                    } else {
                        alert("algo esta mal al Actualizar")
                    }
                }
            } catch (error) {
                console.log("finished",error)
            }
        },
        async deleteItem(item){
            this.dialogDelete = true
            try {
                if (confirm('Realmente Desea Eliminar esta Tarea')){
                    let response = await axios.post('/api/task/delete',item)
                    if (response.status === 200) {
                        this.pendingToDo()
                    } else {
                        alert("algo esta mal al Eliminar")
                    }
                }  
            } catch (error) {
                console.log("deleteItem",error)
            }
        },
        async toDoFinished(){
            try {
                let response = await axios.post('/api/tasks/ter',this.task)
                if (response.data.message === "No hay nada en la BD") {
                    this.there_is_task = false
                    console.log("no hay preguntas")
                } else {
                    this.there_is_task = true
                    this.tasks = response.data.tasks
                    this.$store.commit('setTasks', this.tasks)
                }
            } catch (error) {
                console.log("pendingToDo",error)
            }
        },
        async pendingToDo() {
            console.log("Btn pedientes")
            try {
                let response = await axios.post('/api/tasks',this.task)
                if (response.data.message === "No hay nada en la BD") {
                    this.there_is_task = false
                    console.log("no hay preguntas")
                } else {
                    this.there_is_task = true
                    this.tasks = response.data.tasks
                    this.$store.commit('setTasks', this.tasks)
                }
            } catch (error) {
                console.log("pendingToDo",error)
            }
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
                    this.pendingToDo()
                    this.dialogTask = false
                } else {
                    alert("algo esta mal al registrar")
                }
            } catch (error) {
                alert("algo esta mal al registrar")
            }
        }
    }
}
</script>
