<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Atividades do Sistema</h3>
                <div class="card-tools">
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Usuário</th>
                      <th>Tipo</th>
                      <th>Descrição</th>
                      <th>Data</th>
                    </tr>
                  </thead>
                  <tbody>
<!--                  {{ database }}-->
                     <tr v-for="act in activities.data" :key="act.id">

                      <td>{{ act.id }}</td>
                      <td>{{ act.user.name }}</td>
                      <td v-if="act.type == 'error'"><span class="text-center badge bg-danger text-uppercase">{{ act.type }}</span></td>
                      <td v-if="act.type == 'info'"><span class="text-center badge bg-info text-uppercase">{{ act.type }}</span></td>
                      <td v-if="act.type == 'warning'"><span class="text-center badge bg-warning text-uppercase">{{ act.type }}</span></td>
                      <td>{{ act.subject }}</td>
                      <td>{{ act.created_at | brDate }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="activities" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
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
                activities : {},
            }
        },
        methods: {

          getResults(page = 1) {

              this.$Progress.start();
              axios.get('/api/v1/log-activity?page=' + page).then(({ data }) => (this.activities = data.data));
              this.$Progress.finish();
          },
          loadActivities(){
              axios.get('/api/v1/log-activity').then(({ data }) => (this.activities = data.data));
          },
        },
        mounted() {

        },
        created() {
            this.$Progress.start();
            this.loadActivities();
            this.$Progress.finish();
        }
    }
</script>
