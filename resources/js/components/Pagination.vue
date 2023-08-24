<template>
    <div>
        <button @click="changePage(page - 1)" :disabled="page <= 1">Previous</button>
        <span>{{ page }} / {{ total }}</span>
        <button @click="changePage(page + 1)" :disabled="page >= total">Next</button>
    </div>
</template>

<script>
export default {
    props: ['data'],
    computed: {
        page() {
            return this.data.current_page;
        },
        total() {
            return this.data.last_page;
        }
    },
    methods: {
        changePage(page) {
            if (page >= 1 && page <= this.total) {
                this.$emit('pagination-change-page', page);
            }
        }
    }
}
</script>
