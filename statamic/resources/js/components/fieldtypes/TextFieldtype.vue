<template>
    <input :type="mode" :class="classes" v-model="data" />
</template>

<script>
module.exports = {

    props: ['name', 'data', 'config'],

    data: function() {
    	return {
    		mode: this.config.mode || 'text'
    	}
    },

    computed: {
        classes: function() {
            return 'form-control type-' + this.mode;
        }
    }

};
</script>
