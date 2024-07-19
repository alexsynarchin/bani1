<template>
    <event-form :form="form"
                :action_type="action_type"
                :action_url="action_url"
                @close-modal="closeModal"
                @submit-form="submitForm"
    ></event-form>
</template>
<script>
import EventForm from './components/form';
export default {
    components: {EventForm},
    props:['id'],
    data() {
        return {
            form: {},
            action_type: 'put',
            action_url: '/admin/api/event-dates/' + this.id
        }
    },
    methods: {
        getData() {
            axios.get('/admin/api/event-dates/' + this.id)
                .then((response) => {
                    this.form = response.data;
                })
        },
        closeModal() {
            this.$emit('close-modal');
        },
        submitForm(data) {
            this.$emit('edit-event-date', data);
        },
    },
    mounted() {
        this.getData();
    }
}
</script>
