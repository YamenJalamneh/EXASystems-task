<template>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex align-items-center">
                        <div class="form-group col-lg-3">
                            <label>From</label>
                            <Datepicker v-model="start_date" placeholder="Select Date" autoApply keepActionRow
                                        :closeOnAutoApply="false"></Datepicker>
                            <p class="error-block" v-if="errors.start_date">
                                <small class="text-danger">{{ errors.start_date[0] }}</small>
                            </p>
                        </div>
                        <div class="form-group col-lg-3">
                            <label>To</label>
                            <Datepicker v-model="end_date" placeholder="Select Date" autoApply keepActionRow
                                        :closeOnAutoApply="false"></Datepicker>
                            <p class="error-block" v-if="errors.end_date">
                                <small class="text-danger">{{ errors.end_date[0] }}</small>
                            </p>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="day">Day</label>
                            <select v-model="day" class="form-control form-control-lg" id="day">
                                <option value="" selected hidden>Select Day</option>
                                <option value="monday">Monday</option>
                                <option value="tuesday">Tuesday</option>
                                <option value="wednesday">Wednesday</option>
                                <option value="thursday">Thursday</option>
                                <option value="wednesday">Wednesday</option>
                                <option value="friday">friday</option>
                                <option value="saturday">saturday</option>
                            </select>
                            <p class="error-block" v-if="errors.day">
                                <small class="text-danger">{{ errors.day[0] }}</small>
                            </p>
                        </div>

                        <div class="col-lg-2">
                            <button type="button" @click.prevent="generateReport" class="btn btn-primary mr-2">
                                Generate
                            </button>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" @click.prevent="exportOrders" class="btn btn-success mr-2">Export
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Product Price</th>
                                <th>Times been ordered</th>
                                <th>Merchant Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order in orders">
                                <td>{{ order.name }}</td>
                                <td>{{ order.price }} $</td>
                                <td>{{ date(order.created_at) }}</td>
                                <td>{{ order.merchant_name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    data() {
        return {
            start_date: null,
            end_date: null,
            day: '',
            orders: [],
            errors: {},
        };
    },
    async created() {
        try {
            const res = await axios.get(`http://127.0.0.1:8000/api/orders`);
            this.orders = res.data.data;
        } catch (error) {
            console.log(error);
        }
    },

    methods: {
        date(date) {
            return moment(date).format('LL')
        },
        async generateReport() {
            const res = await axios.post(`http://127.0.0.1:8000/api/least-ordered-products-per-day`, {
                start_date: this.start_date,
                end_date: this.end_date,
                day: this.day,
            }).then((res) => {
                this.orders = res.data.data;
                this.errors = {};
            })
                .catch((err) => {
                    if (err.response.status == 422) {
                        this.errors = err.response.data.errors;
                    }
                });
        },
        exportOrders() {
            const res = axios.get(`http://127.0.0.1:8000/api/export`, {
                    headers:
                        {
                            'Content-Disposition': "attachment; filename=template.xlsx",
                            'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        },
                    params: {
                        start_date: this.start_date,
                        end_date: this.end_date,
                        day: this.day,
                    },
                    responseType: 'arraybuffer',
                }
            ).then(response => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', 'orders.xlsx');
                document.body.appendChild(fileLink);
                fileLink.click();
            })
                .catch((err) => {
                    this.errors = err.response.data.errors;
                });
        },
    }
};
</script>

<style scoped>

</style>
