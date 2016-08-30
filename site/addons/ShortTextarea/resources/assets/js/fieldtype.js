Vue.component('short_textarea-fieldtype', {

    template: '' +
      '<div>' +
        '<textarea v-model="data" maxlength="500" rows="6" style="width: 100%;"></textarea>' +
      '</div>' +
    '',

    props: ['data', 'config', 'name'],

    data: function() {
        return {
            //
        };
    },

    computed: {
        //
    },

    

    methods: {
        //
    },

    ready: function() {
        //
    }

});
