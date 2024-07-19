<template>
    <section>
        <ul class="dashboard-places-list">
            <li
                @click.prevent="selectNav(nav, index)"
                class="dashboard-places-list__item" v-for="(nav, index) in navs"
                    :class="{
                        'dashboard-places-list__item--selected' : index === selectedIndex,
                    }"
            >
                {{nav.date_string}}
            </li>
        </ul>
    </section>
</template>
<script>
    export default {
        data() {
            return {
                date: {},
                navs:[],
                selectedIndex:0,
            }
        },
        methods: {
            getNavs() {
                axios.get('/admin/api/places/navigation')
                .then((response) => {
                    this.navs = response.data.dates;
                    this.$emit('select-date', response.data.now)
                })
            },
            selectNav(date, index) {
                this.selectedIndex = index;
                this.$emit('select-date', date)
            },
            getDate() {

            }
        },
        mounted() {
            this.getDate()
            this.getNavs();
        }
    }
</script>
