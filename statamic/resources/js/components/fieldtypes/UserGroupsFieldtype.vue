<template>
    <div class="user-groups-fieldtype-wrapper">
        <div v-if="loading" class="loading loading-basic">
            <span class="icon icon-circular-graph animation-spin"></span> {{ translate('cp.loading') }}
        </div>
        <checkboxes-fieldtype v-if="! loading"
            :data.sync="data"
            :config="checkboxesConfig">
        </checkboxes-fieldtype>
    </div>
</template>

<script>
module.exports = {

    props: ['data', 'config', 'name'],

    data: function() {
        return {
            loading: true,
            groups: {}
        };
    },

    computed: {

        checkboxesConfig: function() {
            return { options: this.groups };
        }

    },

    methods: {
        getGroups: function() {
            this.$http.get(cp_url('/users/groups'), function(data) {
                var groups = {};
                _.each(data, function(group, id) {
                    groups[id] = group.title;
                });

                this.groups = groups;
                this.loading = false;
            });
        }
    },

    ready: function() {
        this.getGroups();
    }
};
</script>
