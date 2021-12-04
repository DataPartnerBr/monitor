<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row ">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ logs }}</h3>

                        <p>Log Atividades</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
<!--                    <a href="/companies" class="small-box-footer">Acessar Empresas<i class="fas fa-arrow-circle-right"></i></a>-->
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-cyan">
                    <div class="inner">
                        <h3>{{ urls }}</h3>

                        <p>URLs cadastradas</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
<!--                    <a href="/entry/mailings" class="small-box-footer">Acessar Mailings<i class="fas fa-arrow-circle-right"></i></a>-->
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-gradient-green">
                    <div class="inner">
                        <h3>{{ urlsOk }}</h3>

                        <p>Sites Status OK</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
<!--                    <a href="/contacts" class="small-box-footer">Acessar Contatos <i class="fas fa-arrow-circle-right"></i></a>-->
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-gradient-olive">
                    <div class="inner">
                        <h3>{{ users }}</h3>

                        <p>Usuários cadastrados</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
                <!-- Left col -->
                <div class="col-md-8">
                    <!-- MAP & BOX PANE -->
                    <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Últimos Logs gravados</h3>

                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
<!--                        {{ lastLogs }}-->
                        <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Descrição</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
<!--                            { "id": 7, "type": "warning", "subject": "URL https://googlemais.com foi deletada.", "url": "http://127.0.0.1:8000/api/v1/monitor/16", "method": "DELETE", "ip": "127.0.0.1", "agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36", "user_id": 1, "created_at": "2021-12-03T20:07:27.000000Z", "updated_at": "2021-12-03T20:07:27.000000Z" },-->
                                <tr v-for="ll in lastLogs" :key="ll.id">
                                    <td>{{ ll.id }}</td>
                                    <td v-if="ll.type == 'error'"><span class="text-center badge bg-danger text-uppercase">{{ ll.type }}</span></td>
                                    <td v-if="ll.type == 'info'"><span class="text-center badge bg-info text-uppercase">{{ ll.type }}</span></td>
                                    <td v-if="ll.type == 'warning'"><span class="text-center badge bg-warning text-uppercase">{{ ll.type }}</span></td>
                                    <td>{{ ll.subject}}</td>
                                    <td>
                                        <div class="sparkbar" data-color="#00a65a" data-height="20">{{ ll.created_at | brDate }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <!-- /.card -->
            </div>
            <!-- /.col -->

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Últimas Urls Adicionadas</h3>
<!--                            {{ lastUrls}}-->
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>URL</th>
                                    <th>status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="lu in lastUrls" :key="lu.id">
                                    <td>{{lu.id}}</td>
                                    <td><a href="">{{lu.url}}</a></td>
                                    <td v-if="lu.status_code === 200"><span class="badge badge-success">{{lu.status_code}}</span></td>
                                    <td v-if="lu.status_code !== 200"><span class="badge badge-danger">{{lu.status_code}}</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
</template>

<script>
    export default {
        data(){
            return {
                users: [],
                logs: [],
                urls: [],
                urlsOk: [],
                lastLogs: [],
                lastUrls: [],
            }
        },
        methods: {
            loadData(){
                this.$Progress.start();
                this.$loading(true);
                axios.get("api/v1/dashboard")
                    .then(response => {
                        this.users = response.data.usersCount;
                        this.logs = response.data.logsCount;
                        this.urls = response.data.urlsCount;
                        this.urlsOk = response.data.urlsOkCount;
                        this.lastLogs = response.data.lastLogs;
                        this.lastUrls = response.data.lastUrls;
                        // console.log('this.dataDashboard--->' + JSON.stringify(response.data));
                        this.$loading(false);
                        this.$Progress.finish();
                    })
                    // eslint-disable-next-line
                    .catch(errors => {
                        console.log(errors);
                        this.$loading(false);
                        this.$Progress.finish();
                    });
            },
        },

        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.loadData();
        },

    }
</script>
