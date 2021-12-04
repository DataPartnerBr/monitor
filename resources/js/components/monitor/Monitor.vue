<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista das URL's</h3>

                <div class="card-tools">

                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Nova URL
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>URL</th>
                      <th>Descrição</th>
                      <th>Status</th>
                      <th>Atualizado em</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="url in urls.data" :key="url.id">

                      <td>{{url.id}}</td>
                      <td>{{url.url}}</td>
                      <td v-if="url.description">{{ url.description | truncate(30, '...')}}</td>
                      <td v-if="!url.description"></td>
                         <td style="width:100px" v-if="url.status_code === 200"><span class="badge badge-success">{{url.status_code}} Resposta OK</span></td>
                         <td style="width:100px" v-if="url.status_code === null"><span class="badge badge-warning">Em processo</span></td>
                         <td style="width:100px" v-if="url.status_code !== 200 && url.status_code !== null"><span class="badge badge-danger">{{url.status_code}} Não Encontrado</span></td>
                      <td>{{url.updated_at | brDateHour }}</td>
                      <td>
                          <div class="btn-group float-right">
<!--                              <button type="button" class="btn btn-outline-secondary"-->
<!--                                      v-if="url.deleted_at"><i-->
<!--                                  class="fa fa-trash-restore-alt"></i></button>-->
                              <button type="button" class="btn btn-outline-primary"
                                      @click="updateMonitor(url.id)"><i
                                  class="fa fa-sync-alt"></i></button>
                              <button type="button" class="btn btn-outline-dark"
                                      @click="showUrl(url)"><i
                                  class="fa fa-eye"></i></button>
                              <button type="button" class="btn btn-outline-info"
                                      @click="editModal(url)"><i
                                  class="fa fa-edit"></i></button>
                              <button type="button" class="btn btn-outline-danger"
                                      @click="deleteUrl(url.id)"><i
                                  class="fas fa-trash"></i></button>
                          </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="urls" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Adicionar nova URL</h5>
                    <h5 class="modal-title" v-show="editmode">Editar URL</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateUrl() : createUrl()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>URL (precisa iniciar com 'http://' ou 'https://')</label>
                            <input v-model="form.url" type="text" name="url"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('url') }">
                            <has-error :form="form" field="url"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Descrição</label>
                            <input v-model="form.description" type="text" name="description"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                            <has-error :form="form" field="description"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Atualizar</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Criar</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>

        <!-- Modal Show --->
        <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModal"
             aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="showmode ? showModal() : ''">
                        <div class="modal-body">
                            <div class="card card-gray">
                                <div class="card-header">
                                    <h3 class="card-title">Detalhes da URL {{ form.url }}</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Nome URL</label>
                                                <input v-model="form.url" type="text" class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Descrição Status do Código</label>
                                                <input v-model="form.status_code_description" type="text" class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Codigo Status</label>
                                                <input v-model="form.status_code" type="text" class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <label>Descrição</label>
                                            <input v-model="form.decription" type="text" class="form-control"
                                                   disabled>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Timestamp</label>
                                            <input v-model="form.timestamp" type="text" class="form-control"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="mb-5"></div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card card-light">
                                                <div class="card-header">
                                                    <h3 class="card-title">Detalhes da resposta em HTML</h3>
                                                </div>
                                                <div class="card-body">
                                                    <code><pre>{{ form.response | decodeData() }}</pre></code>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    export default {
      components: {
        },
        data () {
            return {
                apiUrl: "api/v1/monitor",
                editmode: false,
                urls : {},
                form: new Form({
                    id : '',
                    url: '',
                    description : '',
                    response : '',
                    status_code : '',
                    status_code_description : '',
                    timestamp : '',
                }),
                // 'url', 'description', 'response', 'status_code', 'status_code_description', 'timestamp'
            }
        },
        methods: {
            updateMonitor(id){
                this.$Progress.start();
                this.$loading(true);
                axios.get("api/v1/tracking-monitor")
                    .then(response => {
                        console.log('updateMonitor OK --->' + id);
                        this.$loading(false);
                        this.$Progress.finish();
                        this.loadUrls();
                        Toast.fire({
                            icon: 'success',
                            title: 'Pronto!'
                        });
                        
                    })
                    // eslint-disable-next-line
                    .catch(errors => {
                        console.log(errors);
                        this.$loading(false);
                        this.$Progress.finish();
                    });
            },
          getResults(page = 1) {
              this.$Progress.start();
              axios.get(this.apiUrl + '?page=' + page).then(({ data }) => (this.urls = data.data));
              this.$Progress.finish();
          },
          loadUrls(){
              this.$Progress.start();
              this.$loading(true);
            // if(this.$gate.isAdmin()){
              axios.get(this.apiUrl).then(({ data }) => (this.urls = data.data));
            // }

              this.$loading(false);
              this.$Progress.finish();
          },
            showUrl(url){
                this.form.reset();
                $('#showModal').modal('show');
                this.form.fill(url);
            },
          editModal(url){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(url);
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
          },
          createUrl(){
              this.$Progress.start();

              this.form.post(this.apiUrl)
              .then((data)=>{
                if(data.data.success){
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                  this.$Progress.finish();
                  this.loadUrls();
                } else {
                  Toast.fire({
                      icon: 'error',
                      title: 'Algum erro ocorreu! Por favor, tente de novo.'
                  });

                  this.$Progress.failed();
                }
              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Algum erro ocorreu! Por favor, tente de novo.'
                  });
              })
          },
          updateUrl(){
              this.$Progress.start();
              this.form.put(this.apiUrl + '/' +this.form.id)
              .then((response) => {
                  // success
                  $('#addNew').modal('hide');
                  Toast.fire({
                    icon: 'success',
                    title: response.data.message
                  });
                  this.$Progress.finish();
                      //  Fire.$emit('AfterCreate');

                  this.loadUrls();
              })
              .catch(() => {
                  this.$Progress.fail();
              });

          },
          deleteUrl(id){
              Swal.fire({
                  title: 'Tem certeza?',
                  text: "Esta ação não pode ser revertida!",
                  showCancelButton: true,
                  cancelButtonText: "Cancelar",
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Sim, apague!'
                  }).then((result) => {
                      // Send request to the server
                        if (result.value) {
                              this.form.delete(this.apiUrl+'/'+id).then(()=>{
                                      Swal.fire(
                                      'Pronto!',
                                      'A url foi apagada.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadUrls();
                              }).catch((data)=> {
                                  if (data.response.status == 403) {
                                      Swal.fire("Atenção!", "Você não tem autorização para eliminar cadastro.", "warning");
                                  } else {
                                      Swal.fire("Atenção!", data.message, "warning");
                                  }
                              });
                        }
                  })
          },

        },
        mounted() {

        },
        created() {
            this.loadUrls();
        },
        filters: {
            truncate: function (text, length, suffix) {
                return text.substring(0, length) + suffix;
            },

            decodeData: function(data){
               return atob(data);
            }
        },
    }
</script>
