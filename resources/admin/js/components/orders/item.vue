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
        <div class="d-flex flex-wrap">
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
        <div class="mt-3">
           <!-- <el-button type="primary" @click.prevent="checkOrderStatus" v-if="item.status !== 'success' && item.status !== 'cancelled'">
                Проверить заказ
            </el-button>-->
        </div>
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
                axios.get('/admin/api/order/' + this.item.id)
                    .then((response) => {
                        this.order = response.data;
                        this.loaded = true;
                    })
            }
        },
        mounted() {
            this.showOrder();
        }
    }
</script>
