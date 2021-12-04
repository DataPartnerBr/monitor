<template>
    <section class="content">'
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img v-if="form.avatar_url" :src="form.avatar_url" class="img-circle" alt="User Image">
                                <img v-if="form.avatar_url == null" src="/images/profile.jpeg" class="img-circle"
                                     alt="User Image">
                            </div>

                            <h3 class="profile-username text-center">{{ form.name }}</h3>
                            <ul class="list-group list-group-unbordered mb-3">
                            </ul>
                            <div class="row justify-content-center">
                                <form>
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input form-control" id="customFile"
                                                   name="avatar"
                                                   ref="file" @change="handleFileObject()"
                                                   :class="{ 'is-invalid': form.errors.has('avatar') }">
                                            <has-error :form="form" field="avatar"></has-error>
                                            <label class="custom-file-label text-left" for="customFile">{{ avatarName
                                                }}</label>
                                        </div>
                                    </div>
                                    <button @click.prevent="updateAvatar" type="submit"
                                            class="btn btn-outline-primary btn-block">Enviar
                                    </button>
                                </form>
                            </div>
                            <!--                <a href="profile/avatar" class="btn btn-primary btn-block"><b>Follow</b></a>-->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active show" href="#activity" data-toggle="tab">Atividade</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Configurações</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#change-password" data-toggle="tab">Trocar
                                    senha</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Activity Tab -->
                                <div class="tab-pane active show" id="activity">
                                    <h3 class="text-center">Ver atividades do usuário</h3>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tipo</th>
                                                <th>Detalhes</th>
                                                <th>Data</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <!--                                            {{ logs }}-->
                                            <tr v-for="log in logs.data" :key="log.id">

                                                <td>{{log.id}}</td>
                                                <td class="text-capitalize">{{log.type? 'Informação' : ''}}</td>
                                                <td class="text-capitalize">{{log.subject}}</td>
                                                <td>{{log.created_at | brDate}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <pagination :data="logs" @pagination-change-page="getResults"></pagination>
                                    </div>
                                </div>
                                <!-- Setting Tab -->
                                <div class="tab-pane" id="settings">
                                    <form @click.prevent="updateInfo" class="form-horizontal">
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label">Nome</label>

                                            <div class="col-sm-12">
                                                <input type="" v-model="form.name" class="form-control" id="inputName"
                                                       placeholder="Name"
                                                       :class="{ 'is-invalid': form.errors.has('name') }">
                                                <has-error :form="form" field="name"></has-error>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail" class="col-sm-2 control-label">E-mail</label>

                                            <div class="col-sm-12">
                                                <input type="email" v-model="form.email" class="form-control"
                                                       id="inputEmail" placeholder="Email"
                                                       :class="{ 'is-invalid': form.errors.has('email') }">
                                                <has-error :form="form" field="email"></has-error>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-success">Atualizar Perfil</button>
                                                <div class="float-right d-sm-inline text-muted">Membro desde: <span>{{ form.created_at | brDate }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Setting Tab -->
                                <div class="tab-pane" id="change-password">
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label for="current_password" class="col-sm-2 control-label">Senha
                                                Atual</label>

                                            <div class="col-sm-12">
                                                <input type="password"
                                                       v-model="form.current_password"
                                                       class="form-control"
                                                       id="current_password"
                                                       placeholder="Senha Atual"
                                                       :class="{ 'is-invalid': form.errors.has('current_password') }"
                                                >
                                                <has-error :form="form" field="current_password"></has-error>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password" class="col-sm-2 control-label">Nova Senha</label>

                                            <div class="col-sm-12">
                                                <input type="password"
                                                       v-model="form.new_password"
                                                       class="form-control"
                                                       id="new_password"
                                                       placeholder="Nova Senha"
                                                       :class="{ 'is-invalid': form.errors.has('new_password') }"
                                                >
                                                <has-error :form="form" field="new_password"></has-error>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="confirm_password" class="col-sm-2 control-label">Nova
                                                Senha</label>

                                            <div class="col-sm-12">
                                                <input type="password"
                                                       v-model="form.confirm_password"
                                                       class="form-control"
                                                       id="confirm_password"
                                                       placeholder="Nova Senha"
                                                       :class="{ 'is-invalid': form.errors.has('confirm_password') }"
                                                >
                                                <has-error :form="form" field="confirm_password"></has-error>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-12">
                                                <button @click.prevent="updatePassword" type="submit"
                                                        class="btn btn-success">Atualizar Senha
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- end tabs -->
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                apiUrl: "/api/v1/profile",
                logs: {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    created_at: '',
                    avatar_url: ''
                }),
                avatar: null,
                avatarName: null,
            }
        },
        mounted() {
            console.log('Profile Component is mounted!.')
        },
        methods: {
            handleFileObject() {
                this.avatar = this.$refs.file.files[0]
                this.avatarName = this.avatar.name
            },
            updateAvatar() {
                this.$Progress.start();
                let formData = new FormData()
                formData.append('avatar', this.avatar)
                console.log('avatar', this.avatar);
                _.each(this.formData, (value, key) => {
                    formData.append(key, value)
                })
                axios.post(this.apiUrl + '/avatar', formData, {
                    headers: {
                        'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
                    }
                }).then(response => {
                    console.log('response--->', response);
                    console.log('response.data.success--->', response.data.data);
                    location.reload();
                    this.$Progress.finish();
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    });

                })
                    .catch((response) => {
                        this.$Progress.fail();

                        Toast.fire({
                            icon: 'error',
                            title: 'Algo deu errado! Por faver, tente de novo'
                        });
                    });

                this.getProfile();


            },
            getProfile() {
                axios.get(this.apiUrl)
                    .then(({data}) => (
                        this.form.fill(data.data)
                    ));
            },

            updateInfo() {
                this.$Progress.start();
                if (this.form.password == '') {
                    this.form.password = undefined;
                }
                this.form.put(this.apiUrl)
                    .then((data) => {
                        this.$Progress.finish();
                        Toast.fire({
                            icon: 'success',
                            title: data.data.message
                        });
                    })
                    .catch((data) => {
                        this.$Progress.fail();

                        Toast.fire({
                            icon: 'error',
                            title: 'Algo deu errado! Por faver, tente de novo'
                        });
                    });
            },
            updatePassword() {
                this.$Progress.start();
                this.form.post('api/v1/change-password')
                    .then((data) => {
                        //  Fire.$emit('AfterCreate');
                        this.$Progress.finish();
                        this.form.current_password = '';
                        this.form.new_password = '';
                        this.form.confirm_password = '';

                        Toast.fire({
                            icon: 'success',
                            title: data.data.message
                        });
                    })
                    .catch(() => {
                        this.$Progress.fail();

                        Toast.fire({
                            icon: 'error',
                            title: 'Algo deu errado! Por faver, tente de novo'
                        });
                    });
            },
            loadLogs() {
                // if(this.$gate.isAdmin()){
                // }
                axios.get('api/v1/logs').then(({data}) => (this.logs = data.data,
                    console.log('this.loadLogs', this.logs)));
            },
            getResults(page = 1) {
                this.$Progress.start();
                axios.get('api/v1/logs?page=' + page).then(({data}) => (this.logs = data.data));
                this.$Progress.finish();
            },
        },
        created() {

            this.$Progress.start();

            axios.get(this.apiUrl)
                .then(({data}) => (this.form.fill(data.data)));

            this.loadLogs();
            this.$Progress.finish();
        }
    }
</script>
