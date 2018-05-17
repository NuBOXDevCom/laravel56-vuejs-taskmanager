<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button @click="initAddTask()" class="btn btn-primary btn-sm float-right">+ Add New Task
                        </button>
                        My Tasks
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="tasks.length > 0">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(task, index) in tasks">
                                <td>{{ index + 1 }}</td>
                                <td>{{ task.name }}</td>
                                <td>{{ task.description }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm" @click="initUpdate(index)">Edit</button>
                                    <button class="btn btn-danger btn-sm" @click="deleteTask(index)">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="add_task_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input class="form-control" type="text" id="name" placeholder="Task Name"
                                   v-model="task.name">
                        </div>
                        <div class="form-group">
                            <label for="description"></label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control"
                                      placeholder="Task Description" v-model="task.description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" @click="createTask()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="update_task_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" placeholder="Task Name" class="form-control"
                                   v-model="task_editing.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea cols="30" rows="5" class="form-control"
                                      placeholder="Task Description" v-model="task_editing.description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateTask()" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                task: {
                    name: '',
                    description: ''
                },
                errors: [],
                tasks: [],
                task_editing: {}
            }
        },
        mounted () {
            this.readTasks()
        },
        methods: {
            initAddTask () {
                this.errors = []
                $('#add_task_model').modal('show')
            },
            createTask () {
                axios.post('/task', {
                    name: this.task.name,
                    description: this.task.description
                })
                    .then(response => {
                        this.reset()
                        this.tasks.push(response.data.task)
                        $('#add_task_modal').modal('hide')
                    })
                    .catch(error => {
                        this.errors = []
                        let errorsReturned = error.response.data.errors
                        if (errorsReturned.name) {
                            this.errors.push(errorsReturned.name[0])
                        }
                        if (errorsReturned.description) {
                            this.errors.push(errorsReturned.description[0])
                        }
                    })
            },
            reset () {
                this.task.name = ''
                this.task.description = ''
            },
            readTasks () {
                axios.get('/task')
                    .then(response => {
                        this.tasks = response.data.tasks
                    })
            },
            initUpdate (index) {
                this.errors = []
                $('#update_task_model').modal('show')
                this.task_editing = this.tasks[index]
            },
            updateTask () {
                axios.patch('/task/' + this.task_editing.id, {
                    name: this.task_editing.name,
                    description: this.task_editing.description
                })
                    .then(response => {
                        $('#update_task_model').modal('hide')
                    })
                    .catch(error => {
                        this.errors = []
                        let errorsReturned = error.response.data.errors
                        if (errorsReturned.name) {
                            this.errors.push(errorsReturned.name[0])
                        }
                        if (errorsReturned.description) {
                            this.errors.push(errorsReturned.description[0])
                        }
                    })
            },
            deleteTask(index){
                let confirmation = confirm('Do you ready want to delete this task?')
                if(confirmation === true){
                    axios.delete('/task/' + this.tasks[index].id)
                        .then(response => {
                            this.tasks.splice(index, 1);
                        })
                        .catch(error => {
                            this.errors.push('Unable to destroy this task on server')
                        })
                }
            }
        }
    }
</script>

<style scoped>

</style>
