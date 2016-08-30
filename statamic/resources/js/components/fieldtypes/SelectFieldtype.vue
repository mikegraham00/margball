<template>
    <div class="select select-full" :data-content="label">
    	<select :name="name" v-model="data">
    		<option v-for="option in selectOptions" :value="option.value">{{ option.text }}</option>
    	</select>
    </div>
</template>

<script>
module.exports = {

    props: ['name', 'data', 'config', 'options'],

    data: function() {
        return {
            keyed: false,
            selectOptions: []
        }
    },

    ready: function() {
        if (this.options) {
            this.selectOptions = this.options;
        } else {
            this.selectOptions = this.config.options;
        }
    },

    computed: {
        label: function() {
            var option = _.findWhere(this.selectOptions, {value: this.data});
            return (option) ? option.text : this.data;
        }
    }
};
</script>
