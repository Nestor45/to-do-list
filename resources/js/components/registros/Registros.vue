<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <v-card class="mt-4">
                    <v-toolbar flat>
                        <v-btn class="ma-2" :color="colorBand ? '' : 'primary'" @click="pendingToDo">
                            Tareas Pendientes
                        </v-btn>
                        <v-btn class="ma-2" :color="colorBand ? 'primary' : ''"  @click="toDoFinished">
                            Tareas Terminadas
                        </v-btn>
                        <v-divider class="mx-2" inset vertical ></v-divider>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialog" max-width="600px" persistent>
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
                                        <span class="text-h5">{{ formTitle }}</span>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-form class="col-12" ref="formTask">
                                                    <v-col cols="12">
                                                        <v-text-field v-model="editedItem.title" :rules="titleRules" label="Titutlo:" required></v-text-field>
                                                    </v-col>
                                                    <v-col cols="12">
                                                        <v-text-field v-model="editedItem.description" :rules="descriptionRules" label="Descripcion:" required></v-text-field>
                                                    </v-col>
                                                </v-form>
                                                <v-card-actions class="mt-10">
                                                    <v-spacer></v-spacer>
                                                    <div class="text-center">
                                                        <v-btn color="red" text @click="close">Cancelar</v-btn>
                                                        <v-btn color="green" text @click="save">Guardar Tarea</v-btn>
                                                    </div>
                                                </v-card-actions>
                                            </v-row>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                            </template>
                        </v-dialog>
                        <v-dialog v-model="dialogDelete" max-width="500px">
                            <v-card>
                                <v-card-title class="text-h5">¿Realmente desea eliminar el elemento?</v-card-title>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                                <v-btn color="error" text @click="deleteItemConfirm">Eliminar</v-btn>
                                <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                    <div class="col-md-12">
                        <v-card-title>
                            TAREAS
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Buscar"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-card-title>
                        <div class="mt-4 col-md-12">
                            <v-data-table
                                :search="search"
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
            editedIndex: -1,
            search: '',
            colorBand: false,
            page: 1,
            pageCount: 1,
            itemsPerPage: 4,
            dialog: false,
            dialogDelete: false,
            delete: false,
            dialogTask: false,
            bandTask: false,
            there_is_task: false,
            tasks: [],
            editedTask: false,
            task: {
                status:'',
                user_id:'',
            },
            editedItem: {
                title: '',
                description:'',
                status:'pendiente',
                id_user:'',
                id_task: ''
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
        formTitle () {
            return this.editedIndex === -1 ? 'Nueva Tarea' : 'Editar Tarea'
        },
    },
    created(){
        this.infoUser()
        this.pendingToDo()
    },
    methods: {
        async save(){
            // Funcion que nos permite agregar o actualizar un tarea
            if (this.editedIndex > -1) {
                try {
                    let response = await axios.post('/api/task/edit', this.editedItem)
                    if (response.status === 200) {
                        console.log(response)
                        this.pendingToDo()
                        this.editedTask = false
                    } else {
                        alert("algo esta mal al Editar el registro")
                    }
                } catch (error) {
                    alert("algo esta mal al Editar")
                }
            } else {
                let $user = this.$store.getters.currentUser
                this.editedItem.user_id = $user.id 
                try {
                    let response = await axios.post('/api/task/create', this.editedItem)
                    if (response.status === 200) {
                        this.pendingToDo()
                        this.dialogTask = false
                    } else {
                        alert("algo esta mal al registrar")
                    }
                } catch (error) {
                    alert("algo esta mal al registrar")
                }
            }
            this.close()
        },
        async finished(item) {
            // Funcion que nos permite actualizar a terminado
            console.log(item)
            try {
                if (confirm('Realmente Desea Terminar esta Tarea')){
                    let response = await axios.post('/api/task/update',item)
                    if (response.status === 200) {
                        this.pendingToDo()
                    }else if(response.status === 250){
                        alert("El dato ya ha fue terminado")
                    } else {
                        alert("algo esta mal al Actualizar")
                    }
                }
            } catch (error) {
                console.error("finished",error)
            }
        },
        async toDoFinished(){
            // Funcion para el boton de terminados
            this.colorBand = true
            this.task.status = 'terminado'
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
                console.error("pendingToDo",error)
            }
        },
        async pendingToDo() {
            // Funcion para el boton de pendientes
            this.task.status = 'pendiente'
            this.colorBand = false
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
                console.error("pendingToDo",error)
            }
        },
        infoUser(){
            let $user = this.$store.getters.currentUser
            this.task.user_id = $user.id 
        },
        editItem(item) {
            this.editedIndex = this.tasks.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.task)
                this.editedIndex = -1
            })
        },
        deleteItem(item) {
            this.editedIndex = this.tasks.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },
        async deleteItemConfirm() {
            // Funcion que nos permite eliminar
            try {
                    let response = await axios.post('/api/task/delete',this.editedItem)
                    if (response.status === 200) {
                        this.pendingToDo()
                        this.closeDelete()
                    } else {
                        alert("algo esta mal al Eliminar")
                    }
            } catch (error) {
                console.error("deleteItem",error)
            }
            
        },
        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem)
            this.editedIndex = -1
            })
        },
    }
}
</script>
