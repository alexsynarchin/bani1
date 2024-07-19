<template>
    <section>
        <h3>Праздничные дни</h3>
        <div class="mb-3">
            <el-button type="success" @click.prevent="openModal('create', null)">Добавить праздник</el-button>
        </div>
        <el-table
            :data="event_dates"
            style="width: 100%">
            <el-table-column
                prop="date"
                label="Дата">
            </el-table-column>
            <el-table-column
                prop="start_time"
                label="Начало бронирования">
            </el-table-column>
            <el-table-column
                label="Действия">
                <template slot-scope="scope">
                    <el-button type="primary" @click.prevent="openModal('edit', scope.row.id)">Редактировать</el-button>
                    <el-button type="danger" @click.prevent="handleDelete(scope.row)">Удалить</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog
            :title="modalTitle"
            :visible.sync="modalVisible"
            width="50%"
            :before-close="handleClose"
        >
            <edit
                v-if="modalState === 'edit' && EventEditId"
                :id="EventEditId"
                @edit-event-date="editEventDate"
                @close-modal="closeModal"
            ></edit>
            <create
                @create-event-date="createEventDate"
                v-if="modalState === 'create'"
                @close-modal="closeModal"
            ></create>
        </el-dialog>
    </section>
</template>
<script>
    import Create from "./create";
    import Edit from './edit';
   export default {
       components: {
           Create, Edit,
       },
       data() {
           return {
               modalVisible:false,
               modalState:"",
               EventEditId:null,
               event_dates:[],
           }
       },
       computed: {
           modalTitle: function () {
               let title = "";
               if(this.modalState === 'edit') {
                   title = "Редактироваить Праздничный день"
               }
               else {
                   title = "Добавить Праздничный день"
               }
               return title;
           },
       },
       methods: {
           getData() {
               axios.get('/admin/api/event-dates')
                   .then((response) => {
                       this.event_dates = response.data;
                   })
           },
           closeModal() {
               this.modalState="";
               this.modalVisible = false;
               this.EventEditId = null;
           },
           handleClose() {
                this.closeModal();
           },
           openModal(type ,id) {
               this.EventEditId = id;
               this.modalState = type;
               this.modalVisible = true;
           },
           createEventDate(event_date) {
               this.event_dates.push(event_date);
               this.closeModal();

           },
           editEventDate(data) {
               let index = this.event_dates.findIndex(item => item.id === data.id);
               console.log(index);
               this.event_dates[index].date =data.date;
               this.event_dates[index].start_time =data.start_time;

               this.closeModal();
           },
           handleDelete(item) {
               this.$confirm('Вы уверены что хотите удалить праздник', 'Удаление праздничного дня', {
                   confirmButtonText: 'Удалить',
                   cancelButtonText: 'Отмена',
                   type: 'warning'
               }).then(() => {
                   axios.delete('/admin/api/event-dates/' + item.id)
                       .then((response) => {
                           let index = this.event_dates.findIndex(item => item.id === response.data.id);
                           this.event_dates.splice(index, 1);
                           this.$message({
                               type: 'success',
                               message: 'Праздник удален'
                           });
                       });

               }).catch(() => {

               });
           },
       },
       mounted() {
           this.getData();
       }
   }
</script>
