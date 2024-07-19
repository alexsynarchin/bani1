<template>
<section>
    <data-tables v-loading="listLoading" :data="orders" :table-props="tableProps">
        <el-table-column
            label="Номер"
            sortable
        >
            <template slot-scope="scope">
                <div style="word-break: normal">
                    {{scope.row.id}}
                </div>
            </template>
        </el-table-column>
        <el-table-column
            label="Дата заказа"
            prop="created_at"
            sortable
        >
        </el-table-column>
        <el-table-column
            label="Сумма"
            prop="price"
            sortable
        >
        </el-table-column>
        <el-table-column
            label="Дата брони"
            prop="date"
            sortable
        >
        </el-table-column>
        <el-table-column
            label="Начало брони"
            prop="start"
            sortable
        >
            <template slot-scope="scope">
                {{formatTime(scope.row.start)}}
            </template>
        </el-table-column>
        <el-table-column
        label="Конец брони"
        prop="end"
        sortable
    >
            <template slot-scope="scope">
                {{formatTime(scope.row.end)}}
            </template>

        </el-table-column>
        <el-table-column
        label="Статус"
        >
            <template slot-scope="scope">
                <el-alert
                    v-if="scope.row.status ==='success'"
                    :closable="false"

                    title="Оплачен"
                    type="success">
                </el-alert>
                <el-alert
                    v-if="scope.row.status ==='cancelled'"
                    :closable="false"
                    title="Отменен"
                    type="error">
                </el-alert>

            </template>
        </el-table-column>



        <el-table-column
            label="Действия"
        >
            <template slot-scope="scope">
                <el-button
                    size="mini"
                    type="primary"
                    @click="handleView(scope.$index, scope.row)">Подробнее</el-button>
            </template>
        </el-table-column>
    </data-tables>

    <el-dialog
        :visible.sync="ModalVisible"
        >
        <order-item
            v-if="ModalVisible"
            :item="order_item"></order-item>
        <span slot="footer" class="dialog-footer">
    <el-button @click="ModalVisible = false">Закрыть</el-button>
  </span>
    </el-dialog>
</section>
</template>
<script>
import Pagination from '../Pagination'
import OrderItem from "./item"
import dayjs from "dayjs";
require('dayjs/locale/ru');
export default {
    components: {
        OrderItem,  Pagination
    },
    data() {
        return {
            total: 0,
            listLoading: true,
            listQuery: {
                page: 1,
                limit: 50,
            },
            orders: [],
            order_item:null,
            ModalVisible:false,
            tableProps: {
                "row-class-name": function (row) {

                    if ((row.row.status === 'cancelled') ||  (row.row.status === 'success') ) {
                        return ''
                    } else {
                        return 'warning-row';
                    }

                }
            },
        }
    },
    methods: {
        formatTime(time) {
            return dayjs(time).format("H:mm");
        },
        getOrders() {
            this.listLoading = true;
            axios.get('/admin/api/orders', {params: this.listQuery})
            .then((response) => {
                this.listLoading = false;
                this.orders = response.data
              //  this.total = response.data.meta.total
            })
        },
        handleView(index, item) {
            this.ModalVisible = true;
            this.order_item = item;
        },
    },
    mounted() {
        this.getOrders();
    }
}
</script>
<style>
.el-table .warning-row {
    background-color: #f8d7da;
}

.el-table .success-row {
    background: #f0f9eb;
}
</style>
