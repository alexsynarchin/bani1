<template>
    <section v-if="loaded">
        <h3 class="text-center">
            Заказ № {{item.id}}
        </h3>
        <div style="width: 120px" class="mb-2">
            <el-alert
                v-if="item.status ==='success'"
                :closable="false"

                title="Оплачен"
                type="success">
            </el-alert>
            <el-alert
                v-if="item.status ==='cancelled'"
                :closable="false"
                title="Отменен"
                type="error">
            </el-alert>
        </div>
        <div class="mb-2">
            <label>
                Дата заказа:
            </label>
            {{item.created_at}}
        </div>
        <div class="mb-2">
            <label>
                Имя:
            </label>
            {{order.client.name}}
        </div>
        <div class="mb-2">
            <label>
                Телефон:
            </label>
            {{order.client.phone}}
        </div>
        <div class="mb-2">
            <label>
                Дата брони:
            </label>
            {{item.date}}
        </div>
        <div class="mb-2">
            <label>
                Начало брони:
            </label>
            {{item.start}}
        </div>
        <div class="mb-2">
            <label>
                Окончание брони:
            </label>
            {{item.end}}
        </div>
        <div class="d-flex flex-wrap mb-4">
            <div v-for="reservation in order.reservations" style="padding: 10px 20px;
             border: 1px dashed #000000;
             margin-right: 10px;
            border-radius: 6px;">
                <span v-if="reservation.reservationable_type === 'App\\Models\\Cabinet'">
                    Кабинка
                </span>
                <span v-if="reservation.reservationable_type === 'App\\Models\\Place'">
                    Место
                </span>

                {{reservation.reservationable.number}}
            </div>
        </div>


        <h4 class="mb-3">
            Конфликтующие заказы
        </h4>
        <section v-for="(conflict) in conflict_orders" class="mb-2">
            <h3 class="text-center">
                Заказ № {{conflict.id}}
            </h3>
            <div style="width: 120px" class="mb-2">
                <el-alert
                    v-if="conflict.status ==='success'"
                    :closable="false"

                    title="Оплачен"
                    type="success">
                </el-alert>
                <el-alert
                    v-if="conflict.status ==='cancelled'"
                    :closable="false"
                    title="Отменен"
                    type="error">
                </el-alert>
            </div>
            <div class="mb-2">
                <label>
                    Дата заказа:
                </label>
                {{conflict.created_at}}
            </div>
            <div class="mb-2">
                <label>
                    Имя:
                </label>
                {{conflict.client.name}}
            </div>
            <div class="mb-2">
                <label>
                    Телефон:
                </label>
                {{conflict.client.phone}}
            </div>
            <div class="mb-2">
                <label>
                    Дата брони:
                </label>
                {{conflict.date}}
            </div>
            <div class="mb-2">
                <label>
                    Начало брони:
                </label>
                {{conflict.start}}
            </div>
            <div class="mb-2">
                <label>
                    Окончание брони:
                </label>
                {{conflict.end}}
            </div>
            <div class="d-flex flex-wrap">
                <div v-for="reservation in conflict.reservations" style="padding: 10px 20px;
             border: 1px dashed #000000;
             margin-right: 10px;
            border-radius: 6px;">
                <span v-if="reservation.reservationable_type === 'App\\Models\\Cabinet'">
                    Кабинка
                </span>
                    <span v-if="reservation.reservationable_type === 'App\\Models\\Place'">
                    Место
                </span>

                    {{reservation.reservationable.number}}
                </div>
            </div>
        </section>

    </section>
</template>
<script>
    export default {
        props: {
            item: {
                type:Object,
                required:true,
            }
        },
        data() {
            return {
                loaded:false,
                order: {},
                conflict_orders:[]
            }
        },
        methods: {
            checkOrderStatus() {
                axios.get('/admin/api/order/' + this.item.id + '/status')
                    .then((response) => {
                        this.item.status =response.data;

                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },
            showOrder() {
                axios.get('/admin/api/order/' + this.item.id + '/manual')
                    .then((response) => {
                        this.order = response.data.order;
                        this.conflict_orders = response.data.conflict_orders;
                        this.loaded = true;
                    })
            }
        },
        mounted() {
            this.showOrder();
        }
    }
</script>
