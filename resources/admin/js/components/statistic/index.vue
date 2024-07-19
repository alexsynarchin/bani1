<template>
    <section>
     <div class="d-flex align-items-center mb-4">
         <label style="margin-right: 15px ">Выбрать период</label>
         <el-date-picker
             style="min-width: 480px"
             v-model="form.date_range"
             value-format="yyyy-MM-dd"
             type="daterange"
             range-separator="По"
             start-placeholder="Дата начала"
             end-placeholder="Дата окончания">
         </el-date-picker>
         <el-button type="primary" style="margin-left: 15px" @click.prevent="getOrders()">Показать</el-button>
     </div>
        <el-table
            :data="statistic"
            :summary-method="getSummaries"
            show-summary
            style="width: 100%">
            <el-table-column
                prop="current_date"
                label="Дата">
            </el-table-column>
            <el-table-column
                prop="places"
                label="Всего мест">
            </el-table-column>
            <el-table-column
                prop="cabinetes"
                label="Всего кабинок">
            </el-table-column>
            <el-table-column
                prop="standard"
                label="Обычное"
            ></el-table-column>
            <el-table-column
                prop="sert"
                label="Подарочные карты">
            </el-table-column>
            <el-table-column
                prop="transfer"
                label="Перенос">
            </el-table-column>
            <el-table-column
                prop="founder"
                label="Учредитель">
            </el-table-column>
            <el-table-column
                prop="invitation"
                label="Пригласительный">
            </el-table-column>
            <el-table-column
                prop="offsetting"
                label="Взаимозачет">
            </el-table-column>
            <el-table-column
                prop="non_cash"
                label="Безнал">
            </el-table-column>
            <el-table-column
                prop="sum"
                label="Сумма">
                <template slot-scope="scope">
                    {{scope.row.sum}} руб.
                </template>
            </el-table-column>
        </el-table>
    </section>
</template>
<script>
    export default {
        data() {
            return {
                statistic: [],
                form: {
                  date_range:[]
                }
            }
        },
        methods: {
            getOrders() {
                this.$root.isLoading = true;
                axios.get('/admin/api/statistic', {params: this.form})
                    .then((response) => {
                        this.$root.isLoading = false;
                        this.statistic = response.data
                    })
            },
            getSummaries(param) {
                const { columns, data } = param;
                const sums = [];
                columns.forEach((column, index) => {
                    if (index === 0) {
                        sums[index] = 'Итого';
                        return;
                    }
                    const values = data.map(item => Number(item[column.property]));
                    if (!values.every(value => isNaN(value))) {
                        sums[index] = values.reduce((prev, curr) => {
                            const value = Number(curr);
                            if (!isNaN(value)) {
                                return prev + curr;
                            } else {
                                return prev;
                            }
                        }, 0);
                        if(index === 10) {
                            sums[index] = sums[index] + ' руб.'
                        }
                    } else {
                        sums[index] = 'N/A';
                    }
                });

                return sums;
            }
        },
        mounted() {
            this.getOrders();
        }
    }
</script>
<styles lang="scss" scoped>

 .el-table thead{
        font-size: 12px !important;
    }
</styles>
