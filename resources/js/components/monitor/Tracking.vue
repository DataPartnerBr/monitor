<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Example Component</div>

                        <div class="card-body">
                            I'm an example component.
                            <code><pre>{{ urls }}</pre></code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
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
        created() {
            axios.get('api/v1/tracking-monitor').then(({ data }) => (this.urls = data.data));

        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
