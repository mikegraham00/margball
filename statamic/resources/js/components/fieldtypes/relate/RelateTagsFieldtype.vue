<template>
    <select v-el:tags multiple></select>
</template>

<script>
export default {

    props: [
        'data',
        'suggestions',
        'maxItems',
        'create'
    ],


    ready() {

        let self = this;

        // Ensure we have an array
        if (typeof this.data === 'string') {
            this.data = [this.data];
        }

        $(this.$els.tags).selectize({
            options: this.suggestions,
            items: this.data,
            maxItems: this.maxItems,
            plugins: ['drag_drop', 'remove_button'],
            onChange: function(value) {
                self.data = value;
            },
            create: this.create
        });

    }

}
</script>
